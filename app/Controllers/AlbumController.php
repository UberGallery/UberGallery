<?php

namespace App\Controllers;

use App\Album;
use App\Image;
use App\Exceptions\FileNotFoundException;
use App\Exceptions\InvalidImageException;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use DirectoryIterator;

class AlbumController extends Controller
{
    /**
     * Handle an incoming Album request and return a response.
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
            $albumPath = $this->albumPath($args['album']);
        } catch (FileNotFoundException $exception) {
            return $response->withStatus(404)->write('Album not found');
        }

        $width = $this->config("albums.{$args['album']}.thumbnails.width", 480);
        $height = $this->config("albums.{$args['album']}.thumbnails.height", 480);

        $album = new Album([], $this->albumTitle($args['album']));

        foreach (new DirectoryIterator($albumPath) as $file) {
            if ($file->isDot()) {
                continue;
            }

            try {
                $album->add(new Image($file->getPathname(), $width, $height));
            } catch (InvalidImageException $exception) {
                // Don't worry about it
            }
        }

        // QUESTION: Cache the album?

        return $response->write($this->view('album', [
            'container' => $this->container,
            'album' => $album
        ]));
    }
}
