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
