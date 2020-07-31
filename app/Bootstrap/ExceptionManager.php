<?php

namespace App\Bootstrap;

use App\Config;
use App\Exceptions\ErrorHandler;
use Slim\App;

class ExceptionManager
{
    protected App $app;
    protected Config $config;

    /** Create a new ExceptionManager object. */
    public function __construct(App $app, Config $config)
    {
        $this->app = $app;
        $this->config = $config;
    }

    /** Set up and configure exception handling. */
    public function __invoke(): void
    {
        if ($this->config->get('debug')) {
            return;
        }

        $errorMiddleware = $this->app->addErrorMiddleware(true, true, true);
        $errorMiddleware->setDefaultErrorHandler(ErrorHandler::class);
    }
}
