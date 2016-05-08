<?php

/*
 * This file is part of the App project.
 *
 * (c) A51 doo <info@activecollab.com>. All rights reserved.
 */

/**
 * Bootstrap test environment.
 */
defined('BASE_PATH') || define('BASE_PATH', realpath(__DIR__));

date_default_timezone_set('GMT');

require dirname(__DIR__) . '/vendor/autoload.php';

require __DIR__ . '/src/Base/DatabaseTestCase.php';
require __DIR__ . '/src/Base/ModelTestCase.php';
