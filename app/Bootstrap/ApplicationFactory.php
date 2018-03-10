<?php

namespace App\Bootstrap;

use Slim\App;

class ApplicationFactory
{
    /** @var type Array of application providers */
    protected $providers = [
        \App\Providers\ServiceProvider::class,
        \App\Providers\MiddlewareProvider::class,
        \App\Providers\RoutesProvider::class
    ];

    /**
     * Return the bootstrapped application.
     *
     * @param string $appRoot Path to application root directory
     *
     * @return \App\Slim The Slim application
     */
    public function __invoke($appRoot)
    {
        $app = new App([
            'settings' => array_merge([
                'routerCacheFile' => "{$appRoot}/cache/routes.cache.php",
                'albums' => (include "{$appRoot}/config/albums.php") ?: [],
                'cache' => (include "{$appRoot}/config/cache.php") ?: []
            ], (include "{$appRoot}/config/app.php") ?: []),
            'root' => $appRoot
        ]);

        $this->registerProviders($app);

        return $app;
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
