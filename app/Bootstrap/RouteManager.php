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
        $this->app->group('/', function (RouteCollectorProxy $group) {
            $group->get('[{page:[0-9]+}]', Controllers\GalleryController::class)->setName('gallery');

            $group->get('image/{image}', Controllers\ImageController::class)
                ->add(Middleware\CacheMiddleware::class)->setName('image');

            $group->get('thumbnail/{image}', Controllers\ThumbnailController::class)
                ->add(Middleware\CacheMiddleware::class)->setName('thumbnail');
        });
    }
}
