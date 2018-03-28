<?php

namespace Tests\Feature;

use Tests\TestCase;
use Symfony\Component\DomCrawler\Crawler;

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

        $crawler = new Crawler((string) $response->getBody());
        $images = $crawler->filter('.image');

        $this->assertCount(4, $images);
    }

    public function test_it_has_the_correct_number_of_images_when_pagination_is_enabled()
    {
        $this->configureApp([
            'settings' => [
                'albums' => [
                    'test' => [
                        'pagination' => true,
                        'images_per_page' => 2
                    ]
                ]
            ]
        ]);

        $response = $this->get('/test/');

        $crawler = new Crawler((string) $response->getBody());
        $images = $crawler->filter('.image');

        $this->assertCount(2, $images);
    }
}
