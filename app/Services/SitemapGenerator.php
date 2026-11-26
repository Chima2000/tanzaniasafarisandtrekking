<?php

namespace App\Services;

use App\Models\BlogPost;
use App\Models\Destination;
use App\Models\Package;
use App\Support\PackageCategories;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class SitemapGenerator
{
    public const STORAGE_PATH = 'sitemap.xml';

    /** @var list<string> */
    private const PAGE_ROUTES = [
        'home',
        'packages.index',
        'packages.safari',
        'packages.trekking',
        'packages.day-trips',
        'packages.cultural',
        'destinations.index',
        'blog',
        'about',
        'contact',
        'faq',
        'book-now',
    ];

    /**
     * @return list<array{loc: string, changefreq: string, priority: string, lastmod?: string}>
     */
    public function urls(): array
    {
        $urls = [];

        foreach (self::PAGE_ROUTES as $name) {
            if (! app('router')->has($name)) {
                continue;
            }

            $urls[] = [
                'loc' => route($name),
                'changefreq' => 'weekly',
                'priority' => $name === 'home' ? '1.0' : '0.7',
            ];
        }

        if (Schema::hasTable('destinations')) {
            foreach (Destination::query()->published()->ordered()->get() as $destination) {
                $urls[] = [
                    'loc' => route('destinations.show', $destination->slug),
                    'lastmod' => $destination->updated_at?->toAtomString(),
                    'changefreq' => 'weekly',
                    'priority' => '0.8',
                ];
            }
        }

        if (Schema::hasTable('packages')) {
            foreach (Package::query()->published()->ordered()->get() as $package) {
                $urls[] = [
                    'loc' => route('packages.show', $package->slug),
                    'lastmod' => $package->updated_at?->toAtomString(),
                    'changefreq' => 'weekly',
                    'priority' => '0.9',
                ];
            }
        }

        if (Schema::hasTable('blog_posts')) {
            foreach (BlogPost::query()->published()->latest('published_at')->get() as $post) {
                $urls[] = [
                    'loc' => route('blog.show', $post->slug),
                    'lastmod' => $post->updated_at?->toAtomString(),
                    'changefreq' => 'monthly',
                    'priority' => '0.7',
                ];
            }
        }

        return $urls;
    }

    /**
     * @return array{total: int, pages: int, destinations: int, packages: int, posts: int, categories: int}
     */
    public function summary(): array
    {
        $pages = count(array_filter(
            self::PAGE_ROUTES,
            fn (string $name): bool => app('router')->has($name)
        ));
        $destinations = Schema::hasTable('destinations')
            ? Destination::query()->published()->count()
            : 0;
        $packages = Schema::hasTable('packages')
            ? Package::query()->published()->count()
            : 0;
        $posts = Schema::hasTable('blog_posts')
            ? BlogPost::query()->published()->count()
            : 0;
        $categories = count(PackageCategories::all());

        return [
            'pages' => $pages,
            'destinations' => $destinations,
            'packages' => $packages,
            'posts' => $posts,
            'categories' => $categories,
            'total' => $pages + $destinations + $packages + $posts,
        ];
    }

    public function xml(): string
    {
        return $this->xmlFromUrls($this->urls());
    }

    /**
     * @return array{url_count: int}
     */
    public function write(): array
    {
        $urls = $this->urls();
        Storage::disk('local')->put(self::STORAGE_PATH, $this->xmlFromUrls($urls));

        return ['url_count' => count($urls)];
    }

    public function exists(): bool
    {
        return Storage::disk('local')->exists(self::STORAGE_PATH);
    }

    public function contents(): ?string
    {
        if (! $this->exists()) {
            return null;
        }

        return Storage::disk('local')->get(self::STORAGE_PATH);
    }

    public function generatedAt(): ?Carbon
    {
        if (! $this->exists()) {
            return null;
        }

        return Carbon::createFromTimestamp(Storage::disk('local')->lastModified(self::STORAGE_PATH));
    }

    /**
     * @param  list<array{loc: string, changefreq: string, priority: string, lastmod?: string}>  $urls
     */
    private function xmlFromUrls(array $urls): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n".'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";

        foreach ($urls as $url) {
            $xml .= '<url><loc>'.e($url['loc']).'</loc>';
            if (! empty($url['lastmod'])) {
                $xml .= '<lastmod>'.$url['lastmod'].'</lastmod>';
            }
            $xml .= '<changefreq>'.$url['changefreq'].'</changefreq><priority>'.$url['priority'].'</priority></url>'."\n";
        }

        return $xml.'</urlset>';
    }
}
