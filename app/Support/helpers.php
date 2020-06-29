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

if (! function_exists('request')) {
    /**
     * Get the current request.
     *
     * @return \Slim\Http\Request
     */
    function request()
    {
        return container()->request;
    }
}

if (! function_exists('env')) {
    /**
     * Return the value of an environment vairable.
     *
     * @param string $envar   The name of an environment variable
     * @param mixed  $default Default value to return if no environment variable is set
     *
     * @return mixed
     */
    function env($envar, $default = null)
    {
        $value = getenv($envar);

        if ($value === false) {
            return $default;
        }

        switch (strtolower($value)) {
            case 'true':
                return true;

            case 'false':
                return false;

            case 'null':
                return null;
        }

        return preg_replace('/^"(.*)"$/', '$1', $value);
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
     * Return the path to a file or directory based on the application base path.
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
     * Return the path to a file or directory based on the application path.
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

if (! function_exists('cache_path')) {
    /**
     * Return the path to the application cache directory.
     *
     * @param string $path File or directory sub-path
     *
     * @return string Path to cache file or directory
     */
    function cache_path($path = '')
    {
        return base_path('cache' . DIRECTORY_SEPARATOR . $path);
    }
}

if (! function_exists('albums_path')) {
    /**
     * Return the path to the albums directory.
     *
     * @return string Path to albums directory
     */
    function albums_path()
    {
        return base_path('albums');
    }
}

if (! function_exists('album_path')) {
    /**
     * Return the path to the directory of a specific album.
     *
     * @param string $album Album name
     *
     * @return string Path to album directory
     */
    function album_path($album)
    {
        return base_path('album' . DIRECTORY_SEPARATOR . $album);
    }
}

if (! function_exists('themes_path')) {
    /**
     * Return the path to the application themes directory.
     *
     * @return string Path to themes directory
     */
    function themes_path()
    {
        return base_path('themes');
    }
}

if (! function_exists('theme_path')) {
    /**
     * Return the path to the directory of a specific theme.
     *
     * @param string $theme Theme name
     *
     * @return string Path to theme directory
     */
    function theme_path($theme)
    {
        return base_path('themes' . DIRECTORY_SEPARATOR . $theme);
    }
}
