<?php

/*
 * This file is part of the App project.
 *
 * (c) A51 doo <info@activecollab.com>. All rights reserved.
 */

// To help the built-in PHP dev server, check if the request was actually for
// something which should probably be served as a static file
if (PHP_SAPI == 'cli-server') {
    $parsed_request_uri = parse_url($_SERVER['REQUEST_URI']);

    if (isset($parsed_request_uri['path']) && $parsed_request_uri['path'] && is_file(__DIR__ . $parsed_request_uri['path'])) {
        return false;
    }
}

require __DIR__ . '/../vendor/autoload.php';

$app = new \Slim\App(require __DIR__ . '/../app/settings.php');
$container = $app->getContainer();

require __DIR__ . '/../app/dependencies.php';
require __DIR__ . '/../app/middleware.php';
require __DIR__ . '/../app/routes.php';

$app->run();

