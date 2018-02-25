<?php

namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class TrailingSlashRedirectMiddleware extends Middleware
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        $uri = $request->getUri();
        $path = $uri->getPath();

        if (strlen($path) > 1) {
            if (substr($path, -1) !== '/' && ! pathinfo($path, PATHINFO_EXTENSION)) {
                $response->withRedirect($path .= '/', 301);
            }
        }

        return $next($request, $response);
    }
}
