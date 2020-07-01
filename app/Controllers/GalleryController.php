<?php

namespace App\Controllers;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Tightenco\Collect\Support\Collection;

class GalleryController extends Controller
{
    /** Handle an incoming Gallery request and return a response. */
    public function __invoke(Request $request, Response $response): Response
    {
        $albums = new Collection($this->config('albums', []));

        $albums = $albums->map(function ($album, $slug) {
            return array_merge($album, ['slug' => $slug]);
        });

        return $this->view->render($response, 'index.twig', ['albums' => $albums]);
    }
}
