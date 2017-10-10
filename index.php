<?php

if (! defined(APP_ROOT)) {
    define(APP_ROOT, realpath(__DIR__ . '/app'));
}

require __DIR__ . '/vendor/autoload.php';

$config = new PHLAK\Config\Config(APP_ROOT . '/config/');
$cache = PHLAK\Stash\Cache::make($config->cache->driver, $config->get('cache.config'));

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $route) {
    $route->get('/', 'Gallery@index');

    $route->get('/{album}', 'Album@show');

    $route->get('/{album}/{image}', 'Image@show');
});

@list($uri, $queryString) = explode('?', $_SERVER['REQUEST_URI'], 2);

list($info, $action, $params) = $dispatcher->dispatch($_SERVER['REQUEST_METHOD'], rawurldecode($uri));

if ($info !== FastRoute\Dispatcher::FOUND) {
    die(404); // TODO: Return a real 404 response
}

list($class, $method) = explode('@', $action, 2);
$classPath = 'App\\' . $class;

call_user_func_array([new $classPath($config, $cache), $method], $params);
