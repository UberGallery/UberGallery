<?php

namespace App;

class Asset extends Model
{
    /** @var string Cannonical asset file path */
    protected $path;

    /**
     * Create a new Asset.
     *
     * @param string $path Path to image file
     */
    public function __construct($path)
    {
        $this->path = realpath($path);

        // if (! $this->isImage($this->path)) {
        //     throw new InvalidImageException($this->path . ' is not a valid image');
        // }
    }

    /**
     * Return the asset path as a string.
     *
     * @return string The asset path
     */
    public function __toString()
    {
        return $this->path;
    }

    /**
     * Return the raw asset content.
     *
     * @return string Binary string of asset data
     */
    public function content()
    {
        return file_get_contents($this->path);
    }

    /**
     * Return the asset mime type.
     *
     * @return string Mime type
     */
    public function mimeType()
    {
        switch (pathinfo($this->path, PATHINFO_EXTENSION)) {
            case 'css':
                return 'text/css';

            case 'js':
                return 'text/javascript';

            default:
                return mime_content_type($this->path);
        }
    }
}
