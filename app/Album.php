<?php

namespace App;

use PHLAK\Config\Config;
use Tightenco\Collect\Support\Collection;
use App\Exceptions\InvalidImageException;
use App\Exceptions\FileNotFoundException;
use SplFileObject;

class Album
{
    /** @var string The album slug */
    protected $slug;

    /** @var \PHLAK\Config The album config */
    protected $config;

    /**
     * Collection of image files as SplFileObjects.
     *
     * @var \Tightenco\Collect\Support\Collection
     */
    protected $images;

    /**
     * Create a new Album.
     *
     * @param string        $slug   Album slug
     * @param \PHLAK\Config $config The album config
     */
    public function __construct($slug, Config $config)
    {
        $this->slug = $slug;
        $this->config = $config;

        $this->images = Collection::make(
            glob("{$this->path()}/*.{gif,jpeg,jpg,png}", GLOB_BRACE)
        )->map(function ($image) {
            return new SplFileObject($image);
        });
    }

    /**
     * Return the album slug.
     *
     * @return string Album slug
     */
    public function slug()
    {
        return $this->slug;
    }

    /**
     * Return the album title.
     *
     * @return string Album title
     */
    public function title()
    {
        return $this->config->get('title', $this->calculatedTitle());
    }

    /**
     * Return a collection of album images for a specific page.
     *
     * @param int $page The page number
     *
     * @return \Tightenco\Collect\Support\Collection Collection of Images
     */
    public function images($page = 1)
    {
        return $this->images
            ->when($this->config->get('pagination', false), function ($images) use ($page) {
                return $images->forPage($page, $this->config->get('images_per_page', 24));
            })
            ->map(function ($image) {
                $width = $this->config->get('thumbnails.width', 480);
                $height = $this->config->get('thumbnails.height', 480);

                try {
                    return new Image($image->getRealPath(), $width, $height);
                } catch (InvalidImageException $exception) {
                    // Don't worry about it
                }
            })
            ->values();
    }

    /**
     * Sort the album images using a pre-defined method or a custom algorithm.
     *
     * @param string|Closure $method Sort method or closure
     *
     * @return self This Album
     */
    public function sort($method, $reverse = false)
    {
        if ($method instanceof \Closure) {
            $this->images = $this->images->sort($method);
        }

        switch ($method) {
            case 'date':
                $sortFunction = function ($first, $second) {
                    return strtotime($first->getMTime()) <=> strtotime($second->getMTime());
                };
                break;

            case 'size':
                $sortFunction = function ($first, $second) {
                    return $first->getSize() <=> $second->getSize();
                };
                break;

            default:
                $sortFunction = function ($first, $second) {
                    return $first->getFilename() <=> $second->getFilename();
                };
                break;
        }

        $this->images = $this->images
            ->sort($sortFunction)
            ->when($reverse, function ($images) {
                return $images->reverse();
            });

        return $this;
    }

    /**
     * Return the album directory path.
     *
     * @throws \App\Exceptions\FileNotFoundException
     *
     * @return string Full path to the album directory
     */
    public function path()
    {
        $albumPath = $this->config->get(
            'path',
            // TODO: Make this an absolute path?
            realpath(__DIR__ . "/../albums/{$this->slug}")
        );

        if (! $albumPath) {
            throw new FileNotFoundException("Album not found at {$albumPath}");
        }

        return $albumPath;
    }

    /**
     * Return the calculated album title.
     *
     * @return string Caculated album title
     */
    protected function calculatedTitle()
    {
        return ucwords(str_replace('_', ' ', $this->slug)) . ' Album';
    }
}
