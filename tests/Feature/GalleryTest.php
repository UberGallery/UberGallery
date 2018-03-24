<?php

namespace Tests\Feature;

use Tests\TestCase;

class GalleryTest extends TestCase
{
    public function test_it_can_retrieve_the_gallery_index()
    {
        $response = $this->get('/');

        $this->assertTrue($response->isOk());
        $this->assertContains('Test Gallery', (string) $response->getBody());
    }
}
