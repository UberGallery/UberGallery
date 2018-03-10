<?php

namespace Tests;

use PHPUnit_Framework_TestCase;
use App\Bootstrap\ApplicationFactory;
use Slim\Http\Environment;

abstract class TestCase extends PHPUnit_Framework_TestCase
{
    /** @var \Slim\App The Slim application */
    protected $app;

    /**
     * Set up the test environment.
     *
     * @return void
     */
    public function setUp()
    {
        $app = call_user_func(new ApplicationFactory, realpath(__DIR__ . '/..'));

        $app->getContainer()->config->set('albums', [
            'test' => [
                'title' => 'Test Album',
                'path' => $app->getContainer()->root . '/tests/test_files'
            ]
        ]);

        $this->app = $app;
    }

    /**
     * Send a GET request to the application and return a response.
     *
     * @param string $path Request path
     *
     * @return \Slim\Http\Response
     */
    protected function get($path)
    {
        return $this->request('GET', $path);
    }

    /**
     * Send a request to the application and return a response.
     *
     * @param string $method HTTP method
     * @param string $path   Request path
     *
     * @return \Slim\Http\Response
     */
    protected function request($method, $path)
    {
        $container = $this->app->getContainer();
        $container['environment'] = Environment::mock([
            'REQUEST_METHOD' => $method,
            'REQUEST_URI' => $path,
            'SCRIPT_NAME' => 'index.php'
        ]);

        return $this->app->run(true);
    }
}
