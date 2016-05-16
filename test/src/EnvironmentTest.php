<?php

/*
 * This file is part of the App project.
 *
 * (c) My Company <info@example.com>. All rights reserved.
 */

namespace ActiveCollab\App\Test;

use ActiveCollab\App\Test\Base\TestCase;

/**
 * @package ActiveCollab\App\Test
 */
class EnvironmentTest extends TestCase
{
    /**
     * Test minimum PHP version.
     */
    public function testPhpVersion()
    {
        $this->assertTrue(version_compare(PHP_VERSION, '7.0.0', '>='));
    }

    /**
     * Test if we have a good application root in tests.
     */
    public function testAppRoot()
    {
        $app_root = $this->app_root;

        $this->assertNotEmpty($app_root);
        $this->assertTrue(is_dir($app_root));
    }

    /**
     * Test if application identifier is properly set in tests.
     */
    public function testAppIdentifier()
    {
        $app_name = $this->app_name;
        $app_version = $this->app_version;

        $this->assertNotEmpty($app_name);
        $this->assertNotEmpty($app_version);

        $this->assertEquals("{$app_name} v{$app_version}", $this->app_identifier);
    }
}
