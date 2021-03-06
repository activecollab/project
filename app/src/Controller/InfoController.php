<?php

/*
 * This file is part of the App project.
 *
 * (c) My Company <info@example.com>. All rights reserved.
 */

namespace ActiveCollab\App\Controller;

/**
 * @package ActiveCollab\App\Controller
 */
class InfoController extends Controller
{
    /**
     * @return array
     */
    public function index()
    {
        return [
            'application' => $this->app_name,
            'version' => $this->app_version,
            'status' => 'current',
            'environment' => $this->app_env,
        ];
    }
}
