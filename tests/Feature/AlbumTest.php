<?php

namespace Tests\Feature;

use Tests\TestCase;

class AlbumTest extends TestCase
{
    public function test_it_can_retrieve_an_album()
    {
        $response = $this->get('/test/');

        $this->assertTrue($response->isOk());
        $this->assertContains('Test Album; Please Ignore', (string) $response);
    }

    public function test_it_returns_a_404test_it_returns_a_404_when_an_album_does_not_exist()
    {
        $response = $this->get('/404/');

        $this->assertEquals(404, $response->getStatusCode());
    }
}
