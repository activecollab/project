<?php

/*
 * This file is part of the App project.
 *
 * (c) My Company <info@example.com>. All rights reserved.
 */

namespace ActiveCollab\App\Test\Base;

use ActiveCollab\App\Test\Base\Traits\TestCaseTrait;
use ActiveCollab\Bootstrap\TestCase\ModelCommandTestCase as BaseModelCommandTestCase;

/**
 * @package ActiveCollab\App\Test\Base
 */
abstract class ModelCommandTestCase extends BaseModelCommandTestCase
{
    use TestCaseTrait;

    /**
     * {@inheritdoc}
     */
    protected function getModelNamespace()
    {
        return '\\ActiveCollab\\App\\Model';
    }
}
