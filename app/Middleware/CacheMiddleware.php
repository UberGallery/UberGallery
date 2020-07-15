<?php

namespace App\Middleware;

use DateTimeImmutable;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Response;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class CacheMiddleware
{
    protected CacheInterface $cache;

    /** Create a new CacheMiddleware object. */
    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    /** Retrieve a response from the cache if present. */
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        [$status, $headers, $body] = $this->cache->get(
            sha1($request->getUri()->getPath()),
            function (ItemInterface $item) use ($request, $handler) {
                $response = $handler->handle($request);

                if (! $this->responseIsSuccessful($response)) {
                    $item->expiresAt(new DateTimeImmutable);
                }

                return [
                    $response->getStatusCode(),
                    $response->getHeaders(),
                    (string) $response->getBody()
                ];
            }
        );

        return new Response($status, new Headers($headers), (new StreamFactory)->createStream($body));
    }

    /** Determine if a response is a successful (2XX) response. */
    protected function responseIsSuccessful(Response $response): bool
    {
        return in_array($response->getStatusCode(), [
            200, 201, 202, 203, 204, 205, 206, 207, 208, 226
        ]);
    }
}
