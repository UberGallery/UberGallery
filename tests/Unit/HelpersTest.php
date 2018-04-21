<?php

namespace Tests\Unit;

use Tests\TestCase;

class HelpersTest extends TestCase
{
    public function test_it_can_get_the_application_instance()
    {
        $this->assertInstanceOf(\Slim\App::class, app());
    }

    public function test_it_can_get_the_application_container()
    {
        $this->assertInstanceOf(\Slim\Container::class, container());
    }

    public function test_it_can_get_environment_variables()
    {
        putenv('TEST_STRING=John Pinkerton');

        $this->assertEquals('John Pinkerton', env('TEST_STRING'));
        $this->assertNull(env('NON_EXISTANT'));
        $this->assertEquals('foo', env('NON_EXISTANT', 'foo'));
    }

    public function test_it_can_get_a_configuration_item()
    {
        $this->assertEquals('Test Gallery', config('gallery.title'));
    }

    public function test_it_can_get_the_application_base_path()
    {
        $this->assertEquals(realpath(__DIR__ . '/../../'), base_path());
    }

    public function test_it_can_get_an_application_path_relative_to_the_base_path()
    {
        $this->assertEquals(realpath(__DIR__ . '/../../tests/'), base_path('tests'));
    }

    public function test_it_returns_false_when_getting_an_invalid_application_base_path()
    {
        $this->assertFalse(base_path('not_a_real_folder'));
    }

    public function test_it_can_get_the_app_path()
    {
        $this->assertEquals(realpath(__DIR__ . '/../../app/'), app_path());
    }

    public function test_it_can_get_an_app_path_relative_to_the_path()
    {
        $this->assertEquals(realpath(__DIR__ . '/../../app/Bootstrap'), app_path('Bootstrap'));
    }

    public function test_it_returns_false_when_getting_an_invalid_app_path()
    {
        $this->assertFalse(app_path('not_a_real_folder'));
    }

    public function test_it_can_get_the_cache_path()
    {
        $this->assertEquals(realpath(__DIR__ . '/../../cache/'), cache_path());
    }

    public function test_it_can_get_an_cache_path_relative_to_the_path()
    {
        $this->assertEquals(realpath(__DIR__ . '/../../cache/some_sub_dir'), cache_path('some_sub_dir'));
    }

    public function test_it_returns_false_when_getting_an_invalid_cache_path()
    {
        $this->assertFalse(cache_path('not_a_real_folder'));
    }

    public function test_it_can_get_the_albums_path()
    {
        $this->assertEquals(realpath(__DIR__ . '/../../albums'), albums_path());
    }

    public function test_it_can_get_a_specific_albums_path()
    {
        $this->assertEquals(realpath(__DIR__ . '/../../app/default'), album_path('default'));
    }

    public function test_it_returns_false_when_getting_an_invalid_album_path()
    {
        $this->assertFalse(album_path('not_a_real_album'));
    }
}
