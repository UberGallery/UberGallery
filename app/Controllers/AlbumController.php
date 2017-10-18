<?php

namespace App\Controllers;

use App\Album;
use App\Image;

class AlbumController extends Controller
{
    public function show($album)
    {
        $width = $this->app->config->get("albums.{$album}.thumbnails.width");
        $height = $this->app->config->get("albums.{$album}.thumbnails.height");

        $images = [];
        foreach (new \DirectoryIterator($this->app->albumPath($album)) as $file) {
            if ($file->isDot()) continue;

            $key = "{$file->getPathname()}-{$width}x{$height}";

            try {
                $images[] = $this->app->cache->remember($key, $this->app->config->cache->duration, function () use ($file, $width, $height) {
                    return new Image($file->getPathname(), $width, $height);
                });
            } catch (\Exception $e) {
                // Don't worry about it
            }
        }

        // QUESTION: Cache the album?
        $album = new Album($images);

        return $this->view('album', ['app' => $this->app, 'album' => $album, 'images' => $album->images()]);
    }
}
