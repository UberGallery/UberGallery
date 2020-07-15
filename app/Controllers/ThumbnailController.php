<?php

namespace App\Controllers;

use App\Image;
use App\Thumbnail;
use Exception;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class ThumbnailController extends Controller
{
    /** Handle an incoming Thumbnail request and return a response. */
    public function __invoke(Response $response, string $image): Response
    {
        try {
            $thumbnail = new Thumbnail(
                new Image(sprintf('%s/%s', $this->container->get('gallery_path'), $image)),
                $this->container->get('thumbnail_width'),
                $this->container->get('thumbnail_height'),
                $this->container->get('thumbnail_quality')
            );
        } catch (Exception $exception) {
            return $response->withStatus(404, 'Thumbnail not found');
        }

        $response->getBody()->write($thumbnail->content());

        return $response->withHeader('Content-Type', $thumbnail->mimeType());
    }
}
