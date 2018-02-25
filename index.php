<?php

require __DIR__ . '/vendor/autoload.php';

$app = new \Slim\App([
    'settings' => array_merge([
        'routerCacheFile' => __DIR__ . '/cache/routes.cache.php',
        'albums' => require __DIR__ . '/config/albums.php'
    ], require __DIR__ . '/config/app.php'),
    'root' => __DIR__
]);

call_user_func(new App\Bootstrap\ServiceProvider, $app);
call_user_func(new App\Bootstrap\MiddlewareProvider, $app);

require __DIR__ . '/routes/web.php';

$app->run();
