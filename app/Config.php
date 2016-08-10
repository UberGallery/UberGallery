<?php

namespace App;

use Exception;

class Config {

    /** @var array Array of config options */
    protected $config = [];

    /**
     * Uber\Config constructor, runs on object creation
     *
     * @param string $path Path to config file
     */
    public function __construct($path = null) {
        if (isset($path)) $this->load($path);
    }

    /**
     * Retrieve a configuration option via a provided key
     *
     * @param string $key     Configuration option key
     * @param mixed  $default Default value to return if option does not exist
     *
     * @return mixed          Configuration option value or $default value
     */
    public function get($key = null, $default = false) {

        if (! isset($key)) return $this->config;

        $config = $this->config;
        $keys   = explode('.', $key);

        while (! empty($keys) && $key = array_shift($keys)) {

            if (! isset($config[$key])) {
                $config = $default;
                break;
            }

            $config = $config[$key];

        }

        return $config;

    }

    /**
     * Set a configuration option
     *
     * @param  string $key   Configuration option key
     * @param  mixed  $value Configuration option value
     *
     * @return object        This Gallery\Config object
     */
    public function set($key, $value) {
        $this->config[$key] = $value;
        return $this;
    }

    /**
     * Check if a configuration option is present
     *
     * @param  string  $key Configuration option key
     *
     * @return boolean      True of config option is present, otherwise false
     */
    public function has($key) {
        return isset($this->config[$key]);
    }

    /**
     * Load configuration options from a file
     *
     * @param  string $path Path to configuration file
     *
     * @return object       This Gallery\Config object
     */
    public function load($path) {

        $contents  = include($path);

        if (gettype($contents) != 'array') {
            throw new Exception('File ' . $path . ' does not contain a valid array');
        }

        $this->config = array_merge($this->config, $contents);

        return $this;

    }

    /**
     * Merge another Config object into this one
     *
     * @param  Config $config [description]
     * @return [type]         [description]
     */
    public function merge(Config $config) {
        $this->config = array_merge($this->config, $config->get());
        return $this;
    }

}
