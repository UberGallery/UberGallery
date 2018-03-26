<?php

namespace App\Services;

use Slim\Container;

abstract class Service
{
    /** @var \Slim\Container $container The Slim application container */
    protected $container;

    /**
     * App\Services\Service constructor. Runs on object creation.
     *
     * @param \Slim\Container $container The Slim application container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Register a service.
     *
     * @codeCoverageIgnore
     *
     * @return void
     */
    public function register()
    {
        // ...
    }

    /**
     * Boot a service.
     *
     * @codeCoverageIgnore
     *
     * @return void
     */
    public function boot()
    {
        // ...
    }

    /**
     * Bind a service to the container.
     *
     * @param string   $name    a unique name for the service
     * @param callable $closure a closure that returns the service
     *
     * @return void
     */
    protected function bind($name, callable $closure)
    {
        $this->container[$name] = $closure($this->container);
    }
}
