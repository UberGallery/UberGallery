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
     * A driver-specific configuration closure.
     *
     * Default value: function () {
     *     return ['dir' => __DIR__ . '/../cache'];
     * }
     */
    'config' => function () {
        return ['dir' => __DIR__ . '/../cache']; // TODO: Improvoe this
    }

];
