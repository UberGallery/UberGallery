<?php

if (! function_exists('app')) {
    /**
     * Return the application instance.
     *
     * @return \Slim\App The Slim application
     */
    function app()
    {
        global $app;

        return $app;
    }
}

if (! function_exists('container')) {
    /**
     * Return the application container instance.
     *
     * @return \Slim\Container The Slim application container
     */
    function container()
    {
        return app()->getContainer();
    }
}

if (! function_exists('config')) {
    /**
     * Return application configuration items from the container.
     *
     * @param string $key     Unique config item key
     * @param mixed  $default Value to be returned if the config item doesn't exist
     *
     * @return mixed The config item or default value
     */
    function config($key, $default = null)
    {
        return container()->config->get($key, $default);
    }
}

if (! function_exists('base_path')) {
    /**
     * Return a path to a file or directory based on the application base path.
     *
     * @param string $path File or directory sub-path
     *
     * @throws \App\Exceptions\FileNotFoundException
     *
     * @return string Path to file or directory
     */
    function base_path($path = '')
    {
        return realpath(container()->root . DIRECTORY_SEPARATOR . $path);
    }
}

if (! function_exists('app_path')) {
    /**
     * Return a path to a file or directory based on the application path.
     *
     * @param string $path File or directory sub-path
     *
     * @return string Path to app file or directory
     */
    function app_path($path = '')
    {
        return base_path('app' . DIRECTORY_SEPARATOR . $path);
    }
}
