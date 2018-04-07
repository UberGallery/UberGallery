<?php

/**
 * Test application cache configuration.
 */

return [
    'enabled' => false,
    'driver' => 'file',
    'drivers' => [
        'file' => function () {
            $this->setCacheDir(__DIR__ . '/../cache');
        },
        'memcached' => function ($memcached) {
            $memcached->addServer('localhost', 11211);
        },
        'redis' => function ($redis) {
            $redis->pconnect('localhost', 6379);
        },
        'apcu' => function () {
            $this->setPrefix('uber_gallery');
        }
    ]
];
