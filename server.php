<?php

// This file allows emulating Apache's "mod_rewrite" functionality with the
// built-in PHP web server. This makes it easy to test UberGallery without
// installing a "real" web server.

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

if ($uri !== '/' && file_exists(__DIR__ . '/public' . $uri)) {
    return false;
}

require_once __DIR__ . '/public/index.php';
