<?php

namespace App\Bootstrap;

use Slim\App;

class ApplicationManager
{
    /** @var type Array of application providers */
    protected $providers = [
        \App\Providers\ServiceProvider::class,
        \App\Providers\MiddlewareProvider::class,
        \App\Providers\RoutesProvider::class
    ];

    /**
     * Bootstrap the application.
     *
     * @param string $app Path to application root directory
     *
     * @return \App\Slim The Slim application
     */
    public function __invoke(App $app)
    {
        $this->registerProviders($app);
    }

    /**
     * Register the application providers.
     *
     * @param  \Slim\App $app The Slim application
     *
     * @return void
     */
    protected function registerProviders(App $app)
    {
        foreach ($this->providers as $provider) {
            call_user_func(new $provider, $app);
        }
    }
}
