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

    public function test_it_can_get_a_configuration_item()
    {
        $this->assertEquals('Test Gallery', config('title'));
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
}
