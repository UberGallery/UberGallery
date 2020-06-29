<?php

define('UBER_GALLERY_START', microtime(true));

require __DIR__ . '/../vendor/autoload.php';

try {
    (new \Dotenv\Dotenv(__DIR__))->load();
} catch (\Dotenv\Exception\InvalidPathException $exception) {
    // Ignore it
}

$app = new \Slim\App([
    'settings' => include __DIR__ . '/../config/app.php' ?: [],
    'config_path' => __DIR__ . '/../config/',
    'env_path' => __DIR__ . '/..',
    'root' => __DIR__ . '/..'
]);

call_user_func(new \App\Bootstrap\ApplicationManager, $app);

$app->run();
