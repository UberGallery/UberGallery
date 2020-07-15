<?php

namespace Tests;

use App\Image;
use App\Thumbnail;

class ThumbnailTest extends TestCase
{
    protected Thumbnail $thumbnail;

    public function setUp(): void
    {
        $this->thumbnail = new Thumbnail(new Image($this->filePath('albums/test/test.png')), 160, 120, 65);
    }

    public function test_it_can_return_the_content(): void
    {
        $this->assertEquals('image/png', $this->mimeType(
            $this->thumbnail->content()
        ));
    }

    public function test_it_can_return_the_mime_type(): void
    {
        $this->assertEquals('image/png', $this->thumbnail->mimeType());
    }

    /** Get the mime type of a binary string. */
    protected function mimeType($binary): string
    {
        return finfo_buffer(finfo_open(FILEINFO_MIME_TYPE), $binary);
    }
}
