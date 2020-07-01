<?php

use App\Bootstrap\AppManager;
use DI\ContainerBuilder;
use Dotenv\Dotenv;

require dirname(__DIR__) . '/vendor/autoload.php';

// Initialize environment variable handler
Dotenv::createUnsafeImmutable(dirname(__DIR__))->safeLoad();

// Initialize the container
$files = glob(dirname(__DIR__) . '/config/*.php');
$container = (new ContainerBuilder)->addDefinitions(...$files);

// if (getenv('APP_DEBUG') !== 'true') {
//     $container->enableCompilation(__DIR__ . '/cache');
// }

// Initialize the application
$app = $container->build()->call(AppManager::class);

// Engage!
$app->run();
