<?php

/**
 * This is where we define our application routes.
 */

$app->get('/', App\Controllers\GalleryController::class)->setName('index');

$app->group('/{album}', function () {
    $this->get('/', App\Controllers\AlbumController::class)->setName('album');
    // $this->get('/{page:[0-9]+}', App\Controllers\AlbumController::class)->setName('album');
    $this->get('/{image}', App\Controllers\ImageController::class)->setName('image');
    $this->get('/thumbnail/{image}', App\Controllers\ThumbnailController::class)->setName('thumbnail');
});
