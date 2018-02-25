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
            return Stash\Cache::make($container->config->cache->driver, $container->config->cache->config);
            // return Stash\Cache::make(config('cache.driver'), config('cache.config'));
        });
    }
}
