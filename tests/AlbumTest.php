<?php

namespace Tests;

use App\Album;

class AlbumTest extends TestCase
{
    protected Album $album;

    public function setUp(): void
    {
        parent::setUp();

        $this->album = new Album('test', $this->container->get('albums')['test']);
    }

    public function test_it_can_instantiate_an_album(): void
    {
        $this->assertInstanceOf(Album::class, $this->album);
    }

    public function test_it_can_return_the_album_slug(): void
    {
        $this->assertEquals('test', $this->album->slug());
    }

    public function test_it_can_return_the_album_title(): void
    {
        $this->assertEquals('Test Album; Please Ignore', $this->album->title());
    }

    public function test_it_can_return_an_album_title_when_one_is_not_set(): void
    {
        $config = $this->container->get('albums')['test'];
        $config['title'] = null;

        $album = new Album('test', $config);

        $this->assertEquals('Test Album', $album->title());
    }
}
