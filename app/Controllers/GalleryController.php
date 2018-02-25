<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class GalleryController extends Controller
{
    /**
     * App\Controllers\GalleryController magic invoke method, runs when accessed
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
        // TODO: List available albums?
        return $response->write($this->view('index'));
    }
}
