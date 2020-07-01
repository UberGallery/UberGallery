<?php

namespace App\ViewFunctions;

use App\Album;
use App\Image;

class ThumbnailUrl extends ViewFunction
{
    protected string $name = 'thumbnail_url';

    /** Return the URL to an image. */
    public function __invoke(Album $album, Image $image): string
    {
        return sprintf('/%s/thumbnail/%s', $album->slug(), $image->name());
    }
}
