<?php

namespace App\Support;

use App\Models\BlogPost;
use App\Models\Destination;
use App\Models\Faq;
use App\Models\Package;
use App\Models\Page;
use Carbon\Carbon;

class CatalogSync
{
    public function __construct(private TourCatalog $catalog) {}

    public function syncAll(): void
    {
        $this->syncPackages();
        $this->syncDestinations();
        $this->syncBlogPosts();
        $this->syncPages();
        $this->syncFaqs();
    }

    public function syncPackages(): int
    {
        $count = 0;

        foreach ($this->catalog->packagesFromFilesystem() as $index => $package) {
            $enriched = $this->catalog->enrichPackage($package);
            $existing = Package::query()->where('slug', $enriched['slug'])->first();
            $defaults = PackagePricing::defaultsFor($enriched);

            $attributes = [
                'name' => $enriched['name'],
                'location' => $enriched['location'],
                'duration' => $enriched['duration'],
                'category' => $enriched['category'],
                'rating' => $enriched['rating'],
                'image' => $enriched['image'],
                'images' => $enriched['images'],
                'photo_count' => $enriched['photo_count'],
                'overview' => $enriched['overview'] ?? null,
                'itinerary' => $enriched['itinerary'] ?? [],
                'includes' => $enriched['includes'] ?? [],
                'excludes' => $enriched['excludes'] ?? [],
                'is_published' => true,
                'is_featured' => $index < 6,
                'sort_order' => $index + 1,
            ];

            if ($existing === null || $existing->cost_per_person === null) {
                $attributes['cost_per_person'] = $defaults['cost_per_person'];
            }

            if ($existing === null || $existing->profit_per_person === null) {
                $attributes['profit_per_person'] = $defaults['profit_per_person'];
            }

            Package::query()->updateOrCreate(
                ['slug' => $enriched['slug']],
                $attributes
            );

            $count++;
        }

        return $count;
    }

    public function syncDestinations(): int
    {
        $count = 0;

        foreach ($this->catalog->destinationsFromDefinitions() as $index => $destination) {
            Destination::query()->updateOrCreate(
                ['slug' => $destination['slug']],
                [
                    'short' => $destination['short'] ?? null,
                    'title' => $destination['title'],
                    'full_name' => $destination['full_name'] ?? $destination['title'],
                    'region' => $destination['region'] ?? 'Tanzania',
                    'needles' => $destination['needles'] ?? [$destination['title']],
                    'starts' => $destination['starts'] ?? null,
                    'seo_title' => $destination['seo_title'] ?? null,
                    'seo_description' => $destination['seo_description'] ?? null,
                    'seo_keywords' => $destination['seo_keywords'] ?? null,
                    'headline' => $destination['headline'] ?? null,
                    'description' => $destination['description'] ?? null,
                    'story_intro' => $destination['story_intro'] ?? null,
                    'story_chapters' => $destination['story_chapters'] ?? [],
                    'highlights' => $destination['highlights'] ?? [],
                    'wildlife' => $destination['wildlife'] ?? [],
                    'best_time' => $destination['best_time'] ?? null,
                    'getting_there' => $destination['getting_there'] ?? null,
                    'tips' => $destination['tips'] ?? [],
                    'facts' => $destination['facts'] ?? [],
                    'faqs' => $destination['faqs'] ?? [],
                    'image' => $destination['image'] ?? null,
                    'is_published' => true,
                    'sort_order' => $index + 1,
                ]
            );

            $count++;
        }

        return $count;
    }

    public function syncBlogPosts(): int
    {
        $count = 0;

        foreach ($this->catalog->postsFromDefinitions() as $post) {
            $publishedAt = null;
            if (! empty($post['date'])) {
                try {
                    $publishedAt = Carbon::parse($post['date']);
                } catch (\Throwable) {
                    $publishedAt = now()->subDays($count + 1);
                }
            }

            BlogPost::query()->updateOrCreate(
                ['slug' => $post['slug']],
                [
                    'title' => $post['title'],
                    'category' => $post['category'] ?? null,
                    'author' => $post['author'] ?? 'Admin',
                    'image' => $post['image'] ?? null,
                    'seo_title' => $post['seo_title'] ?? $post['title'],
                    'seo_description' => $post['seo_description'] ?? null,
                    'intro' => $post['intro'] ?? null,
                    'body' => null,
                    'quote' => $post['quote'] ?? null,
                    'sections' => $post['sections'] ?? [],
                    'tips' => $post['tips'] ?? [],
                    'tags' => $post['tags'] ?? [],
                    'cta_label' => $post['cta_label'] ?? 'Plan Your Trip',
                    'cta_url_name' => $post['cta_url_name'] ?? null,
                    'cta_url_param' => $post['cta_url_param'] ?? null,
                    'cta_query' => $post['cta_query'] ?? null,
                    'is_published' => true,
                    'published_at' => $publishedAt ?? now()->subDays($count + 1),
                ]
            );

            $count++;
        }

        return $count;
    }

    public function syncPages(): void
    {
        $defaults = app(PageBlueprint::class)->seedDefaults();

        foreach ($defaults as $slug => $page) {
            $existing = Page::query()->where('slug', $slug)->first();

            if ($existing === null) {
                Page::query()->create([
                    'slug' => $slug,
                    'title' => $page['title'],
                    'seo_title' => $page['seo_title'],
                    'seo_description' => $page['seo_description'],
                    'content' => $page['content'],
                    'is_published' => true,
                ]);

                continue;
            }

            $mergedContent = array_merge($page['content'], (array) $existing->content);

            if ($slug === 'home' && empty($existing->content['hero_slides'])) {
                $mergedContent['hero_slides'] = app(HomeHeroSlides::class)->resolve((array) $existing->content);
            }

            $existing->fill([
                'title' => $existing->title ?: $page['title'],
                'seo_title' => $existing->seo_title ?: $page['seo_title'],
                'seo_description' => $existing->seo_description ?: $page['seo_description'],
                'content' => $mergedContent,
                'is_published' => $existing->is_published ?? true,
            ])->save();
        }
    }

    public function syncFaqs(): void
    {
        $items = [
            [
                'question' => 'How do I book a tour?',
                'answer' => 'Browse our safari and Kilimanjaro packages, open the tour you like, and send an enquiry. Our team will confirm dates, group size, and a tailored quote.',
            ],
            [
                'question' => 'Can I customize my itinerary?',
                'answer' => 'Yes. Most Northern Circuit safaris and Kilimanjaro routes can be adjusted for pace, lodging style, and add-on destinations.',
            ],
            [
                'question' => 'Are flights included?',
                'answer' => 'International flights are not included. Airport transfers and domestic flight advice can be arranged on request.',
            ],
            [
                'question' => 'How many people are in a group tour?',
                'answer' => 'Private departures are available for couples and families. Shared options depend on the season and selected package—ask us when you enquire.',
            ],
            [
                'question' => 'When is the best time to visit Tanzania?',
                'answer' => 'Dry seasons favor wildlife viewing, while migration timing varies by month. Kilimanjaro can be climbed year-round with preferred windows in the drier months. We’ll recommend dates for your goals.',
            ],
        ];

        foreach ($items as $index => $item) {
            Faq::query()->updateOrCreate(
                ['question' => $item['question']],
                [
                    'answer' => $item['answer'],
                    'sort_order' => $index + 1,
                    'is_published' => true,
                ]
            );
        }
    }
}
