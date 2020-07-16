<?php

namespace Tests;

use DI\Container;
use DI\ContainerBuilder;
use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Contracts\Cache\CacheInterface;

abstract class TestCase extends PHPUnitTestCase
{
    protected Container $container;
    protected CacheInterface $cache;
    protected string $testFilesPath = __DIR__ . '/_files';

    /** This method is called before each test. */
    public function setUp(): void
    {
        Dotenv::createUnsafeImmutable(__DIR__)->safeLoad();

        $this->container = (new ContainerBuilder)->addDefinitions(
            ...glob(dirname(__DIR__) . '/config/*.php')
        )->build();

        $this->cache = new ArrayAdapter($this->container->get('cache_lifetime'));

        $this->container->set('base_path', $this->testFilesPath);
        $this->container->set('asset_path', $this->filePath('app/assets'));
        $this->container->set('cache_path', $this->filePath('app/cache'));
    }

    /** Get the full path to a test file or folder. */
    protected function filePath(string $filePath): string
    {
        return realpath($this->testFilesPath . '/' . $filePath);
    }
}
