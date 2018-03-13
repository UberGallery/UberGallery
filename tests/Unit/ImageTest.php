<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Image;
use App\Exceptions\InvalidImageException;

class ImageTest extends TestCase
{
    /** @var \App\Image PNG instance of Image */
    protected $png;

    /** @var \App\Image JPG instance of Image */
    protected $jpg;

    /** @var \App\Image JPEG instance of Image */
    protected $jpeg;

    public function setUp()
    {
        $this->png = new Image($this->filePath('albums/test/test.png'));
        $this->jpg = new Image($this->filePath('albums/test/test.jpg'));
        $this->jpeg = new Image($this->filePath('albums/test/test.jpeg'));
    }

    public function test_it_can_return_the_content()
    {
        $this->assertNotNull($this->png->content);
        $this->assertNotNull($this->jpg->content);
        $this->assertNotNull($this->jpeg->content);
    }

    public function test_it_can_return_the_raw_content()
    {
        $this->assertNotNull($this->png->raw);
        $this->assertNotNull($this->jpg->raw);
        $this->assertNotNull($this->jpeg->raw);
    }

    public function test_it_can_return_the_base64_encoded_content()
    {
        $this->assertRegExp('/^([a-zA-Z0-9\/+]+=*)$/', $this->png->base64);
        $this->assertRegExp('/^([a-zA-Z0-9\/+]+=*)$/', $this->jpg->base64);
        $this->assertRegExp('/^([a-zA-Z0-9\/+]+=*)$/', $this->jpeg->base64);
    }

    public function test_it_can_return_the_stream_wrapped_image_content()
    {
        $this->assertRegExp('/^data:\/\/image\/png;base64,([a-zA-Z0-9\/+]+=*)$/', $this->png->stream);
        $this->assertRegExp('/^data:\/\/image\/jpeg;base64,([a-zA-Z0-9\/+]+=*)$/', $this->jpg->stream);
        $this->assertRegExp('/^data:\/\/image\/jpeg;base64,([a-zA-Z0-9\/+]+=*)$/', $this->jpeg->stream);
    }

    public function test_it_can_return_the_base_file_name()
    {
        $this->assertEquals('test.png', $this->png->name);
        $this->assertEquals('test.jpg', $this->jpg->name);
        $this->assertEquals('test.jpeg', $this->jpeg->name);
    }

    public function test_it_has_dimensions()
    {
        $this->assertEquals('320x240', $this->png->dimensions);
        $this->assertEquals('320x240', $this->jpg->dimensions);
        $this->assertEquals('320x240', $this->jpeg->dimensions);
    }

    public function test_it_has_exif_data()
    {
        $this->assertNotNull($this->jpg->exif);
        $this->assertNotNull($this->jpeg->exif);
    }

    public function test_it_has_a_width()
    {
        $this->assertEquals(320, $this->png->width);
        $this->assertEquals(320, $this->jpg->width);
        $this->assertEquals(320, $this->jpeg->width);
    }

    public function test_it_has_a_height()
    {
        $this->assertEquals(240, $this->png->height);
        $this->assertEquals(240, $this->jpg->height);
        $this->assertEquals(240, $this->jpeg->height);
    }

    public function test_it_has_a_mimeType()
    {
        $this->assertEquals('image/png', $this->png->mimeType);
        $this->assertEquals('image/jpeg', $this->jpg->mimeType);
        $this->assertEquals('image/jpeg', $this->jpeg->mimeType);
    }

    public function test_it_can_be_resized()
    {
        $pngThumb = $this->png->resize(160, 120);
        $this->assertEquals(160, $pngThumb->width);
        $this->assertEquals(120, $pngThumb->height);

        $jpgThumb = $this->jpg->resize(160, 120);
        $this->assertEquals(160, $jpgThumb->width);
        $this->assertEquals(120, $jpgThumb->height);

        $jpegThumb = $this->jpeg->resize(160, 120);
        $this->assertEquals(160, $jpegThumb->width);
        $this->assertEquals(120, $jpegThumb->height);
    }

    public function test_it_throws_an_invalid_image_exception_for_invalid_file_types()
    {
        $this->expectException(InvalidImageException::class);

        $image = new Image($this->filePath('albums/test/test.txt'));
    }

    public function test_it_can_check_if_a_png_property_is_set()
    {
        $this->assertTrue(isset($this->png->content));
        $this->assertTrue(isset($this->png->raw));
        $this->assertTrue(isset($this->png->base64));
        $this->assertTrue(isset($this->png->stream));
        $this->assertTrue(isset($this->png->name));
        $this->assertTrue(isset($this->png->path));
        $this->assertTrue(isset($this->png->dimensions));
        $this->assertTrue(isset($this->png->exif));
        $this->assertTrue(isset($this->png->width));
        $this->assertTrue(isset($this->png->height));
        $this->assertTrue(isset($this->png->mimeType));
    }

    public function test_it_can_check_if_a_jpg_property_is_set()
    {
        $this->assertTrue(isset($this->jpg->content));
        $this->assertTrue(isset($this->jpg->raw));
        $this->assertTrue(isset($this->jpg->base64));
        $this->assertTrue(isset($this->jpg->stream));
        $this->assertTrue(isset($this->jpg->name));
        $this->assertTrue(isset($this->jpg->path));
        $this->assertTrue(isset($this->jpg->dimensions));
        $this->assertTrue(isset($this->jpg->exif));
        $this->assertTrue(isset($this->jpg->width));
        $this->assertTrue(isset($this->jpg->height));
        $this->assertTrue(isset($this->jpg->mimeType));
    }

    public function test_it_can_check_if_a_jpeg_property_is_set()
    {
        $this->assertTrue(isset($this->jpeg->content));
        $this->assertTrue(isset($this->jpeg->raw));
        $this->assertTrue(isset($this->jpeg->base64));
        $this->assertTrue(isset($this->jpeg->stream));
        $this->assertTrue(isset($this->jpeg->name));
        $this->assertTrue(isset($this->jpeg->path));
        $this->assertTrue(isset($this->jpeg->dimensions));
        $this->assertTrue(isset($this->jpeg->exif));
        $this->assertTrue(isset($this->jpeg->width));
        $this->assertTrue(isset($this->jpeg->height));
        $this->assertTrue(isset($this->jpeg->mimeType));
    }
}
