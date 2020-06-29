<?php

namespace App\Controllers;

use App\Asset;
use Slim\Http\Request;
use Slim\Http\Response;

class AssetController extends Controller
{
    /**
     * Handle an incoming Gallery request and return a response.
     *
     * @param \Slim\Http\Request  $request  Incoming request object
     * @param \Slim\Http\Response $response Outgoing response object
     * @param array               $args     the array of request arguments
     *
     * @return \Slim\Http\Response
     */
    public function __invoke(Request $request, Response $response, array $args)
    {
        try {
            $asset = new Asset(
                $this->assetPath($request->getQueryParam('path'))
            );
        } catch (Exception $exception) {
            return $response->withStatus(404)->write('Image not found');
        }

        $this->restrictFileSystemAccess();

        return $response
            ->withHeader('Content-Type', $asset->mimeType())
            ->write($asset->content());
    }

    /**
     * Return the path for a given asset.
     *
     * @param string $asset Relative asset path
     *
     * @return string Full path to the asset
     */
    protected function assetPath($asset)
    {
        return realpath("{$this->themePath()}/assets/{$asset}");
    }

    /**
     * Prevent accessing files from any folder outside of the theme's
     * assets directory.
     *
     * @return void
     */
    protected function restrictFileSystemAccess()
    {
        ini_set('open_basedir', realpath("{$this->themePath()}/assets"));
    }
}
