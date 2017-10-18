<?php

namespace App;

use Imagick;
use App\Exceptions\InvalidImageException;

class Image
{
    /** @var string Binary string of image data */
    protected $contents;

    /** @var $name Original image file name */
    protected $name;

    /** @var string Cannonical image file path */
    protected $path;

    /** @var int Image width */
    protected $width;

    /** @var int Image height */
    protected $height;

    /** @var string Image mime type */
    protected $mimeType;

    /** @var string Image title */
    protected $title;

    /**
     * Image constructor, runs on object creation.
     *
     * @param string $path   Path to image file
     * @param int    $width  Resized image width
     * @param int    $height Resized image height
     */
    public function __construct($path, $width = 0, $height = 0, $title = null)
    {
        if (! $this->isImage($path)) {
            throw new InvalidImageException($path . ' is not a valid image');
        }

        $this->contents = file_get_contents($path);

        $this->path = realpath($path);
        $this->name = basename($path);

        if ($width > 0 || $height > 0) {
            $imagick = new Imagick;
            $imagick->readImageBlob($this->contents);
            $imagick->resizeImage($width, $height, Imagick::FILTER_LANCZOS, 1);
            $imagick->setImageCompressionQuality(82);
            $this->contents = $imagick->getimageblob();
        }

        list($this->width, $this->height) = getimagesizefromstring($this->contents);
        $this->mimeType = finfo_buffer(finfo_open(FILEINFO_MIME_TYPE), $this->contents);

        if (isset($title)) {
            $this->title = $title;
        }
    }

    /**
     * Get raw image contents
     * TODO: Rename this to raw() or binary()?
     *
     * @return string Binary string of image data
     */
    public function contents()
    {
        return $this->contents;
    }

    /**
     * Get base64 encoded image contents.
     *
     * @return string Base64 encoded string of image data
     */
    public function base64()
    {
        return base64_encode($this->contents);
    }

    /**
     * Get stream wrapped image contents.
     *
     * @return string Stream wrapped string of image data
     */
    public function stream()
    {
        return 'data://' . $this->mimeType . ';base64,' . $this->base64();
    }

    /**
     * Render the image to the browser.
     */
    public function render()
    {
        header('Content-Type: ' . $this->mimeType);

        $image = imagecreatefromstring($this->contents);

        switch ($this->mimeType) {
            case 'image/jpg':
            case 'image/jpeg':
                imagejpeg($image);
                break;

            case 'image/png':
                imagepng($image);
                break;

            case 'image/gif':
                imagegif($image);
                break;

            // case 'image/bmp':
            //     imagewbmp($image);
            //     break;

            default:
                throw new Exception('Invalid image type');
        }

        imagedestroy($image);
    }

    /**
     * Get image width in pixels.
     *
     * @return int Image width
     */
    public function width()
    {
        return $this->width;
    }

    /**
     * Get image height in pixels.
     *
     * @return int Image height
     */
    public function height()
    {
        return $this->height;
    }

    public function dimensions()
    {
        return $this->width . 'x' . $this->height;
    }

    /**
     * Get image mime type.
     *
     * @return string Image mime type
     */
    public function mimeType()
    {
        return $this->mimeType;
    }

    /**
     * Get image exif data.
     *
     * @return array Exif data
     */
    public function exif()
    {
        return exif_read_data($this->stream());
    }

    /**
     * Get a new instance of the image with specified dimensions.
     *
     * @param int $width  Image width
     * @param int $height Image height
     *
     * @return Image Resized Image object
     */
    public function resize($width, $height)
    {
        return new static($this->stream(), $width, $height);
    }

    /**
     * Get the image title.
     *
     * @return string Image title
     */
    public function title()
    {
        return $this->title;
    }

    /**
     * Set the image title.
     *
     * @param string $title Image title
     *
     * @return Image This Image object
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Determine if specified file is an image.
     *
     * @param string $path Path to file
     *
     * @return bool True if file is a valid image, otherwise false
     */
    protected function isImage($path)
    {
        $mimeType = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $path);

        return in_array($mimeType, ['image/png', 'image/jpeg', 'image/jpg']);
    }

    /**
     * [name description]
     *
     * @return [type] [description]
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * [path description]
     *
     * @return [type] [description]
     */
    public function path()
    {
        return $this->path;
    }
}
