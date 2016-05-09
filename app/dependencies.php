<?php

/*
 * This file is part of the App project.
 *
 * (c) A51 doo <info@activecollab.com>. All rights reserved.
 */

use ActiveCollab\Bootstrap\ClassFinder\ClassFinder;

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

// Controller action result encoder
$container['result_encoder'] = function() {
    return new \ActiveCollab\Controller\ResultEncoder\ResultEncoder();
};

// Scan app/src/Controller directory for application controllers
foreach ((new ClassFinder())->scanDirForClasses($container['app_root'] . '/app/src/Controller', '\ActiveCollab\App\Controller', true) as $class_path => $class_name) {
    $container[ltrim($class_name, '\\')] = function ($c) use ($class_name) {
        return new $class_name($c, $c['result_encoder']);
    };
}
