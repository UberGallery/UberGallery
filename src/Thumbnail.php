<?php

namespace Uber;

use Uber\Image;
use Exception;
use Imagick;

class Thumbnail
{

    protected $contents;
    protected $width;
    protected $height;

    /**
     * Uber\Thumbnail constructor, runs on object creation
     *
     * @param Image $image  Image object
     * @param int   $width  Thumbnail width
     * @param int   $height Thumbnail height
     */
    public function __construct(Image $image, $width, $height) {

        $thumbnail = new Imagick();
        $thumbnail->readImageBlob($image->contents());

        $this->contents = $thumbnail->resizeImage(
            $width, $height, Imagick::FILTER_LANCZOS, 1
        );

        $this->width  = $width;
        $this->height = $height;

    }

    public function contents() {
        return $this->contents;
    }

    public function base64() {
        return base64_encode($this->contents);
    }

    public function stream() {
        // TODO: Implement this
    }

    public function width() {
        return $this->width;
    }

    public function height() {
        return $this->height;
    }

}
