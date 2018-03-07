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
            $config = $container->config->get("cache.drivers.{$driver}", function () {
                return ['dir' => realpath(__DIR__ . '/../cache')];
            });

            return Stash\Cache::make($driver, $config);
        });
    }
}
