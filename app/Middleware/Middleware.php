<?php

namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

abstract class Middleware
{
    /**
     * Manipulate the Request and Response objects.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request  Incoming request object
     * @param \Psr\Http\Message\ResponseInterface      $response Outgoing response object
     * @param callable                                 $next     The next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    abstract public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next);
}
