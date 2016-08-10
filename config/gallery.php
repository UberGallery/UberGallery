<?php

return [

    'theme' => 'default',

    'cache' => [
        'driver' => 'file',
        'duration' => 5,
        'config' => [
            'dir' => 'storage/cache'
        ]
    ]

    // 'cache' => [
    //     'driver' => 'memcached',
    //     'duration' => 5,
    //     'config' => [
    //         'servers' => [
    //             ['host' => 'localhost', 'port' => 11211]
    //         ]
    //     ]
    // ]

];
