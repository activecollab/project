<?php

/*
 * This file is part of the App project.
 *
 * (c) My Company <info@example.com>. All rights reserved.
 */

namespace ActiveCollab\App\Controller;

use ActiveCollab\App\ContainerAccess\ContainerAccessTrait;
use ActiveCollab\Bootstrap\Controller\TypeController as BaseTypeController;

/**
 * @package ActiveCollab\App\Controller
 */
abstract class TypeController extends BaseTypeController
{
    use ContainerAccessTrait;

    /**
     * {@inheritdoc}
     */
    protected function getModelsNamespace(): string
    {
        return 'ActiveCollab\\App\\Model';
    }

    /**
     * {@inheritdoc}
     */
    protected function getCollectionsNamespace(): string
    {
        return 'ActiveCollab\\App\\Model\\Collection';
    }
}
