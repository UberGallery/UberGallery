<?php

namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use Tightenco\Collect\Support\Collection;

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
        $albums = new Collection($this->config('albums', []));

        $albums = $albums->map(function ($album, $slug) {
            return array_merge($album, ['slug' => $slug]);
        });

        return $this->view('index', ['albums' => $albums]);
    }
}
