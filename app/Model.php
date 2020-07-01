<?php

namespace App;

abstract class Model
{
    /** Determine if the file is an image based on it's mime type. */
    protected function isImage(string $path): bool
    {
        return in_array(mime_content_type($path), [
            'image/gif', 'image/png', 'image/jpeg', 'image/jpg'
        ]);
    }
}
