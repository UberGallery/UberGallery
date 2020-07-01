<?php

namespace App\Controllers;

use App\Album;
use App\Image;
use App\Thumbnail;
use Exception;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class ThumbnailController extends Controller
{
    /** Handle an incoming Thumbnail request and return a response. */
    public function __invoke(Request $request, Response $response, string $album, string $image): Response
    {
        $config = $this->container->get('albums')[$album];
        $width = $config['thumbnails']['width'] ?? 480;
        $height = $config['thumbnails']['height'] ?? 480;

        try {
            $album = new Album($album, $this->container->get('albums')[$album]);
            $image = Image::fromAlbumAndName($album, $image);
            $thumbnail = new Thumbnail($image, $width, $height);
        } catch (Exception $exception) {
            return $response->withStatus(404, 'Thumbnail not found');
        }

        $response->getBody()->write($thumbnail->content());

        return $response->withHeader('Content-Type', $thumbnail->mimeType());
    }
}
