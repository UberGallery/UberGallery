<?php

require __DIR__ . '/vendor/autoload.php';

$app = new \Slim\App([
    'settings' => array_merge([
        'albums' => (include __DIR__ . '/config/albums.php') ?: [],
        'cache' => (include __DIR__ . '/config/cache.php') ?: []
    ], (include __DIR__ . '/config/app.php') ?: []),
    'root' => __DIR__
]);

call_user_func(new \App\Bootstrap\ApplicationManager, $app);

$app->run();
