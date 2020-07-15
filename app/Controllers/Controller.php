<?php

namespace App\Controllers;

use DI\Container;
use Slim\Views\Twig;

abstract class Controller
{
    protected Container $container;
    protected Twig $view;

    /** Create a new Controller object. */
    public function __construct(Container $container, Twig $view)
    {
        $this->container = $container;
        $this->view = $view;
    }
}
