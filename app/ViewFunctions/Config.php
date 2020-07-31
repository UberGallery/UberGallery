<?php

namespace App\ViewFunctions;

use App\Config as AppConfig;
use DI\NotFoundException;

class Config extends ViewFunction
{
    protected string $name = 'config';
    protected AppConfig $config;

    /** Create a new Config object. */
    public function __construct(AppConfig $config)
    {
        $this->config = $config;
    }

    /**
     * Retrieve an item from the view config.
     *
     * @param mixed $default
     */
    public function __invoke(string $key, $default = null)
    {
        try {
            return $this->config->get($key);
        } catch (NotFoundException $exception) {
            return $default;
        }
    }
}
