<?php

namespace App\Controllers;

use App\Image;
use Exception;
use Slim\Psr7\Response;

class ImageController extends Controller
{
    /** Handle an incoming Image request and return a response. */
    public function __invoke(Response $response, string $image): Response
    {
        try {
            $image = new Image(sprintf('%s/%s', $this->config->get('gallery_path'), $image));
        } catch (Exception $exception) {
            return $response->withStatus(404, 'Image not found');
        }

        $response->getBody()->write($image->content());

        return $response->withHeader('Content-Type', $image->mimeType());
    }
}
