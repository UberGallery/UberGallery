<?php

namespace App\ViewFunctions;

abstract class ViewFunction
{
    protected string $name = '';

    /** Get the function name. */
    public function name(): string
    {
        return $this->name;
    }
}
