<?php

namespace App\Support;

use App\Models\Page;
use Illuminate\Support\Facades\Schema;

class PageSeo
{
    /**
     * @var array<string, string>
     */
    private const ROUTE_PAGE_SLUGS = [
        'home' => 'home',
        'about' => 'about',
        'contact' => 'contact',
        'faq' => 'faq',
        'blog' => 'blog',
        'book-now' => 'book-now',
        'packages.index' => 'packages',
        'destinations.index' => 'destinations',
    ];

    /**
     * @return array{title: string, description: string, keywords: string, og_title: string, og_description: string, og_image: string}
     */
    public static function siteDefaults(): array
    {
        $settings = app(SiteSettings::class);
        $siteName = $settings->get('company_name', 'Tanzania Safaris and Trekking');
        $title = filled($settings->get('default_meta_title'))
            ? \site_title($settings->get('default_meta_title'), $siteName)
            : \site_title(null, $siteName);
        $description = $settings->get(
            'default_meta_description',
            $settings->get('tagline', 'Plan safari and Kilimanjaro adventures across Tanzania with local experts.')
        );
        $keywords = $settings->get(
            'default_meta_keywords',
            'tanzania safari, kilimanjaro trek, serengeti, ngorongoro, arusha day trips, africa safari'
        );
        $ogImage = $settings->get('default_og_image', asset('logo/tanzania-safaris-and-trekking-logo.png'));

        return [
            'title' => $title,
            'description' => $description,
            'keywords' => $keywords,
            'og_title' => $title,
            'og_description' => $settings->get('tagline', $description),
            'og_image' => \absolute_url($ogImage),
        ];
    }

    /**
     * @return array{title: string, description: string, keywords: string, og_title: string, og_description: string, og_image: string}
     */
    public static function forCurrentRoute(): array
    {
        $defaults = self::siteDefaults();
        $route = request()->route()?->getName();

        if (! $route) {
            return $defaults;
        }

        $categorySeo = self::forPackageCategoryRoute($route);
        if ($categorySeo !== null) {
            return array_merge($defaults, $categorySeo);
        }

        $slug = self::ROUTE_PAGE_SLUGS[$route] ?? null;
        if ($slug === null || ! Schema::hasTable('pages')) {
            return $defaults;
        }

        $page = Page::query()->where('slug', $slug)->first();
        if ($page === null) {
            return $defaults;
        }

        return self::fromPage($page, $defaults);
    }

    /**
     * @param  array{title: string, description: string, keywords: string, og_title: string, og_description: string, og_image: string}  $defaults
     * @return array{title: string, description: string, keywords: string, og_title: string, og_description: string, og_image: string}
     */
    public static function fromPage(Page $page, ?array $defaults = null): array
    {
        $defaults ??= self::siteDefaults();
        $title = filled($page->seo_title) ? (string) $page->seo_title : $defaults['title'];
        $description = filled($page->seo_description) ? (string) $page->seo_description : $defaults['description'];
        $keywords = filled($page->seo_keywords) ? (string) $page->seo_keywords : $defaults['keywords'];
        $ogImage = filled($page->hero_image)
            ? \absolute_url((string) $page->hero_image)
            : $defaults['og_image'];

        return [
            'title' => $title,
            'description' => $description,
            'keywords' => $keywords,
            'og_title' => $title,
            'og_description' => $description,
            'og_image' => $ogImage,
        ];
    }

    public static function gtmId(?array $settings = null): ?string
    {
        $id = trim((string) (($settings ?? app(SiteSettings::class)->all())['google_tag_manager_id'] ?? ''));

        return preg_match('/^GTM-[A-Z0-9]+$/i', $id) === 1 ? $id : null;
    }

    public static function ga4Id(?array $settings = null): ?string
    {
        $id = trim((string) (($settings ?? app(SiteSettings::class)->all())['google_analytics_id'] ?? ''));

        return preg_match('/^G-[A-Z0-9]+$/i', $id) === 1 ? $id : null;
    }

    /**
     * @return array{title: string, description: string, keywords: string, og_title: string, og_description: string}|null
     */
    private static function forPackageCategoryRoute(string $route): ?array
    {
        foreach (PackageCategories::all() as $definition) {
            if ($definition['route'] !== $route) {
                continue;
            }

            return [
                'title' => $definition['seo_title'],
                'description' => $definition['seo_description'],
                'keywords' => $definition['seo_keywords'],
                'og_title' => $definition['seo_title'],
                'og_description' => $definition['seo_description'],
            ];
        }

        return null;
    }
}
