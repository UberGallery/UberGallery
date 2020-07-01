<?php

namespace App;

use App\Exceptions\FileNotFoundException;
use SplFileObject;
use Tightenco\Collect\Support\Collection;

class Album extends Model
{
    protected string $slug;
    protected array $config = [];
    protected Collection $images;

    /** Create a new Album. */
    public function __construct(string $slug, array $config = [])
    {
        $this->slug = $slug;
        $this->config = $config;

        $this->images = Collection::make(
            glob("{$this->path()}/*.{gif,jpeg,jpg,png}", GLOB_BRACE)
        )->filter(function ($file) {
            return $this->isImage($file);
        })->map(function ($file) {
            return new SplFileObject($file);
        });
    }

    /** Get the album slug. */
    public function slug(): string
    {
        return $this->slug;
    }

    /** Get the album title. */
    public function title(): string
    {
        return $this->config['title'] ?? $this->calculatedTitle();
    }

    /** Get a collection of album images for a specific page. */
    public function images(int $page = 1): Collection
    {
        return $this->images
            ->when($this->config['pagination'], function ($images) use ($page) {
                return $images->forPage($page, $this->config['images_per_page']);
            })->map(function ($image) {
                return new Image($image->getRealPath());
            })->values();
    }

    /**
     * Sort the album images using a pre-defined method or a custom algorithm.
     *
     * @param string|Closure $method Sort method or closure
     */
    public function sort($method, bool $reverse = false): self
    {
        if ($method instanceof \Closure) {
            $this->images = $this->images->sort($method);

            return $this;
        }

        switch ($method) {
            case 'date':
                $sortFunction = function ($first, $second) {
                    return $first->getMTime() <=> $second->getMTime();
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

    /** Get the album directory path. */
    public function path(): string
    {
        $albumPath = $this->config['path'] ?? realpath(__DIR__ . "/../albums/{$this->slug}");

        if (! $albumPath) {
            throw new FileNotFoundException("Album not found at {$albumPath}");
        }

        return $albumPath;
    }

    /** Get an album configuration item. */
    public function config(string $key, $default = null)
    {
        return $this->config[$key] ?? $default;
    }

    /** Get the calculated album title. */
    protected function calculatedTitle(): string
    {
        return ucwords(str_replace('_', ' ', $this->slug)) . ' Album';
    }
}
