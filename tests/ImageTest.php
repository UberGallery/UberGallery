<?php

namespace Tests;

use App\Exceptions\InvalidImageException;
use App\Image;
use App\Thumbnail;

class ImageTest extends TestCase
{
    protected Image $png;
    protected Image $jpg;
    protected Image $jpeg;

    public function setUp(): void
    {
        $this->png = new Image($this->filePath('albums/test/test.png'));
        $this->jpg = new Image($this->filePath('albums/test/test.jpg'));
        $this->jpeg = new Image($this->filePath('albums/test/test.jpeg'));
    }

    public function test_it_can_return_the_image_content(): void
    {
        $png = $this->png->content();
        $this->assertEquals('image/png', $this->mimeType($png));

        $jpeg = $this->jpeg->content();
        $this->assertEquals('image/jpeg', $this->mimeType($jpeg));

        $jpg = $this->jpg->content();
        $this->assertEquals('image/jpeg', $this->mimeType($jpg));
    }

    public function test_it_can_return_the_base_file_name(): void
    {
        $this->assertEquals('test.png', $this->png->name());
        $this->assertEquals('test.jpg', $this->jpg->name());
        $this->assertEquals('test.jpeg', $this->jpeg->name());
    }

    public function test_it_has_a_mimeType(): void
    {
        $this->assertEquals('image/png', $this->png->mimeType());
        $this->assertEquals('image/jpeg', $this->jpg->mimeType());
        $this->assertEquals('image/jpeg', $this->jpeg->mimeType());
    }

    public function test_it_can_get_a_thumbnail(): void
    {
        $pngThumbnail = $this->png->thumbnail(160, 120, 65);
        $this->assertInstanceOf(Thumbnail::class, $pngThumbnail);

        $jpgThumbnail = $this->jpg->thumbnail(160, 120, 65);
        $this->assertInstanceOf(Thumbnail::class, $jpgThumbnail);

        $jpegThumbnail = $this->jpeg->thumbnail(160, 120, 65);
        $this->assertInstanceOf(Thumbnail::class, $jpegThumbnail);
    }

    public function test_it_throws_an_invalid_image_exception_for_invalid_file_types(): void
    {
        $this->expectException(InvalidImageException::class);

        $image = new Image($this->filePath('albums/test/test.txt'));
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
