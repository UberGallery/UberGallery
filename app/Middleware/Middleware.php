<?php

namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

abstract class Middleware
{
    abstract public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next);
}
