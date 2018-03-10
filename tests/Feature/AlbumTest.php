<?php

namespace Tests\Feature;

use Tests\TestCase;

class AlbumTest extends TestCase
{
    public function test_it_can_retrieve_an_album()
    {
        $response = $this->get('/default/');

        $this->assertTrue($response->isOk());
        $this->assertContains('Default Album', (string) $response);
    }
}
