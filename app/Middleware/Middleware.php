<?php

namespace App\Middleware;

use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

abstract class Middleware
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
     * Manipulate the Request and Response objects.
     *
     * @param \Slim\Http\Request  $request  Incoming request object
     * @param \Slim\Http\Response $response Outgoing response object
     * @param callable            $next     The next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    abstract public function __invoke(Request $request, Response $response, callable $next);
}
