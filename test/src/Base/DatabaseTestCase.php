<?php

/*
 * This file is part of the App project.
 *
 * (c) My Company <info@example.com>. All rights reserved.
 */

namespace ActiveCollab\App\Test\Base;

use ActiveCollab\Bootstrap\TestCase\DatabaseTestCase as BaseDatabaseTestCase;

/**
 * @package ActiveCollab\App\Test\Base
 */
class DatabaseTestCase extends BaseDatabaseTestCase
{
    use TestCaseTrait;

    /**
     * @return string
     */
    protected function getAppVersion()
    {
        $version_file = $this->getAppRoot() . '/VERSION';

        if (is_file($version_file)) {
            $app_version = trim(file_get_contents($version_file));
        }

        if (empty($app_version)) {
            $app_version = '1.0.0';
        }

        return $app_version;
    }

    
}
