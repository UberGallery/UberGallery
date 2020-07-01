<?php

namespace App\ViewFunctions;

use DI\Container;
use DI\NotFoundException;

class Config extends ViewFunction
{
    protected string $name = 'config';
    protected Container $container;

    /** Create a new Config object. */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /** Retrieve an item from the view config. */
    public function __invoke(string $key, $default = null)
    {
        try {
            return $this->container->get($key);
        } catch (NotFoundException $exception) {
            return $default;
        }
    }
}
