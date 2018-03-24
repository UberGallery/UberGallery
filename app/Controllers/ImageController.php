<?php

namespace App\Controllers;

use App\Image;
use Slim\Http\Request;
use Slim\Http\Response;
use Exception;

class ImageController extends Controller
{
    /**
     * Handle an incoming Image request and return a response.
     *
     * @param \Slim\Http\Request  $request  Incoming request object
     * @param \Slim\Http\Response $response Outgoing response object
     * @param array               $args     the array of request arguments
     *
     * @return \Slim\Http\Response
     */
    public function __invoke(Request $request, Response $response, array $args)
    {
        try {
            $imagePath = $this->imagePath($args['album'], $args['image']);
            $image = new Image($imagePath);
        } catch (Exception $exception) {
            return $response->withStatus(404)->write('Image not found');
        }

        return $response
            ->withHeader('Content-Type', $image->mimeType())
            ->write($image->content());
    }
}
