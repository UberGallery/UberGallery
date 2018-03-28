<?php

namespace Tests\Feature;

use Tests\TestCase;

class CacheTest extends TestCase
{
    public function test_it_can_cache_an_image()
    {
        $this->configureApp('cache.enabled', true);

        $response = $this->get('/test/test.png');

        $this->assertTrue($response->isOk());
        $this->assertEquals('image/png', $response->getHeaderLine('Content-Type'));

        $this->assertFileExists(__DIR__ . '/../files/cache/8266304c10d8e78c04f573c352e98c2dd62bc396.cache.php');

        $cachedResponse = $this->get('/test/test.png');

        $this->assertTrue($cachedResponse->isOk());
        $this->assertEquals('image/png', $cachedResponse->getHeaderLine('Content-Type'));

        $cache = $this->app->getContainer()->cache;
        $cache->flush();
    }

    public function test_it_can_cache_a_thumbnail()
    {
        $this->configureApp('cache.enabled', true);

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
