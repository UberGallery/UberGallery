<?php

/**
 * This is where we define our application routes.
 */

$app->get('/', App\Controllers\GalleryController::class)->setName('index');

$app->group('/{album:[\w\d-]+}', function ($app) {
    $app->get('/[{page:[0-9]+}]', App\Controllers\AlbumController::class)
        ->setName('album');

    $app->get('/{image}', App\Controllers\ImageController::class)
        ->add(new App\Middleware\CacheMiddleware($app->getContainer()))
        ->setName('image');

    $app->get('/thumbnail/{image}', App\Controllers\ThumbnailController::class)
        ->add(new App\Middleware\CacheMiddleware($app->getContainer()))
        ->setName('thumbnail');
});

$app->get('/asset', App\Controllers\AssetController::class)
    ->add(new App\Middleware\CacheMiddleware($app->getContainer()))
    ->setName('asset');
