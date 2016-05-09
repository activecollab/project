<?php

require 'vendor/autoload.php';

$filesystem = new \ActiveCollab\FileSystem\FileSystem(new \ActiveCollab\FileSystem\Adapter\LocalAdapter(__DIR__));

foreach ($filesystem->get)