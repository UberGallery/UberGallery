<?php

namespace Tests\Feature;

use Tests\TestCase;

class ThumbnailTest extends TestCase
{
    public function test_it_can_retrieve_a_png_thumbnail()
    {
        $response = $this->get('/test/thumbnail/test.png');

        $this->assertTrue($response->isOk());
        $this->assertEquals('image/png', $response->getHeaderLine('Content-Type'));
    }

    public function test_it_can_retrieve_a_jpg_thumbnail()
    {
        $response = $this->get('/test/thumbnail/test.jpg');

        $this->assertTrue($response->isOk());
        $this->assertEquals('image/jpeg', $response->getHeaderLine('Content-Type'));
    }

    public function test_it_can_retrieve_a_jpeg_thumbnail()
    {
        $response = $this->get('/test/thumbnail/test.jpeg');

        $this->assertTrue($response->isOk());
        $this->assertEquals('image/jpeg', $response->getHeaderLine('Content-Type'));
    }

    public function test_it_returns_a_404_when_trying_to_retrieve_an_invalid_thumbnail()
    {
        $response = $this->get('/test/thumbnail/test.txt');

        $this->assertFalse($response->isOk());
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function test_it_can_cache_a_thumbnail()
    {
        $this->configureApp(['settings' => ['cache' => ['enabled' => true]]]);

        $response = $this->get('/test/thumbnail/test.png');

        $this->assertTrue($response->isOk());
        $this->assertEquals('image/png', $response->getHeaderLine('Content-Type'));

        $this->assertFileExists(__DIR__ . '/../files/cache/e8385a06943cc88819cb7be8fcbd015117539b1c.cache.php');

        $cachedResponse = $this->get('/test/thumbnail/test.png');

        $this->assertTrue($cachedResponse->isOk());
        $this->assertEquals('image/png', $cachedResponse->getHeaderLine('Content-Type'));

        $cache = $this->app->getContainer()->cache;
        $cache->flush();
    }
}
