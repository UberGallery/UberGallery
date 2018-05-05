<?php

namespace App\Services;

use PHLAK\Config;
use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;
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
            $this->loadEnvironmentVariables();

            $config = new Config\Config();

            foreach (new DirectoryIterator($container->config_path) as $file) {
                if ($this->isIgnored($file)) {
                    continue;
                }

                $config->load($file->getPathname(), $file->getBasename('.php'));
            }

            return $config;
        });
    }

    /**
     * Load environment variables from a .env file.
     *
     * @return void
     */
    protected function loadEnvironmentVariables()
    {
        try {
            (new Dotenv($this->container->env_path))->load();
        } catch (InvalidPathException $exception) {
            // Ignore it
        }
    }

    /**
     * Determine if a file should be ignored.
     *
     * @param  \DirectoryIterator $file An instance of a DirectoryIterator file
     *
     * @return boolean True if file should be ignored, otherwise false
     */
    protected function isIgnored(DirectoryIterator $file)
    {
        if ($file->isDot() || $file->isDir()) {
            return true;
        }

        if (in_array($file->getBasename(), $this->ignored)) {
            return true;
        }

        if ($file->getExtension() != 'php') {
            return true;
        }

        return false;
    }
}
