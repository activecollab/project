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
abstract class DatabaseTestCase extends BaseDatabaseTestCase
{
    use TestCaseTrait;
}
