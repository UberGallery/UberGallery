<?php

namespace App\ViewFunctions;

use App\Album;
use App\Image;

class ImageUrl extends ViewFunction
{
    protected string $name = 'image_url';

    /** Return the URL to an image. */
    public function __invoke(Album $album, Image $image): string
    {
        return sprintf('/%s/%s', $album->slug(), $image->name());
    }
}
