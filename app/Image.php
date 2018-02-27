<?php

namespace App;

use Imagick;
use ReflectionMethod;
use App\Traits\Cacheable;
use App\Exceptions\InvalidImageException;

class Image
{
    use Cacheable;

    /** @var string Binary string of image data */
    protected $contents;

    /** @var string Cannonical image file path */
    protected $path;

    /** @var int Image width */
    protected $width;

    /** @var int Image height */
    protected $height;

    /** @var string Image mime type */
    protected $mimeType;

    /**
     * App\Image constructor. Runs on object creation.
     *
     * @param string $path   Path to image file
     * @param int    $width  Resized image width
     * @param int    $height Resized image height
     */
    public function __construct($path, $width = 0, $height = 0)
    {
        if (! $this->isImage($path)) {
            throw new InvalidImageException($path . ' is not a valid image');
        }

        $this->contents = file_get_contents($path);

        $this->path = realpath($path);

        if ($width > 0 || $height > 0) {
            $this->contents = $this->resizeContents($width, $height);
        }

        list($this->width, $this->height) = getimagesizefromstring($this->contents);
        $this->mimeType = finfo_buffer(finfo_open(FILEINFO_MIME_TYPE), $this->contents);
    }

    /**
     * Magic getter method for getting the value of a protected property.
     *
     * @param string $property Property name
     *
     * @return mixed
     */
    public function __get($property)
    {
        if (method_exists($this, $property)) {
            $reflection = new ReflectionMethod($this, $property);

            if ($reflection->isPublic()) {
                return $this->$property();
            }
        }

        return $this->$property;
    }

    /**
     * Magic isset method for determining if a magic property is set.
     *
     * @param string $property Property name
     *
     * @return bool True if property is set, otherwise false
     */
    public function __isset($property)
    {
        if (method_exists($this, $property)) {
            $reflection = new ReflectionMethod($this, $property);

            return $reflection->isPublic();
        }

        return isset($this->$property);
    }

    /**
     * Get raw image contents.
     *
     * @return string Binary string of image data
     */
    public function raw()
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
     * Get the image file name.
     *
     * @return string Image name
     */
    public function name()
    {
        return basename($this->path);
    }

    /**
     * Get the image dimensions ad [height]x[width].
     *
     * @return string Image dimensions
     */
    public function dimensions()
    {
        return $this->width . 'x' . $this->height;
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
     * @return App\Image Resized Image object
     */
    public function resize($width, $height)
    {
        return new static($this->stream(), $width, $height);
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
     * Resize the image from it's contents.
     *
     * @param int $width  Resized image width
     * @param int $height Resized image height
     *
     * @return string Binary string of resized image data
     */
    protected function resizeContents($width, $height)
    {
        $imagick = new Imagick;

        $imagick->readImageBlob($this->contents);
        $imagick->resizeImage($width, $height, Imagick::FILTER_LANCZOS, 1, true);
        // QUESTION: Allow this to be customized?
        $imagick->setImageCompressionQuality(82);

        return $imagick->getimageblob();
    }
}
