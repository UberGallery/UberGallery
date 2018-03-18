<?php

namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;

class GalleryController extends Controller
{
    /**
     * Handle an incoming Gallery request and return a response.
     *
     * @param \Slim\Http\Request  $request  Incoming request object
     * @param \Slim\Http\Response $response Outgoing response object
     * @param array               $args     the array of request arguments
     *
     * @return \Slim\Http\Response
     */
    public function __invoke(Request $request, Response $response, array $args)
    {
        $albums = $this->config('albums', []);

        $albums = array_map(function ($album, $slug) {
            return array_merge($album, ['slug' => $slug]);
        }, $albums, array_keys($albums));

        return $response->write($this->view('index', ['albums' => $albums]));
    }
}
