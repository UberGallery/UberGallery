<?php

namespace App\Controllers;

use App\Image;
use App\Exceptions\FileNotFoundException;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class ImageController extends Controller
{
    /**
     * App\Controllers\ImageController magic invoke method, runs when accessed
     * as a callable.
     *
     * @param Psr\Http\Message\ServerRequestInterface $request  The incoming request object
     * @param Psr\Http\Message\ResponseInterface      $response The outgoing response object
     * @param array                                   $args     the array of request arguments
     *
     * @return Psr\Http\Message\ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        try {
            $imagePath = $this->imagePath($args['album'], $args['image']);
        } catch (FileNotFoundException $exception) {
            return $response->withStatus(404)->write('Image not found');
        }

        $image = Image::createFromCache($this->container, $imagePath);

        return $response
            ->withHeader('Content-Type', $image->mimeType)
            ->write($image->contents);
    }
}
