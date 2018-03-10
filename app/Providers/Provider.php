<?php

namespace App\Providers;

use Slim\App;

abstract class Provider
{
    /**
     * Initialize an application provider.
     *
     * @param \Slim\App $app The Slim application
     *
     * @return void
     */
    abstract public function __invoke(App $app);
}
