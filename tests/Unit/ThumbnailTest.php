<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Image;
use App\Thumbnail;

class ThumbnailTest extends TestCase
{
    /** @var \App\Thumbnail An instance of App\Thumbnail */
    protected $thumbnail;

    public function setUp()
    {
        $this->thumbnail = new Thumbnail(new Image($this->filePath('albums/test/test.png')), 160, 120);
    }

    public function test_it_can_return_the_content()
    {
        $this->assertEquals('image/png', $this->mimeType(
            $this->thumbnail->content()
        ));
    }

    public function test_it_can_return_the_width()
    {
        $this->assertEquals(160, $this->thumbnail->width());
    }

    public function test_it_can_return_the_height()
    {
        $this->assertEquals(120, $this->thumbnail->height());
    }

    public function test_it_can_return_the_dimensions()
    {
        $this->assertEquals('160x120', $this->thumbnail->dimensions());
    }

    public function test_it_can_return_the_mime_type()
    {
        $this->assertEquals('image/png', $this->thumbnail->mimeType());
    }

    /**
     * Return the mime type of a binary string.
     *
     * @param string $binary Binary string of data
     *
     * @return string Mimey type
     */
    protected function mimeType($binary)
    {
        return finfo_buffer(finfo_open(FILEINFO_MIME_TYPE), $binary);
    }
}
