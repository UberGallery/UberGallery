<?php

namespace Tests\Feature;

use Tests\TestCase;

class AlbumTest extends TestCase
{
    public function test_it_can_retrieve_an_album()
    {
        $response = $this->get('/test/');

        $this->assertTrue($response->isOk());
        $this->assertContains('Test Album; Please Ignore', (string) $response->getBody());
    }

    public function test_it_returns_a_404_when_an_album_does_not_exist()
    {
        $response = $this->get('/404/');

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('Album not found', (string) $response->getBody());
    }

    public function test_it_has_the_correct_number_of_images_when_pagination_is_disabled()
    {
        $response = $this->get('/test/');
        $images = $this->getElements('.image', $response);

        $this->assertCount(4, $images);
    }

    public function test_it_has_the_correct_number_of_images_when_pagination_is_enabled()
    {
        $this->configureApp('albums.test.pagination', true);
        $this->configureApp('albums.test.images_per_page', 2);

        $response = $this->get('/test/');
        $images = $this->getElements('.image', $response);

        $this->assertCount(2, $images);
    }

    public function test_it_is_sorted_alphabetically_by_default()
    {
        $response = $this->get('/test/');
        $images = $this->getElements('.image', $response);

        $this->assertEquals([
            '/test/test.gif',
            '/test/test.jpeg',
            '/test/test.jpg',
            '/test/test.png'
        ], $images->extract('href'));
    }

    public function test_it_can_be_sorted_in_reverse()
    {
        $this->configureApp('albums.test.sort.reverse', true);

        $response = $this->get('/test/');
        $images = $this->getElements('.image', $response);

        $this->assertEquals([
            '/test/test.png',
            '/test/test.jpg',
            '/test/test.jpeg',
            '/test/test.gif'
        ], $images->extract('href'));
    }

    public function test_it_can_be_sorted_by_date()
    {
        $this->configureApp('albums.test.sort.method', 'date');

        touch($this->filePath('albums/test/test.png'), strtotime('1986-05-20'));
        touch($this->filePath('albums/test/test.jpg'), strtotime('1986-07-06'));
        touch($this->filePath('albums/test/test.gif'), strtotime('2009-06-09'));
        touch($this->filePath('albums/test/test.jpeg'), strtotime('2012-12-12'));

        $response = $this->get('/test/');
        $images = $this->getElements('.image', $response);

        $this->assertEquals([
            '/test/test.png',
            '/test/test.jpg',
            '/test/test.gif',
            '/test/test.jpeg'
        ], $images->extract('href'));
    }

    public function test_it_can_be_sorted_by_size()
    {
        $this->configureApp('albums.test.sort.method', 'size');

        $response = $this->get('/test/');
        $images = $this->getElements('.image', $response);

        $this->assertEquals([
            '/test/test.jpg',
            '/test/test.jpeg',
            '/test/test.png',
            '/test/test.gif'
        ], $images->extract('href'));
    }
}
