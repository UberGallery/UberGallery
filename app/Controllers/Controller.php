<?php

namespace App\Controllers;

use App\Bootstrap\Application;
use Mustache_Engine;
use Mustache_Loader_FilesystemLoader;

abstract class Controller
{
    /** @var Application $config Instance of the application */
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function view($view, $data = [])
    {
        $themePath = $this->app->themePath($this->app->config->gallery->theme);

        $mustache = new Mustache_Engine([
            'loader' => new Mustache_Loader_FilesystemLoader($themePath),
            // 'partials_loader' => new Mustache_Loader_FilesystemLoader("$themePath/partials"),
            // 'escape' => function($value) {
            //     return htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
            // }
        ]);

        $data = array_merge([
            'gallery_title' => 'Static Gallery Title',
            'album_title' => 'Static Album Title'
        ], $data);

        // TODO: Create some logic to allow template dot notation (i.e. theme.index, theme.)
        return $mustache->loadTemplate($view)->render($data);
    }
}
