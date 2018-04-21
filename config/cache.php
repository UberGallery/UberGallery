<?php

/**
 * This file contains configuration settings for your application cache. You can
 * speed up your application by enabling caching. By default the 'file' driver
 * is enabled but can use any of several different caching drivers.
 */

return [

    /**
     * Whether or not the cache is enabled.
     *
     * Default value: false
     */
    'enabled' => env('CACHE_ENABLED', false),

    /**
     * The caching driver to usefor caching image and thumbnail responses.
     *
     * Available options: 'file', 'memcached', 'redis', 'apcu'
     *
     * Default value: 'file'
     */
    'driver' => env('CACHE_DRIVER', 'file'),

    /**
     * Cache driver configurations. Modifiy these to customize your driver
     * configuration to suit your specific environment.
     */
    'drivers' => [
        /**
         * File driver configuration.
         */
        'file' => function () {
            $this->setCacheDir(env('CACHE_PATH', cache_path()));
        },

        /**
         * Memcached driver configuration.
         */
        'memcached' => function ($memcached) {
            $memcached->addServer(
                env('MEMCACHED_HOST', 'localhost'),
                env('MEMCACHED_PORT', 11211)
            );
        },

        /**
         * Redis driver configuration.
         */
        'redis' => function ($redis) {
            $redis->pconnect(
                env('REDIS_HOST', 'localhost'),
                env('REDIS_PORT', 6379)
            );
        },

        /**
         * APCu driver configuration.
         */
        'apcu' => function () {
            $this->setPrefix(env('APCU_PREFIX', 'uber_gallery'));
        }
    ],

];
