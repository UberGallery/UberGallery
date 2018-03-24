<?php

namespace App;

use App\Image;
use Imagick;

class Thumbnail
{
    /** @var string Binary string of thumbnail data */
    protected $content;

    /**
     * Create an image thumbnail.
     *
     * @param Image $image   An Image object
     * @param int   $width   Image width
     * @param int   $height  Image height
     * @param int   $quality Image compression quality (Default: 82)
     */
    public function __construct(Image $image, $width, $height, $quality = 82)
    {
        $imagick = new Imagick;

        $imagick->readImage($image->path());
        $imagick->resizeImage($width, $height, Imagick::FILTER_LANCZOS, 1, true);
        $imagick->setImageCompressionQuality($quality);

        $this->content = $imagick->getimageblob();
    }

    /**
     * Return the raw thumbnail content.
     *
     * @return string Binary string of thumbnail data
     */
    public function content()
    {
        return $this->content;
    }

    /**
     * Return the thumbnail width.
     *
     * @return int Thumbnail width
     */
    public function width()
    {
        [$width, $height] = getimagesizefromstring($this->content);

        return $width;
    }

    /**
     * Return the thumbnail height.
     *
     * @return int Thumbnail height
     */
    public function height()
    {
        [$width, $height] = getimagesizefromstring($this->content);

        return $height;
    }

    /**
    * Return the thumbnail dimensions as [height]x[width].
    *
    * @return string Thumbnail dimensions
    */
    public function dimensions()
    {
        [$width, $height] = getimagesizefromstring($this->content);

        return $width . 'x' . $height;
    }

    /**
     * Return the thumbnail mime type.
     *
     * @return string Mime type
     */
    public function mimeType()
    {
        return finfo_buffer(finfo_open(FILEINFO_MIME_TYPE), $this->content);
    }
}
