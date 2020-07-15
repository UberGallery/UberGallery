<?php

namespace App\ViewFunctions;

use Symfony\Component\Finder\SplFileInfo;

class ImageUrl extends ViewFunction
{
    protected string $name = 'image_url';

    /** Return the URL to an image. */
    public function __invoke(SplFileInfo $image): string
    {
        return sprintf('/image/%s', $image->getBasename());
    }
}
