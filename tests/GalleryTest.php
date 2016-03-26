<?php

use Uber\Gallery;

class GalleryTest extends PHPUnit_Framework_TestCase {

    protected $gallery;

    public function setUp() {
        $this->gallery = new Gallery();
    }

    /** @test */
    public function it_has_an_array_of_albums() {
        $this->assertEquals([], $this->gallery->albums());
    }

    /** @test */
    public function it_can_add_an_album() {
        $this->gallery->add(__DIR__ . '/test_files');
        $this->assertCount(1, $this->gallery->albums());
    }

}
