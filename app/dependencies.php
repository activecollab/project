<?php

/*
 * This file is part of the App project.
 *
 * (c) A51 doo <info@activecollab.com>. All rights reserved.
 */

/**
 * Specify dependencies.
 *
 * @var \Interop\Container\ContainerInterface
 */
if (empty($container)) {
    throw new RuntimeException('DI container not found');
}

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

$container['result_encoder'] = function() {
    return new \ActiveCollab\Controller\ResultEncoder\ResultEncoder();
};

$collections_path = $container['app_root'] . '/app/src/Controller';
$collections_path_len = strlen($collections_path);

foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($collections_path), RecursiveIteratorIterator::SELF_FIRST) as $file) {
    if ($file->isFile() && $file->getExtension() == 'php') {
        $class_name = ('\\ActiveCollab\\App\\Controller\\' . implode('\\', explode('/', substr($file->getPath() . '/' . $file->getBasename('.php'), $collections_path_len + 1))));

        if ((new ReflectionClass($class_name))->isAbstract()) {
            continue;
        }

        $container[ltrim($class_name, '\\')] = function ($c) use ($class_name) {
            return new $class_name($c, $c['result_encoder']);
        };
    }
}
