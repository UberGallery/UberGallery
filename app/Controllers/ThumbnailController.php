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
                new Image(sprintf('%s/%s', $this->config->get('gallery_path'), $image)),
                $this->config->get('thumbnail_width'),
                $this->config->get('thumbnail_height'),
                $this->config->get('thumbnail_quality')
            );
        } catch (Exception $exception) {
            return $response->withStatus(404, 'Thumbnail not found');
        }

        $response->getBody()->write($thumbnail->content());

        return $response->withHeader('Content-Type', $thumbnail->mimeType());
    }
}
