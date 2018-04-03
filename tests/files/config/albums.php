<?php

/**
 * Test application albums configuration.
 */

return [
    'test' => [
        'title' => 'Test Album; Please Ignore',
        'thumbnail' => null,
        'thumbnails' => [
            'width' => 320,
            'height' => 240,
            'resize' => 'fit'
        ],
        'sort' => [
            'method' => 'name',
            'reverse' => false
        ],
        'pagination' => false,
        'images_per_page' => 24,
        'path' => __DIR__ . '/../albums/test'
    ],
];
