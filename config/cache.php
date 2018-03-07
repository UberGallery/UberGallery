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
    'enabled' => false,

    /**
     * The caching driver to use.
     *
     * Available options: 'file', 'memcached', 'redis', 'apcu'
     *
     * Default value: 'file'
     */
    'driver' => 'file',

    /**
     * Cache driver configurations. Modified these to customize your driver
     * configuration to suit your specific environment.
     */
    'drivers' => [
        /**
         * File driver configuration
         */
        'file' => function () {
            return [
                'dir' => realpath(__DIR__ . '/../cache') // TODO: Improvoe this
            ];
        },

        /**
         * Memcached driver configuration
         */
        'memcached' => function ($memcached) {
            $memcached->addServer('localhost', 11211);

            return $memcached; // Must return the $memcached object
        },

        /**
         * Redis driver configuration
         */
        'redis' => function ($redis) {
            $redis->pconnect('localhost', 6379);

            return $redis; // Must return the $redis object
        },

        /**
         * APCu driver configuration
         */
        'apcu' => function () {
            return [
                // 'prefix' => 'uber_gallery' // Optional prefix
            ];
        }
    ]

];
