<?php

namespace App\Traits;

use Slim\Container;

trait Cacheable
{
    /**
     * Instantiate an object from the cache. Returns a brand new object if not
     * already cached or caching is disabled.
     *
     * @param \Slim\Container $container The Slim application container
     * @param mixed           $args      Arguments to be bassed to the class constructor
     *
     * @return mixed An instantiated object
     */
    public static function createFromCache(Container $container, ...$args)
    {
        if (! $container->config->cache->enabled) {
            return new static(...$args);
        }

        $key = __CLASS__ . '(' . implode($args, ',') . ')';

        return $container->cache->rememberForever($key, function () use ($args) {
            return new static(...$args);
        });
    }
}
