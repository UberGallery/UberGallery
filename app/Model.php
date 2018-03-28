<?php

namespace App;

abstract class Model
{
    /**
     * Determine if the file is an image.
     *
     * @param string $path Path to a file
     *
     * @return bool True if file is a valid image, otherwise false
     */
    protected function isImage($path)
    {
        return in_array(mime_content_type($path), [
            'image/gif', 'image/png', 'image/jpeg', 'image/jpg'
        ]);
    }
}
