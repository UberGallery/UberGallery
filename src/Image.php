<?php

namespace Uber;

use Uber\Thumbnail;
use Exception;

class Image {

    protected $width;
    protected $height;
    protected $contents;
    protected $mimeType;
    public $thumbnail;

    /**
     * Uber\Image constructor, runs on object creation
     *
     * @param string $path Path to image file
     */
    public function __construct($path) {

        if (!$this->isImage($path)) {
            throw new Exception('File ' . $path . ' is not a valid image');
        }

        $this->contents = file_get_contents($path);
        list($this->width, $this->height) = getimagesize($path);
        $this->mimeType = finfo_buffer(finfo_open(FILEINFO_MIME_TYPE), $this->contents);
        $this->thumbnail = new Thumbnail($this, 320, 180);

    }

    // TODO: Rename this to raw() or binary()?
    public function contents() {
        return $this->contents;
    }

    public function base64() {
        return base64_encode($this->contents);
    }

    public function stream() {
        return 'data://' . $this->mimeType . ';base64,' . $this->base64();
    }

    public function width() {
        return $this->width;
    }

    public function height() {
        return $this->height;
    }

    public function mimeType() {
        return $this->mimeType;
    }

    public function exif() {
        return exif_read_data($this->stream());
    }

    protected function isImage($path) {
        $mimeType = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $path);
        return in_array($mimeType, ['image/png', 'image/jpeg', 'image/jpg']);
    }

}
