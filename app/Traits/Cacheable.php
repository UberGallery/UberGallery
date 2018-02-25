<?php

namespace App\Traits;

use Psr\Container\ContainerInterface;

trait Cacheable
{
    public static function createFromCache(ContainerInterface $container, ...$args)
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
