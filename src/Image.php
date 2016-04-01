<?php

namespace Uber;

use Exception;
use Imagick;

class Image {

    protected $contents;
    protected $width;
    protected $height;
    protected $mimeType;

    /**
     * Uber\Image constructor, runs on object creation
     *
     * @param string $path   Path to image file
     * @param int    $width  Resized image width
     * @param int    $height Resized image height
     */
    public function __construct($path, $width = 0, $height = 0) {

        if (!$this->isImage($path)) {
            throw new Exception('File ' . $path . ' is not a valid image');
        }

        $this->contents = file_get_contents($path);

        if ($width > 0 || $height > 0) {
            $imagick = new Imagick;
            $imagick->readImageBlob($this->contents);
            $imagick->resizeImage($width, $height, Imagick::FILTER_LANCZOS, 1);
            $this->contents = $imagick->getimageblob();
        }

        list($this->width, $this->height) = getimagesizefromstring($this->contents);
        $this->mimeType = finfo_buffer(finfo_open(FILEINFO_MIME_TYPE), $this->contents);

    }

    /**
     * Get raw image contents
     * TODO: Rename this to raw() or binary()?
     *
     * @return string Binary string of image data
     */
    public function contents() {
        return $this->contents;
    }

    /**
     * Get base64 encoded image contents
     *
     * @return string Base64 encoded string of image data
     */
    public function base64() {
        return base64_encode($this->contents);
    }

    /**
     * Get stream wrapped image contents
     *
     * @return string Stream wrapped string of image data
     */
    public function stream() {
        return 'data://' . $this->mimeType . ';base64,' . $this->base64();
    }

    /**
     * Get image width in pixels
     *
     * @return int Image width
     */
    public function width() {
        return $this->width;
    }

    /**
     * Get image height in pixels
     *
     * @return int Image height
     */
    public function height() {
        return $this->height;
    }

    /**
     * Get image mime type
     *
     * @return string Image mime type
     */
    public function mimeType() {
        return $this->mimeType;
    }

    /**
     * Get image exif data
     *
     * @return array Exif data
     */
    public function exif() {
        return exif_read_data($this->stream());
    }

    /**
     * Get a new instance of the image with specified dimensions
     *
     * @param  int   $width  Image width
     * @param  int   $height Image height
     *
     * @return Image         Resized Image object
     */
    public function resize($width, $height) {
        return new static($this->stream(), $width, $height);
    }

    /**
     * Determine if specified file is an image
     *
     * @param  string  $path Path to file
     *
     * @return boolean       True if file is a valid image, otherwise false
     */
    protected function isImage($path) {
        $mimeType = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $path);
        return in_array($mimeType, ['image/png', 'image/jpeg', 'image/jpg']);
    }

}
