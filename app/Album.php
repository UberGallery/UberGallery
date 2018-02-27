<?php

namespace App;

class Album
{
    /** @var string|null The album title */
    protected $title;

    /** @var array Array of Image objects */
    protected $images;

    /**
     * App\Album constructor. Runs on object creation.
     *
     * @param array  $images Array of Image objects
     * @param string $title  The album's title
     */
    public function __construct(array $images = [], $title = null)
    {
        foreach ($images as $image) {
            $this->add($image);
        }

        $this->title = $title;
    }

    /**
     * Magic getter method for getting the value of a protected property.
     *
     * @param string $property Property name
     *
     * @return mixed
     */
    public function __get($property)
    {
        return $this->$property;
    }

    /**
     * Magic isset method for determining if a magic property is set.
     *
     * @param string $property Property name
     *
     * @return bool True if property is set, otherwise false
     */
    public function __isset($property)
    {
        return isset($this->$property);
    }

    /**
     * Adds an individual image to the Album.
     *
     * @param object $image Instance of Image
     *
     * @return object This Album object
     */
    public function add(Image $image)
    {
        $this->images[] = $image;

        return $this;
    }

    /**
     * Sort the array of images.
     *
     * @return object This Album object
     */
    public function sort()
    {
        // TODO: Sort the images array

        return $this;
    }
}
