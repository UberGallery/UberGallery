<?php

namespace App\Providers;

use Slim\App;

class MiddlewareProvider extends Provider
{
    /** @var array Array of application middleware */
    protected $middlewares = [
        \App\Middleware\TrailingSlashRedirectMiddleware::class,
    ];

    /**
     * Register application middleware.
     *
     * @param \Slim\App $app The Slim application
     *
     * @return void
     */
    public function __invoke(App $app)
    {
        $middlewares = array_merge(
            $this->middlewares,
            $app->getContainer()->settings->middleware ?? []
        );

        array_walk($middlewares, function ($middleware) use ($app) {
            $app->add(new $middleware($app->getContainer()));
        });
    }
}
