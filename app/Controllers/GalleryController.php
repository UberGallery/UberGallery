<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class GalleryController extends Controller
{
    /**
     * Handle an incoming Gallery request and return a response.
     *
     * @param Psr\Http\Message\ServerRequestInterface $request  Incoming request object
     * @param Psr\Http\Message\ResponseInterface      $response Outgoing response object
     * @param array                                   $args     the array of request arguments
     *
     * @return Psr\Http\Message\ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $albums = $this->config('albums', []);

        $albums = array_map(function ($album, $slug) {
            return array_merge($album, ['slug' => $slug]);
        }, $albums, array_keys($albums));

        return $response->write($this->view('index', ['albums' => $albums]));
    }
}
