<?php

namespace App\Services;

use Slim\Views\Twig;
use Twig_SimpleFunction;
use Closure;

class ViewService extends Service
{
    /**
     * Register the view service.
     *
     * @return void
     */
    public function register()
    {
        $this->bind('view', function ($container) {
            $themePath = realpath($container->root . "/themes/{$container->config->get('gallery.theme')}");

            $view = new Twig($themePath, [
                // 'cache' => $container->root . '/cache/views'
            ]);

            // Instantiate and add Slim specific extension
            // $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
            // $view->addExtension(new \Slim\Views\TwigExtension($container['router'], $basePath));

            return $view;
        });
    }

    /**
     * Boot the view service.
     *
     * @return void
     */
    public function boot()
    {
        $this->addFunction('themePath', function ($path) {
            $themeName = $this->container->config->get('gallery.theme');

            return "/themes/{$themeName}/{$path}";
        });

        $this->addFunction('imagePath', function ($image) {
            $album = $this->albumSlug();

            return "/{$album}/{$image}";
        });

        $this->addFunction('thumbnailPath', function ($image) {
            $album = $this->albumSlug();

            return "/{$album}/thumbnail/{$image}";
        });
    }

    /**
     * Add a new view function.
     *
     * @param string  $name     The function name
     * @param Closure $function A Closure function
     */
    protected function addFunction($name, Closure $function)
    {
        $this->container->view->getEnvironment()->addFunction(
            new Twig_SimpleFunction($name, $function)
        );
    }

    /**
     * Return the album slug.
     *
     * @return string The album slug
     */
    protected function albumSlug()
    {
        $path = $this->container->request->getUri()->getPath();

        preg_match_all('/\/([a-zA-Z0-9]+)/', $path, $matches);

        return $matches[1][0];
    }
}
