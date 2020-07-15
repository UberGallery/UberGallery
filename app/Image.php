<?php

namespace App;

use App\Exceptions\InvalidImageException;

class Image
{
    protected string $path;

    /** Create a new Image. */
    public function __construct(string $path)
    {
        $this->path = realpath($path);

        if (! $this->isImage($this->path)) {
            throw InvalidImageException::fromPath($this->path);
        }
    }

    /** Return the image path. */
    public function path(): string
    {
        return $this->path;
    }

    /** Return the raw image content.  */
    public function content(): string
    {
        return file_get_contents($this->path);
    }

    /** Return the image file name. */
    public function name(): string
    {
        return basename($this->path);
    }

    /** Return the image mime type. */
    public function mimeType(): string
    {
        return mime_content_type($this->path);
    }

    /** Return the image thumbnail of specified dimensions and quality. */
    public function thumbnail(int $width, int $height, int $quality): Thumbnail
    {
        return new Thumbnail($this, $width, $height, $quality);
    }

    /** Determine if the file is an image based on it's mime type. */
    protected function isImage(string $path): bool
    {
        return in_array(mime_content_type($path), [
            'image/gif', 'image/png', 'image/jpeg', 'image/jpg'
        ]);
    }
}
