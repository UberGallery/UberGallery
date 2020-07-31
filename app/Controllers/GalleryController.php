<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class GalleryController extends Controller
{
    /** Handle an incoming Gallery request and return a response. */
    public function __invoke(Response $response, int $page = 1): ResponseInterface
    {
        if (! is_readable($this->config->get('gallery_path'))) {
            return $response->withStatus(404, 'Gallery not found');
        }

        $images = Finder::create()->in($this->config->get('gallery_path'))
            ->name(['*.gif', '*.jpeg', '*.jpg', '*.png'])
            ->filter(fn (SplFileInfo $file) => $this->isImage($file));

        // TODO: Pagination

        return $this->view->render($response, 'index.twig', [
            'title' => $this->config->get('gallery_title'),
            'images' => $images,
        ]);
    }

    /** Determine if the file is an image based on it's mime type. */
    protected function isImage(SplFileInfo $file): bool
    {
        return in_array(mime_content_type($file->getPathname()), [
            'image/gif', 'image/png', 'image/jpeg', 'image/jpg'
        ]);
    }
}
