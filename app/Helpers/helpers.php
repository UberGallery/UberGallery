<?php

if (! function_exists('dd')) {
    /**
     * Dump one or more variables and halt further script execution.
     *
     * @param mixed $args One or more variables to be dumped
     *
     * @return void
     */
    function dd(...$args)
    {
        var_dump(...$args);

        die(1);
    }
}

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
    function base_path($subPath = '')
    {
        $basePath = realpath(container()->root . '/' . $subPath);

        if (! $basePath) {
            throw new \App\Exceptions\FileNotFoundException("File not found at {$basePath}");
        }

        return $basePath;
    }
}

if (! function_exists('app_path')) {
    /**
     * Return a path to a file or directory based on the application path.
     *
     * @param string $path File or directory sub-path
     *
     * @throws \App\Exceptions\FileNotFoundException
     *
     * @return string
     */
    function app_path($subPath = '')
    {
        $appPath = realpath(base_path() . '/app/' . $subPath);

        if (! $appPath) {
            throw new \App\Exceptions\FileNotFoundException("File not found at {$appPath}");
        }

        return $appPath;
    }
}
