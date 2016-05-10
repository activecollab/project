<?php

/*
 * This file is part of the App project.
 *
 * (c) A51 doo <info@activecollab.com>. All rights reserved.
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
}
