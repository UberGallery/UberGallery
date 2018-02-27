<?php

namespace App\Bootstrap;

use Slim\App;

class RoutesProvider
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
