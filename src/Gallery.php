<?php

namespace Uber;

use Uber\Album;
use Uber\Config;

class Gallery {

    public $config;
    protected $albums = [];

    /**
     * Uber\Gallery constructor, runs on object creation
     *
     * @param string $albums Array of album directory paths
     * @param Config $config Path to gallery config
     */
    public function __construct(array $albums = [], $config = null) {

        $this->config = new Config();

        if (isset($config)) $this->config->load($config);

        foreach ($albums as $album) $this->add($album);

    }

    /**
     * Adds a directory to the Gallery as an album
     *
     * @param string  $image Directory path
     *
     * @return object        This Uber\Gallery object
     */
    public function add($path) {
        $this->albums[] = new Album($path);
        return $this;
    }

    /**
     * Get an array of this Gallery's Albums
     *
     * @return array Array of Albums
     */
    public function albums() {
        return $this->albums;
    }

}
