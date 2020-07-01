<?php

namespace App\Controllers;

use DI\Container;
use Slim\Views\Twig;

abstract class Controller
{
    protected Container $container;
    protected Twig $view;

    /** Create a new Controller object. */
    public function __construct(Container $container, Twig $view)
    {
        $this->container = $container;
        $this->view = $view;
    }

    /** Convenience method for fetching configuration items from the container. */
    protected function config($key = null, $default = null)
    {
        return $this->container->get($key) ?? $default;
    }

    /** Get the directory path for an album. */
    protected function albumPath($album): string
    {
        return sprintf('%s/%s', $this->container->get('albums_path'), $album);
    }

    /** Get the path to a given album and image. */
    protected function imagePath(string $album, string $image): string
    {
        return realpath("{$this->albumPath($album)}/{$image}");
    }
}
