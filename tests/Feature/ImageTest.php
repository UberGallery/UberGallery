<?php

namespace Tests\Feature;

use Tests\TestCase;

class ImageTest extends TestCase
{
    public function test_it_can_retrieve_an_image()
    {
        $response = $this->get('/default/1297761555736.jpg');

        $this->assertTrue($response->isOk());
        $this->assertEquals('image/jpeg', $response->getHeaderLine('Content-Type'));
    }
}
