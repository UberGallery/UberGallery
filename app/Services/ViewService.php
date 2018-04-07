<?php

namespace App\Services;

use Mustache_Engine;
use Mustache_Loader_FilesystemLoader;

class ViewService extends Service
{
    /**
     * Register the view service.
     *
     * @return void
     */
    public function register()
    {
        $this->bind('view', function ($container) {
            $themePath = realpath($container->root . "/themes/{$container->config->get('gallery.theme')}");

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
