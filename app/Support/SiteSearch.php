<?php

namespace App\Support;

use App\Models\Faq;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class SiteSearch
{
    public function __construct(
        private TourCatalog $catalog,
        private PublishedBlogPosts $blogPosts,
    ) {}

    /**
     * @return Collection<int, array{type: string, type_label: string, title: string, description: string, url: string, image: string|null}>
     */
    public function search(string $query, ?int $limit = null): Collection
    {
        $needle = Str::lower(trim($query));

        if ($needle === '' || Str::length($needle) < 2) {
            return collect();
        }

        $results = collect()
            ->merge($this->searchPackages($needle))
            ->merge($this->searchDestinations($needle))
            ->merge($this->searchBlogPosts($needle))
            ->merge($this->searchFaqs($needle))
            ->merge($this->searchPages($needle))
            ->values();

        if ($limit !== null) {
            return $results->take($limit)->values();
        }

        return $results;
    }

    /**
     * @return Collection<int, array{type: string, type_label: string, title: string, description: string, url: string, image: string|null}>
     */
    private function searchPackages(string $needle): Collection
    {
        return $this->catalog->packages()
            ->filter(function (array $package) use ($needle): bool {
                $haystack = Str::lower(implode(' ', array_filter([
                    $package['name'] ?? '',
                    $package['location'] ?? '',
                    $package['category'] ?? '',
                    $package['duration'] ?? '',
                    $package['overview'] ?? '',
                ])));

                return Str::contains($haystack, $needle);
            })
            ->map(function (array $package): array {
                $description = trim((string) ($package['overview'] ?? ''));

                if ($description === '') {
                    $description = trim(implode(' · ', array_filter([
                        $package['category'] ?? null,
                        $package['duration'] ?? null,
                        $package['location'] ?? null,
                    ])));
                }

                return [
                    'type' => 'package',
                    'type_label' => 'Package',
                    'title' => (string) $package['name'],
                    'description' => Str::limit(strip_tags($description), 120),
                    'url' => route('packages.show', $package['slug']),
                    'image' => $package['image'] ?: null,
                ];
            })
            ->values();
    }

    /**
     * @return Collection<int, array{type: string, type_label: string, title: string, description: string, url: string, image: string|null}>
     */
    private function searchDestinations(string $needle): Collection
    {
        return $this->catalog->destinations()
            ->filter(function (array $destination) use ($needle): bool {
                $needles = collect($destination['needles'] ?? [])
                    ->map(fn (mixed $value): string => (string) $value)
                    ->implode(' ');

                $haystack = Str::lower(implode(' ', array_filter([
                    $destination['title'] ?? '',
                    $destination['full_name'] ?? '',
                    $destination['short'] ?? '',
                    $destination['region'] ?? '',
                    $destination['headline'] ?? '',
                    $destination['description'] ?? '',
                    $destination['story_intro'] ?? '',
                    $needles,
                ])));

                return Str::contains($haystack, $needle);
            })
            ->map(function (array $destination): array {
                $description = trim((string) ($destination['description'] ?? $destination['headline'] ?? $destination['story_intro'] ?? ''));

                if ($description === '') {
                    $description = trim(implode(' · ', array_filter([
                        $destination['region'] ?? null,
                        $destination['places'] ?? null,
                    ])));
                }

                return [
                    'type' => 'destination',
                    'type_label' => 'Destination',
                    'title' => (string) ($destination['title'] ?? $destination['full_name'] ?? 'Destination'),
                    'description' => Str::limit(strip_tags($description), 120),
                    'url' => route('destinations.show', $destination['slug']),
                    'image' => ($destination['image'] ?? '') !== '' ? $destination['image'] : null,
                ];
            })
            ->values();
    }

    /**
     * @return Collection<int, array{type: string, type_label: string, title: string, description: string, url: string, image: string|null}>
     */
    private function searchBlogPosts(string $needle): Collection
    {
        return $this->blogPosts->all()
            ->filter(function (array $post) use ($needle): bool {
                $tags = collect($post['tags'] ?? [])
                    ->map(fn (mixed $value): string => (string) $value)
                    ->implode(' ');

                $haystack = Str::lower(implode(' ', array_filter([
                    $post['title'] ?? '',
                    $post['category'] ?? '',
                    $post['intro'] ?? '',
                    $post['seo_description'] ?? '',
                    $post['author'] ?? '',
                    $tags,
                ])));

                return Str::contains($haystack, $needle);
            })
            ->map(function (array $post): array {
                $description = trim((string) ($post['intro'] ?? $post['seo_description'] ?? ''));

                if ($description === '') {
                    $description = (string) ($post['category'] ?? 'Travel story');
                }

                return [
                    'type' => 'blog',
                    'type_label' => 'Blog',
                    'title' => (string) $post['title'],
                    'description' => Str::limit(strip_tags($description), 120),
                    'url' => route('blog.show', $post['slug']),
                    'image' => ($post['image'] ?? '') !== '' ? $post['image'] : null,
                ];
            })
            ->values();
    }

    /**
     * @return Collection<int, array{type: string, type_label: string, title: string, description: string, url: string, image: string|null}>
     */
    private function searchFaqs(string $needle): Collection
    {
        if (! Schema::hasTable('faqs')) {
            return collect();
        }

        return Faq::query()
            ->published()
            ->ordered()
            ->get()
            ->filter(function (Faq $faq) use ($needle): bool {
                $haystack = Str::lower($faq->question.' '.$faq->answer);

                return Str::contains($haystack, $needle);
            })
            ->map(function (Faq $faq): array {
                return [
                    'type' => 'faq',
                    'type_label' => 'FAQ',
                    'title' => (string) $faq->question,
                    'description' => Str::limit(strip_tags((string) $faq->answer), 120),
                    'url' => route('faq'),
                    'image' => null,
                ];
            })
            ->values();
    }

    /**
     * @return Collection<int, array{type: string, type_label: string, title: string, description: string, url: string, image: string|null}>
     */
    private function searchPages(string $needle): Collection
    {
        $pages = [
            [
                'title' => 'About Us',
                'description' => 'Learn about Tanzania Safaris and Trekking, our local guides, and how we craft safari and Kilimanjaro journeys.',
                'url' => route('about'),
                'keywords' => 'about company story mission vision team arusha',
            ],
            [
                'title' => 'Contact Us',
                'description' => 'Get in touch to plan your Tanzania safari, Kilimanjaro trek, or day trip.',
                'url' => route('contact'),
                'keywords' => 'contact email phone reach out enquire inquiry',
            ],
            [
                'title' => 'Book Now',
                'description' => 'Request a booking for a safari package, Kilimanjaro trek, or custom Tanzania adventure.',
                'url' => route('book-now'),
                'keywords' => 'book booking reserve reservation request trip',
            ],
            [
                'title' => 'Safari Packages',
                'description' => 'Browse wildlife safari packages across Serengeti, Ngorongoro, Tarangire, and more.',
                'url' => route('packages.safari'),
                'keywords' => 'safari wildlife game drive serengeti ngorongoro tarangire',
            ],
            [
                'title' => 'Kilimanjaro Treks',
                'description' => 'Explore Kilimanjaro trek packages on Machame, Lemosho, Marangu, and other routes.',
                'url' => route('packages.trekking'),
                'keywords' => 'kilimanjaro trek climbing hike mountain route',
            ],
            [
                'title' => 'Day Trips',
                'description' => 'Discover short Tanzania day adventures for wildlife, waterfalls, and local culture.',
                'url' => route('packages.day-trips'),
                'keywords' => 'day trip excursion short adventure',
            ],
            [
                'title' => 'Destinations',
                'description' => 'Explore must-see Tanzania destinations across the Northern Circuit and beyond.',
                'url' => route('destinations.index'),
                'keywords' => 'destinations places parks moments',
            ],
            [
                'title' => 'Blog',
                'description' => 'Read safari tips, Kilimanjaro advice, and destination stories from our team.',
                'url' => route('blog'),
                'keywords' => 'blog news stories guides tips articles',
            ],
            [
                'title' => 'FAQs',
                'description' => 'Answers to common questions about booking, travel, and Tanzania adventures.',
                'url' => route('faq'),
                'keywords' => 'faq questions answers help',
            ],
        ];

        return collect($pages)
            ->filter(function (array $page) use ($needle): bool {
                $haystack = Str::lower($page['title'].' '.$page['description'].' '.$page['keywords']);

                return Str::contains($haystack, $needle);
            })
            ->map(function (array $page): array {
                return [
                    'type' => 'page',
                    'type_label' => 'Page',
                    'title' => $page['title'],
                    'description' => Str::limit($page['description'], 120),
                    'url' => $page['url'],
                    'image' => null,
                ];
            })
            ->values();
    }
}
