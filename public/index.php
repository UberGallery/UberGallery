<?php

require __DIR__ . '/../vendor/autoload.php';

$config = new PHLAK\Config\Config(__DIR__ . '/../config/');

$app = new App\Bootstrap\Application(__DIR__ . '/../app', $config);

echo $app->view();
