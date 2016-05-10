<?php

/*
 * This file is part of the App project.
 *
 * (c) My Company <info@example.com>. All rights reserved.
 */

namespace ActiveCollab\App\Controller;

use ActiveCollab\App\ContainerAccess\ContainerAccessTrait;
use ActiveCollab\Controller\Controller as BaseController;

/**
 * @package ActiveCollab\App\Controller
 */
abstract class Controller extends BaseController
{
    use ContainerAccessTrait;
}
