<?php

namespace App\Middleware;

use Slim\Http\Request;
use Slim\Http\Response;

abstract class Middleware
{
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
