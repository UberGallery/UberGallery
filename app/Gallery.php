<?php

namespace App;

use Exception;
use Stash\Interfaces\Cacheable;
use Uber;

class Gallery {

    /**
     * Initialize a new Uber\Gallery object
     *
     * @param  string        $path   Path to folder of images
     * @param  App\Config    $config Instance of App\Config
     * @param  Stash\Cache   $config Instance of Stash\Cache
     *
     * @return Uber\Gallery          Uber\Gallery instance
     */
    static function create($path, Config $config, Cacheable $cache = null)
    {
        $images = [];

        foreach (new \DirectoryIterator($path) as $file) {
            if ($file->isDot() || ! self::isImage($file->getPathname())) continue;
            $key = sha1($file->getPathname());
            $images[] = $cache->remember($key, $config->get('cache.duration'), function() use ($file) {
                try {
                    return new Uber\Image($file->getPathname(), 640);
                } catch (Exception $e) {
                    return null;
                }
            });
        }

        return new Uber\Gallery([new Uber\Album($images)]);
    }

    /**
     * Determine if specified file is an image
     *
     * @param  string  $path Path to file
     *
     * @return boolean       True if file is a valid image, otherwise false
     */
    protected function isImage($path)
    {
        $mimeType = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $path);
        return in_array($mimeType, ['image/png', 'image/jpeg', 'image/jpg']);
    }

}