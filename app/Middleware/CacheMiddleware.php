<?php

namespace App\Middleware;

use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\Headers;

class CacheMiddleware extends Middleware
{
    /**
     * Cache responses to speed up page loading.
     *
     * @param \Slim\Http\Request  $request  Incoming request object
     * @param \Slim\Http\Response $response Outgoing response object
     * @param callable            $next     The next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(Request $request, Response $response, callable $next)
    {
        if (! $this->container->config->get('cache.enabled', false)) {
            return $next($request, $response);
        }

        $key = $request->getUri()->getPath();

        if ($this->container->cache->has($key)) {
            [$response, $body] = $this->container->cache->get($key);

            $headers = new Headers;
            foreach ($response->getHeaders() as $header => $value) {
                $headers->set($header, $value);
            }

            return (
                new Response($response->getStatusCode(), $headers)
            )->write($body);
        }

        $response = $next($request, $response);

        if ($response->isOk()) {
            $this->container->cache->forever($key, [$response, (string) $response->getBody()]);
        }

        return $response;
    }
}
