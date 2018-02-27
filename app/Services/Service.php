<?php

namespace App\Services;

use Psr\Container\ContainerInterface;

abstract class Service
{
    /** @var \Psr\Container\ContainerInterface $container The Slim application container */
    protected $container;

    /**
     * App\Services\Service constructor. Runs on object creation.
     *
     * @param \Psr\Container\ContainerInterface $container The Slim application container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Register a service.
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
        $this->container[$name] = $closure;
    }
}
