<?php

class ConfigTest extends PHPUnit_Framework_TestCase {

    protected $config;

    public function setUp() {
        $this->config = new App\Config(__DIR__ . '/test_files/config.php');
    }

    /** @test */
    public function it_can_get_an_option() {
        $this->assertEquals('bar', $this->config->get('bar'));
    }

    /** @test */
    public function it_can_get_an_option_by_dot_notation() {
        $this->assertEquals('foobarbaz', $this->config->get('foo.bar.baz'));
    }

    /** @test */
    public function it_can_set_and_retrieve_an_option() {
        $this->config->set('ping', 'pong');
        $this->assertEquals('pong', $this->config->get('ping'));
    }

    /** @test */
    public function it_returns_a_default_value() {
        $value = $this->config->get('non_existent_key', 'default_value');
        $this->assertEquals('default_value', $value);
    }

    /** @test */
    public function it_has_an_option() {
        $this->assertTrue($this->config->has('baz'));
    }

    /** @test */
    public function it_does_not_have_an_option() {
        $this->assertFalse($this->config->has('potato'));
    }

}
