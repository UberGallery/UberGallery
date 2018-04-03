<?php

require __DIR__ . '/vendor/autoload.php';

$app = new \Slim\App([
    'settings' => include __DIR__ . '/config/app.php' ?: [],
    'config_path' => __DIR__ . '/config/',
    'root' => __DIR__
]);

call_user_func(new \App\Bootstrap\ApplicationManager, $app);

$app->run();
