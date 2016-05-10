<?php

/*
 * This file is part of the App project.
 *
 * (c) My Company <info@example.com>. All rights reserved.
 */

namespace ActiveCollab\App\Command;

use ActiveCollab\App\ContainerAccess\ContainerAccessTrait;
use ActiveCollab\Bootstrap\Command\Command as BaseCommand;

/**
 * @package ActiveCollab\App\Command
 */
abstract class Command extends BaseCommand
{
    use ContainerAccessTrait;
}
