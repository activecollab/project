<?php

/*
 * This file is part of the App project.
 *
 * (c) My Company <info@example.com>. All rights reserved.
 */

$router = new \ActiveCollab\Bootstrap\Router\Router($app, 'ActiveCollab\App\Controller');

$app->group('/api/v1', function () use ($router) {
    $router->map('/', \ActiveCollab\App\Controller\InfoController::class, ['GET' => 'index']);
});
