<?php

namespace App\Providers;

use Slim\App;

class RoutesProvider extends Provider
{
    /**
     * Register application routes.
     *
     * @param \Slim\App $app The Slim application
     *
     * @return void
     */
    public function __invoke(App $app)
    {
        require $app->getContainer()->root . '/routes/web.php';
    }
}
