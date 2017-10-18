<?php

namespace App\Controllers;

use App\Image;

class ThumbnailController extends Controller
{
    public function show($album, $thumbnail)
    {
        $imagePath = "{$this->app->rootDir()}/albums/{$album}/{$thumbnail}";

        $width = $this->app->config->get("albums.{$album}.thumbnails.width");
        $height = $this->app->config->get("albums.{$album}.thumbnails.height");

        $key = "{$imagePath}-{$width}x{$height}";

        if (file_exists($imagePath)) {
            $image = $this->app->cache->remember($key, $this->app->config->cache->duration, function () use ($imagePath, $width, $height) {
                return new Image($imagePath, $width, $height);
            });

            $image->render();
        }
    }
}
