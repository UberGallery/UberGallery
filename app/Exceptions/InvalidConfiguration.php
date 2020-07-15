<?php

namespace App\Exceptions;

use RuntimeException;

class InvalidConfiguration extends RuntimeException
{
    /**
     * Create an exception from a configuration option and value.
     *
     * @param mixed $value
     */
    public static function fromConfig(string $option, $value): self
    {
        return new static(
            sprintf("Unknown value %s for configuration option '%s'", var_export($value, true), $option)
        );
    }
}
