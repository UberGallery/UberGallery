<?php

namespace App\Services;

use PHLAK\Config;
use DirectoryIterator;

class ConfigService extends Service
{
    /** @var array Array of ignored configuration file names */
    protected $ignored = [
        'app.php'
    ];

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

        $this->loadConfigs($this->container->config_path);
    }

    /**
     * Load config files from a directory.
     *
     * @param string $path Path to a directory of configuration files
     *
     * @return void
     */
    protected function loadConfigs($path)
    {
        foreach (new DirectoryIterator($path) as $file) {
            if ($file->isDot() || $file->isDir() || in_array($file->getBasename(), $this->ignored)) {
                continue;
            }

            $this->container->config->load($file->getPathname(), $file->getBasename('.php'));
        }
    }
}
