<?php

/*
 * This file is part of the App project.
 *
 * (c) My Company <info@example.com>. All rights reserved.
 */

namespace ActiveCollab\App\Test\Base;

use ActiveCollab\App\Test\Base\Traits\TestCaseTrait;
use ActiveCollab\Bootstrap\TestCase\RequestResponseTestCase as BaseRequestResponseTestCase;

/**
 * @package ActiveCollab\App\Test\Base
 */
abstract class RequestResponseTestCase extends BaseRequestResponseTestCase
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
