<?php

namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class TrailingSlashRedirectMiddleware extends Middleware
{
    /**
     * Redirect requests to a non-file path without a trailing slash.
     *
     * @param Psr\Http\Message\ServerRequestInterface $request  Incoming request object
     * @param Psr\Http\Message\ResponseInterface      $response Outgoing response object
     * @param callable                                $next     The next middleware
     *
     * @return Psr\Http\Message\ResponseInterface
     */
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
