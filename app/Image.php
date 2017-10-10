<?php

namespace App;

use UberGallery\Uber;

class Image extends Controller
{
    public function show($album, $image)
    {
        // TODO: Don't hardcode the extension
        $imagePath = APP_ROOT . "/albums/{$album}/{$image}.jpg";

        $width = $this->config->get("albums.{$album}.thumbnails.width");
        $height = $this->config->get("albums.{$album}.thumbnails.height");

        $key = "{$imagePath}-{$width}x{$height}";

        if (file_exists($imagePath)) {
            $image = $this->cache->remember($key, $this->config->cache->duration, function () use ($imagePath) {
                return new Uber\Image($imagePath);
            });

            $image->render();
        }
    }
}
