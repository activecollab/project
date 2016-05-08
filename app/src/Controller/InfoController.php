<?php

/*
 * This file is part of the App project.
 *
 * (c) A51 doo <info@activecollab.com>. All rights reserved.
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
            'application' => 'ID',
            'version' => $this->app_version,
            'status' => 'current',
            'environment' => $this->app_env,
        ];
    }
}
