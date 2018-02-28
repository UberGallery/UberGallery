<?php

/**
 * This file contains general application configuration settings.
 */

return [

    /**
     * Give your gallery a descriptive title.
     *
     * Default value: 'Uber Gallery'
     */
    'title' => 'Uber Gallery',

    /**
     * Set your desired theme. Must be the name aof the theme folder as it is in
     * the themes directory.
     *
     * Default value: 'redux'
     */
    'theme' => 'redux',

    /**
     * You can speed up your application by enabling caching. By default the
     * the 'file' cache is enabled but you can use any of several different
     * caching backends including 'file', 'memcached' or 'redis'
     */
    'cache' => [
        'enabled' => false,
        'driver' => 'file',
        'config' => function () {
            return ['dir' => __DIR__ . '/../cache']; // TODO: Improvoe this
        }
    ],

    /**
     * Register custom application services.
     *
     * Default value: []
     */
    'services' => [
        // App\Services\SomeService::class,
    ],

    /**
     * Register custom application middleware.
     *
     * Default value: []
     */
    'middleware' => [
        // App\Middleware\SomeMiddleware::class,
    ],

];
