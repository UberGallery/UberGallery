<?php

namespace App\Controllers;

use App\Image;
use Slim\Http\Request;
use Slim\Http\Response;
use Exception;

class ThumbnailController extends Controller
{
    /**
     * Handle an incoming Thumbnail request and return a response.
     *
     * @param use \Slim\Http\Request  $request  Incoming request object
     * @param use \Slim\Http\Response $response Outgoing response object
     * @param array                   $args     the array of request arguments
     *
     * @return \Slim\Http\Response
     */
    public function __invoke(Request $request, Response $response, array $args)
    {
        $width = $this->config("albums.{$args['album']}.thumbnails.width", 480);
        $height = $this->config("albums.{$args['album']}.thumbnails.height", 480);

        try {
            $imagePath = $this->imagePath($args['album'], $args['image']);
            $image = new Image($imagePath);
        } catch (Exception $exception) {
            return $response->withStatus(404)->write('Thumbnail not found');
        }

        return $response
            ->withHeader('Content-Type', $image->mimeType())
            ->write($image->thumbnail($width, $height));
    }
}
