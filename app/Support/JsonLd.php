<?php

namespace App\Support;

use App\Models\Faq;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\View\View;

class JsonLd
{
    public const CONTEXT = 'https://schema.org';

    /**
     * @param  array<string, mixed>  $data
     */
    public function __construct(
        private array $data = [],
        private ?string $route = null,
    ) {}

    public static function forView(View $view): array
    {
        return (new self($view->getData(), request()->route()?->getName()))->toArray();
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array{('@context'): string, ('@graph'): list<array<string, mixed>>}
     */
    public static function document(array $data = [], ?string $route = null): array
    {
        return (new self($data, $route))->toArray();
    }

    public static function encode(array $document): string
    {
        $json = json_encode(
            $document,
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP
        );

        return is_string($json) ? $json : '{}';
    }

    /**
     * @return array{('@context'): string, ('@graph'): list<array<string, mixed>>}
     */
    public function toArray(): array
    {
        $graph = [
            $this->organization(),
            $this->website(),
            $this->webPage(),
            $this->breadcrumbs(),
        ];

        $main = $this->mainEntity();
        if ($main !== null) {
            $graph[] = $main;
        }

        foreach ($this->relatedEntities() as $entity) {
            $graph[] = $entity;
        }

        return [
            '@context' => self::CONTEXT,
            '@graph' => $graph,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function organization(): array
    {
        $settings = $this->settings();
        $logo = $this->logoUrl();
        $node = [
            '@type' => 'TravelAgency',
            '@id' => $this->origin().'#organization',
            'name' => $settings['company_name'] ?: 'Tanzania Safaris and Trekking',
            'url' => $this->origin().'/',
            'description' => $settings['tagline'] ?: $this->pageDescription(),
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => $settings['address'] ?: 'Arusha, Tanzania',
                'addressLocality' => 'Arusha',
                'addressCountry' => 'TZ',
            ],
            'geo' => [
                '@type' => 'GeoCoordinates',
                'latitude' => -3.3869,
                'longitude' => 36.6830,
            ],
            'areaServed' => ['@type' => 'Country', 'name' => 'Tanzania'],
            'priceRange' => '$$',
        ];

        if ($logo) {
            $node['logo'] = $logo;
            $node['image'] = $logo;
        }
        if (filled($settings['phone'] ?? null)) {
            $node['telephone'] = $settings['phone'];
        }
        if (filled($settings['email'] ?? null)) {
            $node['email'] = $settings['email'];
        }

        $sameAs = $this->sameAs();
        if ($sameAs !== []) {
            $node['sameAs'] = $sameAs;
        }

        return $node;
    }

    /**
     * @return array<string, mixed>
     */
    private function website(): array
    {
        $node = [
            '@type' => 'WebSite',
            '@id' => $this->origin().'#website',
            'url' => $this->origin().'/',
            'name' => $this->settings()['company_name'] ?: 'Tanzania Safaris and Trekking',
            'publisher' => ['@id' => $this->origin().'#organization'],
            'inLanguage' => 'en-US',
        ];

        if ($this->routeExists('search.index')) {
            $node['potentialAction'] = [
                '@type' => 'SearchAction',
                'target' => [
                    '@type' => 'EntryPoint',
                    'urlTemplate' => route('search.index').'?q={search_term_string}',
                ],
                'query-input' => 'required name=search_term_string',
            ];
        }

        return $node;
    }

    /**
     * @return array<string, mixed>
     */
    private function webPage(): array
    {
        $page = [
            '@type' => $this->webPageType(),
            '@id' => $this->currentUrl().'#webpage',
            'url' => $this->currentUrl(),
            'name' => $this->pageName(),
            'description' => $this->pageDescription(),
            'isPartOf' => ['@id' => $this->origin().'#website'],
            'about' => ['@id' => $this->origin().'#organization'],
            'breadcrumb' => ['@id' => $this->currentUrl().'#breadcrumb'],
            'inLanguage' => 'en-US',
        ];

        $image = $this->pageImage();
        if ($image) {
            $page['primaryImageOfPage'] = $image;
            $page['image'] = $image;
        }

        if ($this->route === 'faq') {
            $questions = $this->faqEntities($this->faqCollection());
            if ($questions !== []) {
                $page['mainEntity'] = $questions;
            }
        } elseif ($this->mainEntityId()) {
            $page['mainEntity'] = ['@id' => $this->mainEntityId()];
        }

        return $page;
    }

    /**
     * @return array<string, mixed>
     */
    private function breadcrumbs(): array
    {
        $elements = [];
        foreach ($this->breadcrumbTrail() as $index => [$name, $url]) {
            $item = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $name,
            ];
            if (filled($url)) {
                $item['item'] = $url;
            }
            $elements[] = $item;
        }

        return [
            '@type' => 'BreadcrumbList',
            '@id' => $this->currentUrl().'#breadcrumb',
            'itemListElement' => $elements,
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    private function mainEntity(): ?array
    {
        return match ($this->route) {
            'packages.show' => $this->touristTrip(),
            'destinations.show' => $this->touristDestination(),
            'blog.show' => $this->blogPosting(),
            default => null,
        };
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function relatedEntities(): array
    {
        $entities = [];

        $itemList = $this->itemList();
        if ($itemList !== null) {
            $entities[] = $itemList;
        }

        if (in_array($this->route, ['home', 'packages.show', 'destinations.show'], true)) {
            $faqs = match ($this->route) {
                'home' => $this->faqCollection(),
                'packages.show' => $this->collection('packageFaqs'),
                'destinations.show' => collect($this->arrayItem($this->destination())['faqs'] ?? []),
                default => collect(),
            };

            $questions = $this->faqEntities($faqs);
            if ($questions !== []) {
                $entities[] = [
                    '@type' => 'FAQPage',
                    '@id' => $this->currentUrl().'#faq',
                    'mainEntity' => $questions,
                ];
            }
        }

        return $entities;
    }

    /**
     * @return array<string, mixed>|null
     */
    private function touristTrip(): ?array
    {
        $package = $this->arrayItem($this->package());
        if ($package === []) {
            return null;
        }

        $url = $this->absolute(route('packages.show', $package['slug'] ?? ''));
        $node = [
            '@type' => 'TouristTrip',
            '@id' => $url,
            'name' => $package['name'] ?? 'Tour package',
            'description' => $package['seo_description']
                ?? \truncate_meta(strip_tags((string) ($package['overview'] ?? '')))
                ?: $this->pageDescription(),
            'url' => $url,
            'touristType' => 'Adventure traveler',
            'provider' => ['@id' => $this->origin().'#organization'],
        ];

        $image = $this->absolute((string) ($package['image'] ?? ''));
        if ($image) {
            $node['image'] = $image;
        }

        $itinerary = array_values(array_filter((array) ($package['itinerary'] ?? [])));
        if ($itinerary !== []) {
            $elements = [];
            foreach ($itinerary as $index => $day) {
                if (! is_array($day)) {
                    continue;
                }
                $elements[] = [
                    '@type' => 'ListItem',
                    'position' => $index + 1,
                    'name' => trim(($day['day'] ?? ('Day '.($index + 1))).': '.($day['title'] ?? 'Itinerary')),
                ];
            }
            if ($elements !== []) {
                $node['itinerary'] = [
                    '@type' => 'ItemList',
                    'numberOfItems' => count($elements),
                    'itemListElement' => $elements,
                ];
            }
        }

        $price = $package['price_per_person'] ?? null;
        if ($price !== null && $price !== '') {
            $node['offers'] = [
                '@type' => 'Offer',
                'price' => (string) $price,
                'priceCurrency' => 'USD',
                'availability' => 'https://schema.org/InStock',
                'url' => $url,
            ];
        }

        return $node;
    }

    /**
     * @return array<string, mixed>|null
     */
    private function touristDestination(): ?array
    {
        $destination = $this->arrayItem($this->destination());
        if ($destination === []) {
            return null;
        }

        $url = $this->absolute(route('destinations.show', $destination['slug'] ?? ''));
        $node = [
            '@type' => 'TouristDestination',
            '@id' => $url,
            'name' => $destination['full_name'] ?? $destination['title'] ?? 'Destination',
            'description' => $destination['seo_description'] ?? $this->pageDescription(),
            'url' => $url,
            'touristType' => 'Adventure traveler',
        ];

        $image = $this->absolute((string) ($destination['image'] ?? ''));
        if ($image) {
            $node['image'] = $image;
        }

        if (filled($destination['region'] ?? null)) {
            $node['containedInPlace'] = [
                '@type' => 'Place',
                'name' => $destination['region'],
            ];
        }

        return $node;
    }

    /**
     * @return array<string, mixed>|null
     */
    private function blogPosting(): ?array
    {
        $post = $this->arrayItem($this->post());
        if ($post === []) {
            return null;
        }

        $url = $this->absolute(route('blog.show', $post['slug'] ?? ''));
        $node = [
            '@type' => 'BlogPosting',
            '@id' => $url,
            'headline' => $post['title'] ?? 'Article',
            'description' => $post['seo_description'] ?? $this->pageDescription(),
            'url' => $url,
            'mainEntityOfPage' => ['@id' => $this->currentUrl().'#webpage'],
            'author' => [
                '@type' => 'Person',
                'name' => $post['author'] ?? $this->settings()['company_name'] ?: 'Tanzania Safaris and Trekking',
            ],
            'publisher' => ['@id' => $this->origin().'#organization'],
        ];

        $image = $this->absolute((string) ($post['image'] ?? ''));
        if ($image) {
            $node['image'] = $image;
        }

        if (filled($post['date'] ?? null)) {
            $node['datePublished'] = $post['date'];
        }

        return $node;
    }

    /**
     * @return array<string, mixed>|null
     */
    private function itemList(): ?array
    {
        $items = match ($this->route) {
            'destinations.index' => $this->listElements($this->collection('destinations'), function ($item): array {
                $destination = $this->arrayItem($item);

                return [
                    '@type' => 'TouristDestination',
                    'name' => $destination['full_name'] ?? $destination['title'] ?? 'Destination',
                    'url' => $this->absolute(route('destinations.show', $destination['slug'] ?? '')),
                ];
            }),
            'packages.index', 'packages.safari', 'packages.trekking', 'packages.day-trips', 'packages.cultural' => $this->listElements(
                $this->paginated('packages'),
                function ($item): array {
                    $package = $this->arrayItem($item);

                    return [
                        '@type' => 'TouristTrip',
                        'name' => $package['name'] ?? 'Package',
                        'url' => $this->absolute(route('packages.show', $package['slug'] ?? '')),
                    ];
                }
            ),
            'blog' => $this->listElements($this->paginated('posts'), function ($item): array {
                $post = $this->arrayItem($item);

                return [
                    '@type' => 'BlogPosting',
                    'headline' => $post['title'] ?? 'Article',
                    'url' => $this->absolute(route('blog.show', $post['slug'] ?? '')),
                ];
            }),
            'home' => $this->listElements($this->collection('featuredPackages'), function ($item): array {
                $package = $this->arrayItem($item);

                return [
                    '@type' => 'TouristTrip',
                    'name' => $package['name'] ?? 'Package',
                    'url' => $this->absolute(route('packages.show', $package['slug'] ?? '')),
                ];
            }),
            default => [],
        };

        if ($items === []) {
            return null;
        }

        return [
            '@type' => 'ItemList',
            '@id' => $this->currentUrl().'#itemlist',
            'numberOfItems' => count($items),
            'itemListElement' => $items,
        ];
    }

    /**
     * @param  Collection<int, mixed>  $items
     * @param  callable(mixed): array<string, mixed>  $mapper
     * @return list<array<string, mixed>>
     */
    private function listElements(Collection $items, callable $mapper): array
    {
        $elements = [];
        foreach ($items->take(20)->values() as $index => $item) {
            $elements[] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'item' => $mapper($item),
            ];
        }

        return $elements;
    }

    /**
     * @param  Collection<int, mixed>  $faqs
     * @return list<array<string, mixed>>
     */
    private function faqEntities(Collection $faqs): array
    {
        $questions = [];
        foreach ($faqs as $faq) {
            if ($faq instanceof Faq) {
                $name = trim((string) $faq->question);
                $answer = trim(strip_tags((string) $faq->answer));
            } elseif (is_array($faq)) {
                $name = trim((string) ($faq['question'] ?? ''));
                $answer = trim(strip_tags((string) ($faq['answer'] ?? '')));
            } else {
                continue;
            }

            if ($name === '' || $answer === '') {
                continue;
            }

            $questions[] = [
                '@type' => 'Question',
                'name' => $name,
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $answer,
                ],
            ];
        }

        return $questions;
    }

    private function webPageType(): string
    {
        return match ($this->route) {
            'about' => 'AboutPage',
            'contact' => 'ContactPage',
            'book-now' => 'WebPage',
            'faq' => 'FAQPage',
            'search.index' => 'SearchResultsPage',
            'destinations.index', 'packages.index', 'packages.safari', 'packages.trekking', 'packages.day-trips', 'packages.cultural', 'blog' => 'CollectionPage',
            default => 'WebPage',
        };
    }

    private function mainEntityId(): ?string
    {
        return match ($this->route) {
            'packages.show' => ($slug = $this->arrayItem($this->package())['slug'] ?? null)
                ? $this->absolute(route('packages.show', $slug))
                : null,
            'destinations.show' => ($slug = $this->arrayItem($this->destination())['slug'] ?? null)
                ? $this->absolute(route('destinations.show', $slug))
                : null,
            'blog.show' => ($slug = $this->arrayItem($this->post())['slug'] ?? null)
                ? $this->absolute(route('blog.show', $slug))
                : null,
            'home' => $this->origin().'#organization',
            default => null,
        };
    }

    /**
     * @return list<array{0: string, 1: ?string}>
     */
    private function breadcrumbTrail(): array
    {
        $home = [$this->settings()['company_name'] ?: 'Home', $this->origin().'/'];

        return match ($this->route) {
            'home' => [[$this->pageName(), $this->origin().'/']],
            'destinations.index' => [$home, ['Destinations', null]],
            'destinations.show' => [
                $home,
                ['Destinations', $this->routeUrl('destinations.index')],
                [$this->arrayItem($this->destination())['full_name'] ?? $this->arrayItem($this->destination())['title'] ?? 'Destination', null],
            ],
            'packages.index', 'packages.safari', 'packages.trekking', 'packages.day-trips', 'packages.cultural' => [
                $home,
                [(string) ($this->data('pageTitle') ?: $this->data('categoryLabel') ?: 'Packages'), null],
            ],
            'packages.show' => [
                $home,
                ['Tour Packages', $this->routeUrl('packages.index')],
                [$this->arrayItem($this->package())['name'] ?? 'Package', null],
            ],
            'blog' => [$home, ['Blog', null]],
            'blog.show' => [
                $home,
                ['Blog', $this->routeUrl('blog')],
                [$this->arrayItem($this->post())['title'] ?? 'Article', null],
            ],
            'about' => [$home, ['About Us', null]],
            'contact' => [$home, ['Contact', null]],
            'book-now' => [$home, ['Book Now', null]],
            'faq' => [$home, ['FAQs', null]],
            'search.index' => [$home, ['Search', null]],
            default => [$home, [$this->pageName(), null]],
        };
    }

    private function pageName(): string
    {
        $title = trim((string) ($this->pageSeo()['title'] ?? ''));
        $site = $this->settings()['company_name'] ?: 'Tanzania Safaris and Trekking';
        $suffix = ' | '.$site;
        if ($title !== '' && Str::endsWith($title, $suffix)) {
            $title = trim(Str::beforeLast($title, $suffix));
        }

        return $title !== '' ? $title : $site;
    }

    private function pageDescription(): string
    {
        return (string) ($this->pageSeo()['description'] ?? $this->settings()['tagline'] ?? '');
    }

    private function pageImage(): ?string
    {
        foreach (['package', 'destination', 'post'] as $key) {
            $item = $this->arrayItem($this->data($key));
            $image = $this->absolute((string) ($item['image'] ?? ''));
            if ($image) {
                return $image;
            }
        }

        $pageBg = $this->data('pageBg');
        if (filled($pageBg)) {
            return $this->absolute((string) $pageBg);
        }

        return $this->pageSeo()['og_image'] ?? $this->logoUrl();
    }

    private function logoUrl(): ?string
    {
        $settings = $this->settings();
        if (filled($settings['default_og_image'] ?? null)) {
            return \absolute_url($settings['default_og_image']);
        }

        return \absolute_url(asset('logo/tanzania-safaris-and-trekking-logo.png'));
    }

    /**
     * @return list<string>
     */
    private function sameAs(): array
    {
        $settings = $this->settings();
        $urls = [];
        foreach (['facebook_url', 'instagram_url', 'twitter_url', 'whatsapp_url'] as $key) {
            if (filled($settings[$key] ?? null)) {
                $urls[] = (string) $settings[$key];
            }
        }

        return array_values(array_unique($urls));
    }

    /**
     * @return array{title?: string, description?: string, keywords?: string, og_title?: string, og_description?: string, og_image?: string}
     */
    private function pageSeo(): array
    {
        $seo = $this->data('pageSeo', []);

        return is_array($seo) ? $seo : [];
    }

    /**
     * @return array<string, string>
     */
    private function settings(): array
    {
        $settings = $this->data('siteSettings');
        if (is_array($settings) && $settings !== []) {
            return $settings;
        }

        return app(SiteSettings::class)->all();
    }

    private function package(): mixed
    {
        return $this->data('package');
    }

    private function destination(): mixed
    {
        return $this->data('destination');
    }

    private function post(): mixed
    {
        return $this->data('post');
    }

    /**
     * @return Collection<int, mixed>
     */
    private function faqCollection(): Collection
    {
        return $this->collection('faqs');
    }

    /**
     * @return array<string, mixed>
     */
    private function arrayItem(mixed $item): array
    {
        if (is_array($item)) {
            return $item;
        }

        if (is_object($item) && method_exists($item, 'toCatalogArray')) {
            return $item->toCatalogArray();
        }

        if (is_object($item)) {
            return (array) $item;
        }

        return [];
    }

    private function origin(): string
    {
        return rtrim(request()->getSchemeAndHttpHost(), '/');
    }

    private function currentUrl(): string
    {
        return rtrim(url()->current(), '/');
    }

    private function absolute(?string $url): ?string
    {
        return \absolute_url($url);
    }

    private function routeUrl(string $name): ?string
    {
        return $this->routeExists($name) ? route($name) : null;
    }

    private function routeExists(string $name): bool
    {
        return app('router')->has($name);
    }

    private function data(string $key, mixed $default = null): mixed
    {
        return $this->data[$key] ?? $default;
    }

    /**
     * @return Collection<int, mixed>
     */
    private function collection(string $key): Collection
    {
        $value = $this->data($key);

        return $value instanceof Collection ? $value : collect($value ?? []);
    }

    /**
     * @return Collection<int, mixed>
     */
    private function paginated(string $key): Collection
    {
        $value = $this->data($key);
        if ($value instanceof Paginator) {
            return collect($value->items());
        }

        return $this->collection($key);
    }
}
