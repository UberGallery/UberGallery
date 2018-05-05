<?php

namespace Tests;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use App\Bootstrap\ApplicationManager;
use App\Exceptions\FileNotFoundException;
use Symfony\Component\DomCrawler\Crawler;
use Slim\Http\Environment;
use Slim\Http\Response;

abstract class TestCase extends PHPUnitTestCase
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
        global $app;

        $app = new \Slim\App([
            'config_path' => __DIR__ . '/files/config/',
            'env_path' => __DIR__ . '/files/',
            'root' => realpath(__DIR__ . '/../')
        ]);

        call_user_func(new ApplicationManager, $app);

        $this->app = $app;
    }

    /**
     * Override the test application configuration.
     *
     * @param string $key   Unique configuration option key
     * @param mixed  $value Config item value
     *
     * @return void
     */
    protected function configureApp($key, $value)
    {
        $this->app->getContainer()->config->set($key, $value);
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

    /**
     * Return the full path to a test file or folder.
     *
     * @param string $path An optional sub-path to apend to the test path
     *
     * @throws \App\Exceptions\FileNotFoundException
     *
     * @return string Full path to the test file or folder
     */
    protected function filePath($path = null)
    {
        $testPath = realpath(__DIR__ . "/files/{$path}");

        if (! $testPath) {
            throw new FileNotFoundException("File or folder not found at {$testPath}");
        }

        return $testPath;
    }

    /**
     * Return an element by it's class.
     *
     * @param string              $selector An element selector or filter
     * @param \Slim\Http\Response $response A Slim application response
     *
     * @return \DOMElement An array of DOMElements
     */
    protected function getElements($selector, Response $response)
    {
        $crawler = new Crawler((string) $response->getBody());

        return $crawler->filter($selector);
    }
}
