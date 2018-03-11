<?php

namespace App\Controllers;

use App\Image;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Exception;

class ImageController extends Controller
{
    /**
     * Handle an incoming Image request and return a response.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request  Incoming request object
     * @param \Psr\Http\Message\ResponseInterface      $response Outgoing response object
     * @param array                                   $args     the array of request arguments
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        try {
            $imagePath = $this->imagePath($args['album'], $args['image']);
            $image = Image::createFromCache($this->container, $imagePath);
        } catch (Exception $exception) {
            return $response->withStatus(404)->write('Image not found');
        }

        return $response
            ->withHeader('Content-Type', $image->mimeType)
            ->write($image->contents);
    }
}
