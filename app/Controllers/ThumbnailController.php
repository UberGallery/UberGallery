<?php

namespace App\Controllers;

use App\Image;
use App\Traits\Cacheable;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Exception;

class ThumbnailController extends Controller
{
    use Cacheable;

    /**
     * Handle an incoming Thumbnail request and return a response.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request  Incoming request object
     * @param \Psr\Http\Message\ResponseInterface      $response Outgoing response object
     * @param array                                   $args     the array of request arguments
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $width = $this->config("albums.{$args['album']}.thumbnails.width", 480);
        $height = $this->config("albums.{$args['album']}.thumbnails.height", 480);

        try {
            $imagePath = $this->imagePath($args['album'], $args['image']);
            $image = Image::createFromCache($this->container, $imagePath, $width, $height);
        } catch (Exception $exception) {
            return $response->withStatus(404)->write('Thumbnail not found');
        }

        return $response
            ->withHeader('Content-Type', $image->mimeType)
            ->write($image->contents);
    }
}
