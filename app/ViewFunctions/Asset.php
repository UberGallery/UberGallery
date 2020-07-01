<?php

namespace App\ViewFunctions;

use DI\Container;
use Tightenco\Collect\Support\Collection;

class Asset extends ViewFunction
{
    protected string $name = 'asset';
    protected Container $container;

    /** Create a new Asset object. */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /** Return the path to an asset. */
    public function __invoke(string $path): string
    {
        $path = '/' . ltrim($path, '/');

        if ($this->mixManifest()->has($path)) {
            $path = $this->mixManifest()->get($path);
        }

        return '/assets/' . ltrim($path, '/');
    }

    /** Return the mix manifest collection. */
    protected function mixManifest(): Collection
    {
        $mixManifest = $this->container->get('asset_path') . '/mix-manifest.json';

        if (! is_file($mixManifest)) {
            return new Collection;
        }

        return Collection::make(
            json_decode(file_get_contents($mixManifest), true) ?? []
        );
    }
}
