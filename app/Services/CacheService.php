<?php

namespace App\Services;

use PHLAK\Stash;

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
            $driver = $container->config->get('cache.driver', 'file');
            $config = $container->config->get("cache.drivers.{$driver}");

            return Stash\Cache::$driver($config);
        });
    }
}
