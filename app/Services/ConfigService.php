<?php

namespace App\Services;

use PHLAK\Config;

class ConfigService extends Service
{
    /**
     * Register config service.
     *
     * @return void
     */
    public function register()
    {
        $this->bind('config', function ($container) {
            return new Config\Config($container->settings->all());
        });
    }
}
