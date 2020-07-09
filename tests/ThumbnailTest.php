<?php

namespace Tests;

use App\Image;
use App\Thumbnail;

class ThumbnailTest extends TestCase
{
    protected Thumbnail $thumbnail;

    public function setUp(): void
    {
        $this->thumbnail = new Thumbnail(new Image($this->filePath('albums/test/test.png')), 160, 120);
    }

    public function test_it_can_return_the_content(): void
    {
        $this->assertEquals('image/png', $this->mimeType(
            $this->thumbnail->content()
        ));
    }

    public function test_it_can_return_the_width(): void
    {
        $this->assertEquals(160, $this->thumbnail->width());
    }

    public function test_it_can_return_the_height(): void
    {
        $this->assertEquals(120, $this->thumbnail->height());
    }

    public function test_it_can_return_the_dimensions(): void
    {
        $this->assertEquals('160x120', $this->thumbnail->dimensions());
    }

    public function test_it_can_return_the_mime_type(): void
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
