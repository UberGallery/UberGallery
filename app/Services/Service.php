<?php

namespace App\Services;

use Psr\Container\ContainerInterface;

abstract class Service
{
    /**
     * App\Services\Service constructor. Runs on object creation.
     *
     * @param Psr\Container\ContainerInterface $container The Slim application container
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
    abstract public function register();

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
