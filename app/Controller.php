<?php

namespace App;

use PHLAK\Stash;
use PHLAK\Config;

class Controller
{
    /** @var $config Instance of PHLAK\Config\Config */
    protected $config;

    /** @var $cache Instance of PHLAK\Stash\Cache */
    protected $cache;

    public function __construct(Config\Config $config, Stash\Interfaces\Cacheable $cache)
    {
        $this->config = $config;
        $this->cache = $cache;
    }
}
