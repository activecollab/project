<?php

/*
 * This file is part of the App project.
 *
 * (c) A51 doo <info@activecollab.com>. All rights reserved.
 */

if (empty($container)) {
    throw new RuntimeException('DI container not found');
}

$dotenv = new \Dotenv\Dotenv(dirname(__DIR__) . '/config');

if (empty(getenv('APP_ENVIRONMENT')) || getenv('APP_ENVIRONMENT') == 'development') {
    $dotenv->load();
}

$dotenv->required([
    'APP_LOG_HANDLER',

    'APP_MYSQL_HOST',
    'APP_MYSQL_PORT',
    'APP_MYSQL_USER',
    'APP_MYSQL_PASS',
    'APP_MYSQL_NAME',
]);

switch (getenv('APP_LOG_HANDLER')) {
    case 'file':
        $dotenv->required(['APP_LOG_DIR']);
        break;
    case 'graylog':
        $dotenv->required(['APP_GRAYLOG_HOST']);
        $dotenv->required(['APP_GRAYLOG_PORT']);
        break;
}

if (getenv('APP_LOG_HANDLER') == 'file') {
    $dotenv->required(['APP_LOG_DIR']);
}

$container['dotenv'] = $dotenv;
