<?php

namespace App\Controllers;

use App\Config;
use Slim\Views\Twig;

abstract class Controller
{
    protected Config $config;
    protected Twig $view;

    /** Create a new Controller object. */
    public function __construct(Config $config, Twig $view)
    {
        $this->config = $config;
        $this->view = $view;
    }
}
