<?php

use Uber\Image;

class ImageTest extends PHPUnit_Framework_TestCase {

    protected $png;
    protected $jpg;
    protected $jpeg;

    public function setUp() {
        $this->png  = new Image(__DIR__ . '/files/test.png');
        $this->jpg  = new Image(__DIR__ . '/files/test.jpg');
        $this->jpeg = new Image(__DIR__ . '/files/test.jpeg');
    }

    /** @test */
    public function png_has_contents() {
        $this->assertNotNull($this->png->contents());
    }

    /** @test */
    public function jpg_has_contents() {
        $this->assertNotNull($this->jpg->contents());
    }

    /** @test */
    public function jpeg_has_contents() {
        $this->assertNotNull($this->jpeg->contents());
    }

    /** @test */
    public function png_has_base64() {
        $this->assertRegExp('/^([a-zA-Z0-9\/+]+=*)$/', $this->png->base64());
    }

    /** @test */
    public function jpg_has_base64() {
        $this->assertRegExp('/^([a-zA-Z0-9\/+]+=*)$/', $this->jpg->base64());
    }

    /** @test */
    public function jpeg_has_base64() {
        $this->assertRegExp('/^([a-zA-Z0-9\/+]+=*)$/', $this->jpeg->base64());
    }

    /** @test */
    public function png_has_mimeType() {
        $this->assertEquals('image/png', $this->png->mimeType());
    }

    /** @test */
    public function jpg_has_mimeType() {
        $this->assertEquals('image/jpeg', $this->jpg->mimeType());
    }

    /** @test */
    public function jpeg_has_mimeType() {
        $this->assertEquals('image/jpeg', $this->jpeg->mimeType());
    }

    /** @test */
    public function png_has_a_thumbnail() {
        $this->assertInstanceOf('Uber\Thumbnail', $this->png->thumbnail);
    }

    /** @test */
    public function jpg_has_a_thumbnail() {
        $this->assertInstanceOf('Uber\Thumbnail', $this->jpg->thumbnail);
    }

    /** @test */
    public function jpeg_has_a_thumbnail() {
        $this->assertInstanceOf('Uber\Thumbnail', $this->jpeg->thumbnail);
    }

}
