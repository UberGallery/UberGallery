<?php

namespace App\Exceptions;

class InvalidImageException extends \RuntimeException
{
    public static function fromPath(string $path): self
    {
        return new static(sprintf('%s is not a valid image', var_export($path)));
    }
}
