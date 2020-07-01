<?php

namespace App;

use App\Exceptions\InvalidImageException;

class Image extends Model
{
    /** @var string Cannonical image file path */
    protected $path;

    /**
     * Create a new Image.
     *
     * @param string $path Path to image file
     */
    public function __construct($path)
    {
        $this->path = realpath($path);

        if (! $this->isImage($this->path)) {
            throw InvalidImageException::fromPath($this->path);
        }
    }

    /** Create an image from album and name */
    public static function fromAlbumAndName(Album $album, string $name): self
    {
        return new static(
            realpath(sprintf('%s/%s', $album->path(), $name))
        );
    }

    /**
     * Return the image path.
     *
     * @return string Path to the image file
     */
    public function path()
    {
        return $this->path;
    }

    /**
     * Return the raw image content.
     *
     * @return string Binary string of image data
     */
    public function content()
    {
        return file_get_contents($this->path);
    }

    /**
     * Return the image file name.
     *
     * @return string Image name
     */
    public function name()
    {
        return basename($this->path);
    }

    /**
     * Return the image mime type.
     *
     * @return string Mime type
     */
    public function mimeType()
    {
        return mime_content_type($this->path);
    }

    /**
     * Return the image dimensions as [height]x[width].
     *
     * @return string Image dimensions
     */
    public function dimensions()
    {
        [$width, $height] = getimagesize($this->path);

        return $width . 'x' . $height;
    }

    /**
     * Return the image width.
     *
     * @return int Image width
     */
    public function width()
    {
        [$width, $height] = getimagesize($this->path);

        return $width;
    }

    /**
     * Return the image height.
     *
     * @return int Image height
     */
    public function height()
    {
        [$width, $height] = getimagesize($this->path);

        return $height;
    }

    /**
     * Return the image thumbnail with specified dimensions.
     *
     * @param int $width   Image width
     * @param int $height  Image height
     * @param int $quality Image compression quality (Default: 82)
     *
     * @return string Binary string of thumbnail data
     */
    public function thumbnail($width, $height, $quality = 82)
    {
        return new Thumbnail($this, $width, $height, $quality);
    }
}
