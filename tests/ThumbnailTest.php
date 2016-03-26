<?php

use Uber\Image;
use Uber\Thumbnail;

class ThumbnailTest extends PHPUnit_Framework_TestCase {

    protected $thumbnail;

    public function setUp() {
        $image = new Image(__DIR__ . '/test_files/test.jpg');
        $this->thumbnail = new Thumbnail($image, 180, 120);
    }

    /** @test */
    public function it_has_contents() {
        $this->assertNotNull($this->thumbnail->contents());
    }

    /** @test */
    public function it_has_base64() {
        $this->assertRegExp('/^([a-zA-Z0-9\/+]+=*)$/', $this->thumbnail->base64());
    }

}
