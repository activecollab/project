<?php

/*
 * This file is part of the App project.
 *
 * (c) My Company <info@example.com>. All rights reserved.
 */

/**
 * Bootstrap command line application.
 */
date_default_timezone_set('UTC');

defined('APP_PATH') or define('APP_PATH', dirname(dirname(__DIR__)));

require_once APP_PATH . '/vendor/autoload.php';

use ActiveCollab\Bootstrap\ClassFinder\ClassFinder;
use ActiveCollab\ContainerAccess\ContainerAccessInterface;
use Slim\Container;
use Symfony\Component\Console\Application;

$container = new Container();
$container['settings'] = function () {
    $settings = require dirname(__DIR__) . '/settings.php';

    return $settings['settings'];
};

require_once dirname(__DIR__) . '/config.php';
require_once dirname(__DIR__) . '/dependencies.php';

$application = new Application($container['app_name'], $container['app_version']);

(new ClassFinder())->scanDirsForInstances([
    APP_PATH . '/vendor/activecollab/bootstrap/src/command' => '\ActiveCollab\Bootstrap\Command',
    APP_PATH . '/app/src/Command' => '\ActiveCollab\App\Command',
], function (\Symfony\Component\Console\Command\Command $command) use (&$application, &$container) {
    if ($command instanceof ContainerAccessInterface) {
        $command->setContainer($container);
    }

    $application->add($command);
});

$application->run();
