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
     * Set your desired theme. Must be the name of the theme folder as it is in
     * the themes directory.
     *
     * Default value: 'redux'
     */
    'theme' => 'redux',

    /**
     * Improve performance by defining a router cache file.
     *
     * Suggested value: __DIR__ . '/../cache/routes.cache.php'
     *
     * Default value: false
     */
    'routerCacheFile' => false,

    /**
     * Show error details on the page by setting this value to 'true'.
     *
     * Default value: false
     */
    'displayErrorDetails' => true,

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
