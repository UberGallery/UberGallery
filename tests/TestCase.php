<?php

namespace Tests;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use App\Exceptions\FileNotFoundException;
use Slim\Http\Environment;

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
        $this->configureApp();
    }

    /**
     * Set up or override the test application configuration.
     *
     * @param  array $config Application config array
     *
     * @return void
     */
    protected function configureApp(array $config = [])
    {
        $app = new \Slim\App(array_replace_recursive([
            'settings' => include __DIR__ . '/files/settings.php',
            'root' => realpath(__DIR__ . '/../')
        ], $config));

        call_user_func(new \App\Bootstrap\ApplicationManager, $app);

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
}
