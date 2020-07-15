<?php

namespace App\Factories;

use App\Exceptions\InvalidConfiguration;
use Closure;
use DI\Container;
use Symfony\Component\Finder\Finder;

class FinderFactory
{
    protected Container $container;

    /** Create a new FinderFactory object. */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /** Initialize and return the Finder component. */
    public function __invoke(): Finder
    {
        $finder = Finder::create()->followLinks();
        $finder->ignoreVCS($this->container->get('hide_vcs_files'));

        // $sortOrder = $this->container->get('sort_order');
        // if ($sortOrder instanceof Closure) {
        //     $finder->sort($sortOrder);
        // } else {
        //     if (! array_key_exists($sortOrder, $this->container->get('sort_methods'))) {
        //         throw InvalidConfiguration::fromConfig('sort_order', $sortOrder);
        //     }

        //     $this->container->call($this->container->get('sort_methods')[$sortOrder], [$finder]);
        // }

        if ($this->container->get('reverse_sort')) {
            $finder->reverseSorting();
        }

        return $finder;
    }
}
