<?php

/*
 * This file is part of the App project.
 *
 * (c) My Company <info@example.com>. All rights reserved.
 */

namespace ActiveCollab\App\Test\Base;

/**
 * @package ActiveCollab\App\Test\Base
 */
trait TestCaseTrait
{
    /**
     * {@inheritdoc}
     */
    protected function getAppVersion()
    {
        $version_file = $this->app_root . '/VERSION';

        if (is_file($version_file)) {
            $app_version = trim(file_get_contents($version_file));
        }

        if (empty($app_version)) {
            $app_version = '1.0.0';
        }

        return $app_version;
    }
}
