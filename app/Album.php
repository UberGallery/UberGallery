<?php

namespace App;

use UberGallery\Uber;

class Album extends Controller
{
    public function show($album)
    {
        $width = $this->config->get("albums.{$album}.thumbnails.width");
        $height = $this->config->get("albums.{$album}.thumbnails.height");

        $images = [];
        foreach (new \DirectoryIterator(realpath(__DIR__ . "/../albums/{$album}")) as $file) {
            if ($file->isDot()) continue;

            $key = "{$file->getPathname()}-{$width}x{$height}";

            try {
                $images[] = $this->cache->remember($key, $this->config->cache->duration, function () use ($file, $width, $height) {
                    return new Uber\Image($file->getPathname(), $width, $height);
                });
            } catch (\Exception $e) {
                // Don't worry about it
            }
        }

        // QUESTION: Cache the album?
        var_dump(new Uber\Album($images));
    }
}
