<?php

require __DIR__ . '/vendor/autoload.php';

$app = new \Slim\App([
    'settings' => array_merge([
        'routerCacheFile' => __DIR__ . '/cache/routes.cache.php',
        'albums' => require __DIR__ . '/config/albums.php',
        'cache' => require __DIR__ . '/config/cache.php'
    ], require __DIR__ . '/config/app.php'),
    'root' => __DIR__
]);

call_user_func(new App\Bootstrap\ServiceProvider, $app);
call_user_func(new App\Bootstrap\MiddlewareProvider, $app);
call_user_func(new App\Bootstrap\RoutesProvider, $app);

$app->run();
