<?php

namespace App\Controllers;

use Mustache_Engine;
use Mustache_Loader_FilesystemLoader;
use App\Exceptions\FileNotFoundException;
use Psr\Container\ContainerInterface;

abstract class Controller
{
    /** @var ContainerInterface An implementation of ContainerInterface */
    protected $container;

    /** @var string Path to the directory of the currently enabled theme */
    protected $themePath;

    /**
     * App\Controllers\Controller constructor. Runs on object creation.
     *
     * @param Psr\Container\ContainerInterface $container The Slim application container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

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
        $mustache = new Mustache_Engine([
            'loader' => new Mustache_Loader_FilesystemLoader($this->themePath()),
            // 'partials_loader' => new Mustache_Loader_FilesystemLoader("$this->themePath/partials"),
            // 'escape' => function($value) {
            //     return htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
            // }
        ]);

        $data = array_merge([
            'gallery_title' => 'Static Gallery Title',
            'album_title' => 'Static Album Title',
            'themePath' => function ($path) {
                return "/themes/{$this->config('theme')}/{$path}";
            }
        ], $data);

        // TODO: Create some logic to allow template dot notation (i.e. theme.index, theme.album, etc.)
        return $mustache->loadTemplate($view)->render($data);
    }

    /**
     * Convinience method for fetching config items.
     *
     * @param string $key     A unique config item key
     * @param mixed  $default A value to be returned if the config item doesn't exist
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
     * @return string The them path
     */
    protected function themePath()
    {
        return $this->container->root . "/themes/{$this->config('theme')}";
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
        $albumPath = $this->container->root . "/albums/{$album}";

        if (! file_exists($albumPath)) {
            throw new FileNotFoundException("Album not found at {$albumPath}");
        }

        return $albumPath;
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
        $imagePath = "{$this->albumPath($album)}/{$image}";

        if (! file_exists($imagePath)) {
            throw new FileNotFoundException("Image not found at $imagePath");
        }

        return $imagePath;
    }
}
