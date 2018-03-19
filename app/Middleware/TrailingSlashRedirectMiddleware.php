<?php

namespace App\Middleware;

use Slim\Http\Request;
use Slim\Http\Response;

class TrailingSlashRedirectMiddleware extends Middleware
{
    /**
     * Redirect requests to a non-file path without a trailing slash.
     *
     * @param \Slim\Http\Request  $request  Incoming request object
     * @param \Slim\Http\Response $response Outgoing response object
     * @param callable            $next     The next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(Request $request, Response $response, callable $next)
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
