<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Album;

class AlbumTest extends TestCase
{
    /** @var \App\Album Instance of App\Album */
    protected $album;

    public function setUp()
    {
        parent::setUp();

        $this->album = new Album('test', $this->app->getContainer()->config->split('albums.test'));
    }

    public function test_it_can_instantiate_an_album()
    {
        $this->assertInstanceOf(Album::class, $this->album);
    }

    public function test_it_can_return_the_album_slug()
    {
        $this->assertEquals('test', $this->album->slug());
    }

    public function test_it_can_return_the_album_title()
    {
        $this->assertEquals('Test Album; Please Ignore', $this->album->title());
    }

    public function test_it_can_return_an_album_title_when_one_is_not_set()
    {
        $this->configureApp('albums.test.title', null);

        $album = new Album('test', $this->app->getContainer()->config->split('albums.test'));

        $this->assertEquals('Test Album', $album->title());
    }
}
