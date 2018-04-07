<?php

namespace App\Services;

use PHLAK\Stash;
use App\Exceptions\InvalidConfigurationException;

class CacheService extends Service
{
    /**
     * Register cache service.
     *
     * @return void
     */
    public function register()
    {
        $this->bind('cache', function ($container) {
            $driver = $container->config->get('cache.driver');
            $config = $container->config->get("cache.drivers.{$driver}");

            if (! isset($driver, $config)) {
                throw new InvalidConfigurationException('The cache configuraton is invalid');
            }

            return Stash\Cache::$driver($config);
        });
    }
}
