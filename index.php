<?php

require __DIR__ . '/vendor/autoload.php';

$app = call_user_func(new App\Bootstrap\ApplicationFactory, __DIR__);

$app->run();
