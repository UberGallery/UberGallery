<?php

namespace App\Bootstrap;

use Slim\App;

class ServiceProvider
{
    /** @var array Array of application services */
    protected $services = [
        \App\Services\ConfigService::class,
        \App\Services\CacheService::class
    ];

    /**
     * Register application services.
     *
     * @param Slim\App $app The Slim application
     *
     * @return void
     */
    public function __invoke(App $app)
    {
        $services = array_merge(
            $this->services,
            $app->getContainer()->settings->services ?? []
        );

        array_walk($services, function ($service) use ($app) {
            $service = new $service($app->getContainer());
            $service->register();
        });
    }
}
