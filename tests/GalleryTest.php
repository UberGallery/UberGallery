<?php

use Uber\Gallery;

class GalleryTest extends PHPUnit_Framework_TestCase {

    protected $album;
    protected $gallery;

    public function setUp() {
        $this->album   = __DIR__ . '/test_files';
        $this->gallery = new Gallery();
    }

    /** @test */
    public function it_has_an_array_of_albums() {
        $this->assertEquals([], $this->gallery->albums());
    }

    /** @test */
    public function it_can_add_an_album() {
        $this->gallery->add($this->album);
        $this->assertCount(1, $this->gallery->albums());
    }

    /** @test */
    public function it_can_initialize_multiple_albums() {
        $gallery = new Gallery([$this->album, $this->album]);
        $this->assertCount(2, $gallery->albums());
    }

}
