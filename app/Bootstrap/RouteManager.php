<?php

namespace App\Bootstrap;

use App\Controllers;
use App\Middleware;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

class RouteManager
{
    protected App $app;

    /** Create a new RouteManager object. */
    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /** Register the application routes. */
    public function __invoke(): void
    {
        $this->app->get('/', Controllers\GalleryController::class)->setName('index');

        // TODO: Cache image and thumbnail responses via middleware
        $this->app->group('/{album:[\w\d-]+}', function (RouteCollectorProxy $group): void {
            $group->get('/[{page:[0-9]+}]', Controllers\AlbumController::class)->setName('album');
            $group->get('/{image}', Controllers\ImageController::class)->setName('image');
            $group->get('/thumbnail/{image}', Controllers\ThumbnailController::class)->setName('thumbnail');
        });
    }
}
