<?php

namespace App\Controllers;

use App\Album;
use App\Image;
use Exception;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class ImageController extends Controller
{
    /** Handle an incoming Image request and return a response. */
    public function __invoke(Request $request, Response $response, string $album, string $image): Response
    {
        try {
            $album = new Album($album, $this->container->get('albums')[$album]);
            $image = Image::fromAlbumAndName($album, $image);
        } catch (Exception $exception) {
            return $response->withStatus(404, 'Image not found');
        }

        $response->getBody()->write($image->content());

        return $response->withHeader('Content-Type', $image->mimeType());
    }
}
