<?php

namespace Tests\Feature;

use Tests\TestCase;

class ImageTest extends TestCase
{
    public function test_it_can_retrieve_a_png_image()
    {
        $response = $this->get('/test/test.png');

        $this->assertTrue($response->isOk());
        $this->assertEquals('image/png', $response->getHeaderLine('Content-Type'));
    }

    public function test_it_can_retrieve_a_jpg_image()
    {
        $response = $this->get('/test/test.jpg');

        $this->assertTrue($response->isOk());
        $this->assertEquals('image/jpeg', $response->getHeaderLine('Content-Type'));
    }

    public function test_it_can_retrieve_a_jpeg_image()
    {
        $response = $this->get('/test/test.jpeg');

        $this->assertTrue($response->isOk());
        $this->assertEquals('image/jpeg', $response->getHeaderLine('Content-Type'));
    }

    public function test_it_returns_a_404_when_trying_to_retrieve_an_invalid_image()
    {
        $response = $this->get('/test/test.txt');

        $this->assertFalse($response->isOk());
        $this->assertEquals(404, $response->getStatusCode());
    }
}
