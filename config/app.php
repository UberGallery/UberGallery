<?php

return [

    // Set your theme here
    'theme' => 'redux',

    // Application cache settings
    'cache' => [
        'enabled' => false,
        'driver' => 'file',
        'config' => function () {
            return ['dir' => __DIR__ . '/../cache']; // TODO: Improvoe this
        }
    ],

    // Custom services
    'services' => [
        // App\Services\SomeService::class,
    ],

    // Custom middleware
    'middleware' => [
        // App\Middleware\SomeMiddleware::class,
    ],

];
