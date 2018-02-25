<?php

namespace App\Bootstrap;

use Slim\App;

class MiddlewareProvider
{
    /** @var array Arayy of application middlewares */
    protected $middlewares = [
        // App\Middleware\SomeMiddleware::class,
    ];

    /**
     * Register application middlewares.
     *
     * @param Slim\App $app The Slim application
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
            $app->add(new $middleware);
        });
    }
}
