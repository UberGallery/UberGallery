<?php

namespace Uber;

use Uber\Config;
use Uber\Image;

class Album {

    protected $config;
    protected $images;

    /**
     * Uber\Album constructor, runs on object creation
     *
     * @param string $path Path to directory
     */
    public function __construct($path) {

        $this->config = new Config();

        foreach (new \DirectoryIterator($path) as $file) {

            if ($file->isDot()) continue;

            if ($file->getFilename() == 'config.php') {
                $this->config->load($file->getPathname());
                continue;
            }

            $this->add($file->getPathname());

        }

    }

    /**
     * Adds an individual image to the Album
     *
     * @param  string $image Image path
     *
     * @return object        This Uber\Album object
     */
    public function add($path) {

        try {
            $this->images[] = new Image($path);
        } catch (Excetption $e) {
            // TODO: Handle this exception
        }

        return $this;

    }

   /**
     * Get an array of this Album's Images
     *
     * @return array Array of Images
     */
    public function images() {
        return $this->images;
    }

    /**
     * Sort the array of images
     *
     * @return object This Uber\Album object
     */
    public function sort() {
        // TODO: Sort the images array
        return $this;
    }

    /**
     * Load a config file
     *
     * @param  $path  Path to config file
     *
     * @return object This Uber\Album object
     */
    protected function config($path) {
        $this->config = include($path);
        return $this;
    }

}
