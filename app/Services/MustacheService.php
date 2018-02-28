<?php

namespace App\Services;

use Mustache_Engine;
use Mustache_Loader_FilesystemLoader;

class MustacheService extends Service
{
    /**
     * Register Mustache service.
     *
     * @return void
     */
    public function register()
    {
        $this->bind('mustache', function ($container) {
            $themePath = realpath($container->root . "/themes/{$container->config->get('theme')}");

            return new Mustache_Engine([
                'loader' => new Mustache_Loader_FilesystemLoader($themePath, [
                    'extension' => '.mustache.html'
                ]),
                'escape' => function ($value) {
                    return htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
                }
            ]);
        });
    }
}
