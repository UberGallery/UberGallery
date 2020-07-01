<?php

use App\Factories;
use App\Middlewares;
use App\ViewFunctions;
use Middlewares as HttpMiddlewares;
use Psr\Container\ContainerInterface;

return [
    /* Path definitions */
    'base_path' => dirname(__DIR__),
    'app_path' => DI\string('{base_path}/app'),
    'asset_path' => DI\string('{app_path}/public/assets'),
    'cache_path' => DI\string('{base_path}/cache'),
    'config_path' => DI\string('{base_path}/config'),
    'translations_path' => DI\string('{base_path}/translations'),
    'views_path' => DI\string('{base_path}/views'),
    'albums_path' => DI\string('{base_path}/albums'),

    /* Array of application middlewares */
    'middlewares' => fn (ContainerInterface $container): array => [
        Middlewares\WhoopsMiddleware::class,
        // new HttpMiddlewares\Expires($container->get('http_expires')),
    ],

    /* Array of view functions */
    'view_functions' => [
        ViewFunctions\Asset::class,
        ViewFunctions\Config::class,
        ViewFunctions\ImageUrl::class,
        ViewFunctions\ThumbnailUrl::class,
        ViewFunctions\Translate::class,
        // ViewFunctions\Url::class,
    ],

    /* Container definitions */
    Symfony\Contracts\Cache\CacheInterface::class => DI\factory(Factories\CacheFactory::class),
    Symfony\Contracts\Translation\TranslatorInterface::class => DI\factory(Factories\TranslationFactory::class),
    Slim\Views\Twig::class => DI\factory(Factories\TwigFactory::class),
    Whoops\RunInterface::class => DI\create(Whoops\Run::class),
];
