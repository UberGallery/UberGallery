<?php

return [
    'cache' => [
        // 'driver' => 'file',
        // 'duration' => 5,
        // 'config' => [
        //     'dir' => __DIR__ . '/../cache' // TODO: Improvoe this
        // ]

        'driver' => 'memcached',
        'duration' => 5,
        'config' => [
            'servers' => [
                ['host' => 'localhost', 'port' => 11211]
            ]
        ]
    ]
];
