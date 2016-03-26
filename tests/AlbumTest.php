<?php

use Uber\Album;

class AlbumTest extends PHPUnit_Framework_TestCase {

    protected $album;

    public function setUp() {
        $this->album = new Album(__DIR__ . '/test_files');
    }

    /** @test */
    public function it_has_an_array_of_images() {
        $this->assertCount(3, $this->album->images());
    }

    /** @test */
    public function it_can_add_an_image() {
        $this->album->add(__DIR__ . '/test_files/test.png');
        $this->assertCount(4, $this->album->images());
    }

}
