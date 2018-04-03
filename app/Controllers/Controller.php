<?php

namespace App\Controllers;

use App\Exceptions\FileNotFoundException;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

abstract class Controller
{
    /** @var \Slim\Container The Slim application container */
    protected $container;

    /**
     * App\Controllers\Controller constructor. Runs on object creation.
     *
     * @param \Slim\Container $container The Slim application container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Handle an incoming request and return a response.
     *
     * @param \Slim\Http\Request $request  Incoming request object
     * @param \Slim\Http\Request $response Outgoing response object
     * @param array              $args     the array of request arguments
     *
     * @return \Slim\Http\Response
     */
    abstract public function __invoke(Request $request, Response $response, array $args);

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
            'gallery_title' => $this->config('gallery.title', 'Uber Gallery'),
            'themePath' => function ($path) {
                return "/themes/{$this->config('gallery.theme')}/{$path}";
            },
            'imagePath' => function ($image) use ($data) {
                return "/{$data['slug']}/{$image}";
            },
            'thumbnailPath' => function ($thumbnail) use ($data) {
                return "/{$data['slug']}/thumbnail/{$thumbnail}";
            }
        ], $data));
    }

    /**
     * Convenience method for fetching application configuration items from the container.
     *
     * @param string $key     Unique config item key
     * @param mixed  $default Value to be returned if the config item doesn't exist
     *
     * @return mixed The config item or default value
     */
    protected function config($key = null, $default = null)
    {
        if (is_null($key)) {
            return $this->container->config;
        }

        return $this->container->config->get($key, $default);
    }

    /**
     * Return the directory path for a given album.
     *
     * @param string $album Album name
     *
     * @return string Full path to the album directory
     */
    protected function albumPath($album)
    {
        return $this->config(
            "albums.{$album}.path",
            realpath(base_path("albums/{$album}"))
        );
    }

    /**
     * Return the directory path for a given album and image.
     *
     * @param string $album Album name
     * @param string $album Image name
     *
     * @return string Full path to the image
     */
    protected function imagePath($album, $image)
    {
        return realpath("{$this->albumPath($album)}/{$image}");
    }
}
