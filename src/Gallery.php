<?php

namespace Uber;

use Uber\Album;

class Gallery {

    protected $albums = [];

    /**
     * Uber\Gallery constructor, runs on object creation
     *
     * @param string $path Path to initial album
     */
    public function __construct($path = null) {
        if (isset($path)) $this->add($path);
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
