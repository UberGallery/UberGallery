<?php

namespace App\Bootstrap;

use PHLAK\Config;
use PHLAK\Stash;
use FastRoute;

class Application
{
    /** @var string VERSION The Application version */
    const VERSION = '0.1.0';

    /** @var string $path Canonical Application folder path */
    protected $path;

    /** @var Config\Config $config Application config */
    protected $config;

    /** @var Stash\Interfaces\Cacheable $cache Application cache instance */
    protected $cache;

    public function __construct($path, Config\Config $config)
    {
        $this->path = realpath($path);
        $this->config = $config;
        $this->cache = Stash\Cache::make($this->config->cache->driver, $this->config->get('cache.config'));
    }

    /**
     * [__get description]
     *
     * @param  [type] $property [description]
     *
     * @return [type] [description]
     */
    public function __get($property)
    {
        return $this->{$property};
    }

    /**
     * [view description]
     *
     * @return [type] [description]
     */
    public function view()
    {
        $dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $route) {
            require "{$this->rootDir()}/routes/web.php";
        });

        @list($uri, $queryString) = explode('?', $_SERVER['REQUEST_URI'], 2);

        list($info, $action, $params) = $dispatcher->dispatch($_SERVER['REQUEST_METHOD'], rawurldecode($uri));

        if ($info !== FastRoute\Dispatcher::FOUND) {
            var_dump([$info, $action, $params]); die();
             // TODO: Return a real 404 response
            throw new \Exception('Error 404, page not found');
        }

        list($class, $method) = explode('@', $action, 2);
        $classPath = 'App\\Controllers\\' . $class;

        return call_user_func_array([new $classPath($this), $method], $params);
    }

    /**
     * [rootDir description]
     *
     * @return [type] [description]
     */
    public function rootDir()
    {
        return realpath("{$this->path}/..");
    }

    /**
     * [albumDir description]
     *
     * @param  [type] $album [description]
     *
     * @return [type] [description]
     */
    public function albumsDir()
    {
        return "{$this->rootDir()}/albums/";
    }

    /**
     * [albumDir description]
     *
     * @param  [type] $album [description]
     *
     * @return [type] [description]
     */
    public function albumPath($album)
    {
        return "{$this->albumsDir()}/{$album}";
    }

    /**
    * [publicDir description]
    *
    * @return [type] [description]
    */
    public function publicDir()
    {
        return "{$this->rootDir()}/public";
    }

    /**
     * [themesDir description]
     *
     * @return [type] [description]
     */
    public function themesDir()
    {
        return "{$this->publicDir()}/themes";
    }

    /**
     * [themePath description]
     *
     * @param  [type] $themeName [description]
     *
     * @return [type] [description]
     */
    public function themePath()
    {
        return "{$this->themesDir()}/{$this->config->gallery->theme}";
    }
}
