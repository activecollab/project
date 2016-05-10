<?php

/*
 * This file is part of the App project.
 *
 * (c) My Company <info@example.com>. All rights reserved.
 */

use ActiveCollab\Bootstrap\ClassFinder\ClassFinder;

if (empty($container)) {
    throw new RuntimeException('DI container not found');
}

// ---------------------------------------------------
//  Application
// ---------------------------------------------------

$container['app_identifier'] = function ($c) {
    return "App v$c[app_version]";
};

$container['app_version'] = function ($c) {
    return trim(file_get_contents($c['app_root'] . '/VERSION'));
};

$container['app_env'] = function () {
    $env = getenv('APP_ENVIRONMENT');

    if ($env && in_array($env, ['narrative', 'staging', 'production'])) {
        return $env;
    } else {
        return 'development';
    }
};

// app root
$container['app_root'] = function () {
    return dirname(__DIR__);
};

// app url
$container['app_url'] = function ($c) {
  if (in_array($c['app_env'], ['development', 'narrative'])) {
      if (!empty($c['settings']['app_url'])) {
          return $c['settings']['app_url'];
      }

      return 'http://' . $_SERVER['HTTP_HOST'];
  } elseif ($c['app_env'] == 'staging') {
      return 'https://app-staging.activecollab.com';
  } elseif ($c['app_env'] == 'production') {
      return 'https://app.activecollab.com';
  } else {
      throw new RuntimeException("Unknown environment '{$c['app_env']}'");
  }
};

// ---------------------------------------------------
//  Logger
// ---------------------------------------------------

$container['logger'] = function ($c) {
    $app_identifier = strtolower(explode(' ', $c['app_identifier'])[0]);

    $log = new \Monolog\Logger($app_identifier);
    $log_level = \Monolog\Logger::DEBUG;

    $formatter = new \Monolog\Formatter\LineFormatter("[%datetime%] %level_name%: %message% %context% %extra%\n", 'Y-m-d H:i:s');

    $log_handler = getenv('APP_LOG_HANDLER');

    switch ($log_handler) {
        case 'file':
            $log_dir = getenv('APP_LOG_DIR');

            if (empty($log_dir)) {
                throw new RuntimeException('APP_LOG_DIR is required for file log handler');
            }

            if (substr($log_dir, 0, 1) == '.') {
                $config_path = dirname(__DIR__) . '/config';
                $old_working_dir = getcwd();

                if ($old_working_dir != $config_path) {
                    chdir($config_path);
                }

                $log_dir = realpath($log_dir);

                if ($old_working_dir != $config_path) {
                    chdir($old_working_dir);
                }
            }

            $log_dir = rtrim($log_dir, '/');

            if (!is_writable($log_dir)) {
                throw new RuntimeException("We can't write logs to '$log_dir'");
            }

            $keep_for_days = $c['app_env'] == 'production' ? 30 : 5;

            $handler = new \Monolog\Handler\RotatingFileHandler("$log_dir/log.txt", $keep_for_days, $log_level);
            break;
        case 'graylog':
            $publisher = new \Gelf\Publisher(new \Gelf\Transport\UdpTransport(getenv('APP_GRAYLOG_HOST'), getenv('APP_GRAYLOG_PORT')));
            $handler = new \Monolog\Handler\GelfHandler($publisher, $log_level);
            $formatter = new \Monolog\Formatter\GelfMessageFormatter(null, null, '');
            break;
        case 'blackhole':
            $handler = new \Monolog\Handler\NullHandler($log_level);
            break;
        default:
            throw new RuntimeException("Unknown log handler '$log_handler'");
    }

    $handler->setFormatter($formatter);
    $handler->pushProcessor(new \Monolog\Processor\PsrLogMessageProcessor());

    $handler->pushProcessor(function (array $record) use ($app_identifier, $c) {
        $record['context'] = array_merge($record['context'], [
            'app' => $app_identifier,
            'ver' => $c['app_version'],
            'env' => $c['app_env'],
            'sapi' => php_sapi_name(),
        ]);

        return $record;
    });

    $log->pushHandler($handler);

    return $log;
};

// ---------------------------------------------------
//  DB Connection, Pool, Structure, Migrations, Models
// ---------------------------------------------------

$container['connection'] = function ($c) {
    $db_host = getenv('APP_MYSQL_HOST');
    $db_port = getenv('APP_MYSQL_PORT');
    $db_user = getenv('APP_MYSQL_USER');
    $db_pass = getenv('APP_MYSQL_PASS');
    $db_name = getenv('APP_MYSQL_NAME');

    return (new \ActiveCollab\DatabaseConnection\ConnectionFactory($c['logger']))->mysqli("$db_host:$db_port", $db_user, $db_pass, $db_name, 'utf8mb4');
};

$container['pool'] = function ($c) {
    $pool = new \ActiveCollab\DatabaseObject\Pool($c['connection'], $c['log']);
    $pool->setContainer($c);

    $types_file = $c['app_root'] . '/app/src/Model/types.php';

    if (is_file($types_file)) {
        $types = require $c['app_root'] . '/app/src/Model/types.php';

        foreach ($types as $type) {
            $pool->registerType($type);
        }
    }

    foreach ((new ClassFinder())->scanDirForClasses($c['app_root'] . '/app/src/Model/Producer', '\ActiveCollab\App\Model\Producer', true) as $class_name) {
        $pool->registerProducerByClass(str_replace('\\Producer\\', '\\', $class_name), $class_name);
    }

    return $pool;
};

$container['structure'] = function () {
    return new \ActiveCollab\App\Model\Structure();
};

$container['migrations_finder'] = function ($c) {
    return new \ActiveCollab\DatabaseMigrations\Finder\MigrationsInChangesetsFinder($c['logger'], '\ActiveCollab\Id\Model\Migrations', "$c[app_root]/app/src/Model/Migrations");
};

$container['migrations'] = function ($c) {
    return new \ActiveCollab\DatabaseMigrations\Migrations($c['connection'], $c['migrations_finder'], $c['logger']);
};

// ---------------------------------------------------
//  Controllers
// ---------------------------------------------------

// Controller action result encoder
$container['result_encoder'] = function () {
    return new \ActiveCollab\Controller\ResultEncoder\ResultEncoder();
};

// Scan app/src/Controller directory for application controllers
foreach ((new ClassFinder())->scanDirForClasses($container['app_root'] . '/app/src/Controller', '\ActiveCollab\App\Controller', true) as $class_path => $class_name) {
    $container[ltrim($class_name, '\\')] = function ($c) use ($class_name) {
        return new $class_name($c, $c['result_encoder']);
    };
}
