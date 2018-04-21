<?php

/**
 * This file contains general application configuration settings.
 */

return [

    /**
     * Show error details on the page by setting this value to 'true'.
     *
     * Default value: false
     */
    'displayErrorDetails' => env('DISPLAY_ERRORS', false),


    /**
     * Improve performance by defining a router cache file.
     *
     * Suggested value: __DIR__ . '/../cache/routes.cache.php'
     *
     * Default value: false
     */
    'routerCacheFile' => env('ROUTER_CACHE_FILE', false),

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
