<?php

namespace App;

use Imagick;

class Thumbnail
{
    protected string $content;

    /** Create an image thumbnail. */
    public function __construct(Image $image, int $width, int $height, int $quality)
    {
        $imagick = new Imagick;

        $imagick->readImage($image->path());
        $imagick->resizeImage($width, $height, Imagick::FILTER_LANCZOS, 1, true);
        $imagick->setImageCompressionQuality($quality);

        $this->content = $imagick->getimageblob();
    }

    /** Return the raw thumbnail content. */
    public function content(): string
    {
        return $this->content;
    }

    /** Return the thumbnail mime type. */
    public function mimeType(): string
    {
        return finfo_buffer(finfo_open(FILEINFO_MIME_TYPE), $this->content);
    }
}
