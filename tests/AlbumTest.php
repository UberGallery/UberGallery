<?php

use App\Album;
use App\Image;

class AlbumTest extends PHPUnit_Framework_TestCase
{
    /** @var Uber\Album Instance of Uber\Album */
    protected $album;

    public function setUp()
    {
        $this->album = new Album([
            new Image(__DIR__ . '/test_files/test.png'),
            new Image(__DIR__ . '/test_files/test.jpg'),
            new Image(__DIR__ . '/test_files/test.jpeg')
        ]);
    }

    public function test_it_has_an_array_of_images()
    {
        $this->assertCount(3, $this->album->images());
    }

    public function test_it_can_add_an_image()
    {
        $this->album->add(new Image(__DIR__ . '/test_files/test.png'));
        $this->assertCount(4, $this->album->images());
    }

    public function test_it_can_sort_images()
    {
        // ...
    }
}
