<?php

/*
 * This file is part of the App project.
 *
 * (c) A51 doo <info@activecollab.com>. All rights reserved.
 */

/**
 * Bootstrap command line application.
 */
date_default_timezone_set('UTC');

defined('APP_PATH') or define('APP_PATH', dirname(dirname(__DIR__)));

require_once APP_PATH . '/vendor/autoload.php';

use ActiveCollab\ContainerAccess\ContainerAccessInterface;
use Slim\Container;
use Symfony\Component\Console\Application;
use ActiveCollab\Bootstrap\ClassFinder\ClassFinder;

$application = new Application('App', file_get_contents(APP_PATH . '/VERSION'));

$container = new Container();
$container['settings'] = function () {
    $settings = include dirname(__DIR__) . '/settings.php';

    return $settings['settings'];
};
require_once dirname(__DIR__) . '/dependencies.php';

(new ClassFinder())->scanDirs([
    APP_PATH . '/vendor/activecollab/bootstrap/src/command' => '\ActiveCollab\Bootstrap\Command',
    APP_PATH . '/app/src/Command' => '\ActiveCollab\App\Command',
], function(\Symfony\Component\Console\Command\Command $command) use (&$application, &$container) {
    if ($command instanceof ContainerAccessInterface) {
        $command->setContainer($container);
    }

    $application->add($command);
});

$application->run();
