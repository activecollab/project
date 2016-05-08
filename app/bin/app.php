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

use ActiveCollab\Container\Container;
use ActiveCollab\ContainerAccess\ContainerAccessInterface;
use Symfony\Component\Console\Application;

$application = new Application('App', file_get_contents(APP_PATH . '/VERSION'));

$container = new Container();
$container['settings'] = function () {
    $settings = include dirname(__DIR__) . '/settings.php';

    return $settings['settings'];
};
require_once dirname(__DIR__) . '/dependencies.php';

$dirs_to_scan = [
    APP_PATH . '/vendor/activecollab/bootstrap/src/command' => '\ActiveCollab\Bootstrap\Command',
    APP_PATH . '/app/src/Command' => '\ActiveCollab\App\Command',
];

foreach ($dirs_to_scan as $application_commands_path => $application_commands_namespace) {
    $application_commands_path_len = strlen($application_commands_path);

    if (is_dir($application_commands_path)) {
        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($application_commands_path), RecursiveIteratorIterator::SELF_FIRST) as $file) {
            if ($file->isFile() && $file->getExtension() == 'php') {
                $class_name = ($application_commands_namespace . '\\' . implode('\\', explode('/', substr($file->getPath() . '/' . $file->getBasename('.php'), $application_commands_path_len + 1))));

                if (!class_exists($class_name, false)) {
                    require_once $file->getPathname();
                }

                if (!(new ReflectionClass($class_name))->isAbstract()) {
                    /** @var \Symfony\Component\Console\Command\Command|ContainerAccessInterface $command */
                    $command = new $class_name();

                    if ($command instanceof ContainerAccessInterface) {
                        $command->setContainer($container);
                    }

                    $application->add($command);
                }
            }
        }
    }
}

$application->run();
