<?php

namespace Tests\Feature;

use Tests\TestCase;
use DOMDocument;

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

    // public function test_it_has_the_correct_number_of_images()
    // {
    //     $response = $this->get('/test/');
    //     $html = DOMDocument::loadHTML((string) (string) $response->getBody());
    //     $images = $html->getElementsByTagName('img');
    //
    //     $this->assertTrue($response->isOk());
    //     $this->assertCount(3, $images);
    // }
}
