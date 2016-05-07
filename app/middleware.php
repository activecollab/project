<?php

/*
 * This file is part of the Active Collab ID project.
 *
 * (c) A51 doo <info@activecollab.com>. All rights reserved.
 */

/**
 * Specify application wide middlewares.
 *
 * @var \Interop\Container\ContainerInterface
 */
if (empty($container)) {
    throw new RuntimeException('DI container not found');
}

// Add HTTP cache (Etag)
// $app->add(new \Slim\HttpCache\Cache('public', 86400));

// Authenticate user with configured adapters
// $app->add(new \ActiveCollab\Id\Middleware\UserAuthentication($container));

// Add Etag interceptor (execute after authentication middlewares because we need authenticated user)
// $app->add(new \ActiveCollab\Id\Middleware\Etag($container));
