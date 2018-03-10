<?php

namespace App\Controllers;

use Mustache_Engine;
use Mustache_Loader_FilesystemLoader;
use App\Exceptions\FileNotFoundException;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

abstract class Controller
{
    /** @var ContainerInterface An implementation of ContainerInterface */
    protected $container;

    /**
     * App\Controllers\Controller constructor. Runs on object creation.
     *
     * @param \Psr\Container\ContainerInterface $container The Slim application container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Handle an incoming request and return a response.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request  Incoming request object
     * @param \Psr\Http\Message\ResponseInterface      $response Outgoing response object
     * @param array                                   $args     the array of request arguments
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    abstract public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args);

    /**
     * Render a view with some provided data.
     *
     * @param string $view The view name to be rendered
     * @param array  $data An array of data passed to the view
     *
     * @return string The rendered view
     */
    protected function view($view, $data = [])
    {
        return $this->container->mustache->render($view, array_merge([
            'gallery_title' => $this->config('title', 'Uber Gallery'),
            'themePath' => function ($path) {
                return "/themes/{$this->config('theme')}/{$path}";
            }
        ], $data));
    }

    /**
     * Convinience method for fetching application configuration items from the container.
     *
     * @param string $key     Unique config item key
     * @param mixed  $default Value to be returned if the config item doesn't exist
     *
     * @return mixed The config item or default value
     */
    public function config($key, $default = null)
    {
        return $this->container->config->get($key, $default);
    }

    /**
     * Return the directory path to the enabled theme.
     *
     * @param string $path An optional sub-path to apend to the theme path
     *
     * @return string Full path to the theme
     */
    protected function themePath($path = null)
    {
        $themePath = realpath($this->container->root . "/themes/{$this->config('theme')}/{$path}");

        if (! $themePath) {
            throw new FileNotFoundException("Theme path not found at $themePath");
        }

        return $themePath;
    }

    /**
     * Return the directory path for a given album.
     *
     * @param string $album Album name
     *
     * @throws FileNotFoundException
     *
     * @return string Full path to the album directory
     */
    protected function albumPath($album)
    {
        $albumPath = $this->config(
            "albums.{$album}.path",
            realpath($this->container->root . "/albums/{$album}")
        );

        if (! $albumPath) {
            throw new FileNotFoundException("Album not found at {$albumPath}");
        }

        return $albumPath;
    }

    /**
     * Retrieve the album title from the config or construct it from the
     * provided slug.
     *
     * @param string $album Album slug
     *
     * @return string The album title
     */
    protected function albumTitle($album)
    {
        return $this->config(
            "albums.{$album}.title",
            ucwords(str_replace('_', ' ', $album)) . ' Album'
        );
    }

    /**
     * Return the directory path for a given album and image.
     *
     * @param string $album Album name
     * @param string $album Image name
     *
     * @throws FileNotFoundException
     *
     * @return string Full path to the image
     */
    protected function imagePath($album, $image)
    {
        $imagePath = realpath("{$this->albumPath($album)}/{$image}");

        if (! $imagePath) {
            throw new FileNotFoundException("Image not found at $imagePath");
        }

        return $imagePath;
    }
}
