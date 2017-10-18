<?php

namespace App;

class Album
{
    /** @var array Array of Image objects */
    protected $images;

    /**
     * Album constructor, runs on object creation.
     *
     * @param array $images Array of Image objects
     */
    public function __construct(array $images = [])
    {
        foreach ($images as $image) {
            $this->add($image);
        }
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
     * Get an array of this Album's Images.
     *
     * @return array Array of Images
     */
    public function images()
    {
        return $this->images;
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
