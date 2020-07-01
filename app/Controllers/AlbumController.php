<?php

namespace App\Controllers;

use App\Album;
use App\Exceptions\FileNotFoundException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class AlbumController extends Controller
{
    /** Handle an incoming Album request and return a response. */
    public function __invoke(Request $request, Response $response, string $album, int $page = 1): Response
    {
        try {
            $album = new Album($album, $this->container->get('albums')[$album]);
        } catch (FileNotFoundException $exception) {
            return $response->withStatus(404, 'Album not found');
        }

        $album->sort(
            $album->config('sort')['method'] ?? 'name',
            $album->config('sort')['reverse'] ?? false
        );

        return $this->view->render($response, 'album.twig', [
            'slug' => $album->slug(),
            'title' => $album->title(),
            'album' => $album,
            'images' => $album->images($page)->all(),
        ]);
    }
}
