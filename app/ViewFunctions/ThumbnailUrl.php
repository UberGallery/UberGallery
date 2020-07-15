<?php

namespace App\ViewFunctions;

use Symfony\Component\Finder\SplFileInfo;

class ThumbnailUrl extends ViewFunction
{
    protected string $name = 'thumbnail_url';

    /** Return the URL to an image. */
    public function __invoke(SplFileInfo $image): string
    {
        return sprintf('/thumbnail/%s', $image->getFilename());
    }
}
