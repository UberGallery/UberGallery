<?php

namespace App\Controllers;

use App\Image;

class ImageController extends Controller
{
    public function show($album, $image)
    {
        $imagePath = "{$this->app->rootDir()}/albums/{$album}/{$image}";

        if (file_exists($imagePath)) {
            $image = $this->app->cache->remember($imagePath, $this->app->config->cache->duration, function () use ($imagePath) {
                return new Image($imagePath);
            });

            $image->render();
        }
    }
}
