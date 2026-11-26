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

            Package::query()->updateOrCreate(
                ['slug' => $enriched['slug']],
                [
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
                ]
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
        $defaults = [
            'home' => [
                'title' => 'Home',
                'seo_title' => 'Tanzania Safaris and Trekking',
                'seo_description' => 'Plan safari and Kilimanjaro adventures across Tanzania with local experts.',
                'content' => [
                    'hero_kicker' => 'Tanzania Safaris',
                    'hero_title' => 'Wildlife, Kilimanjaro & Day Adventures',
                    'hero_subtitle' => 'Plan unforgettable safaris and treks across Tanzania with local experts.',
                ],
            ],
            'about' => [
                'title' => 'About Us',
                'seo_title' => 'About Us | Tanzania Safaris and Trekking',
                'seo_description' => 'Learn about Tanzania Safaris and Trekking and how we craft safari and Kilimanjaro journeys.',
                'content' => [
                    'subtitle' => 'Behind the Adventure',
                    'heading' => 'Inspired by Tanzania, Designed for Your Journey',
                    'body' => 'We craft safari and Kilimanjaro experiences across Serengeti, Ngorongoro, Tarangire, and beyond—personal itineraries with trusted local guides, careful pacing, and authentic wilderness moments.',
                    'feature_one_title' => 'Curated With Care',
                    'feature_one_body' => 'Designed for comfort, quality and unforgettable memories.',
                    'feature_two_title' => 'Explore With Ease',
                    'feature_two_body' => 'Travel made smooth, simple, and worry-free for you.',
                    'cta_label' => 'Explore Packages',
                ],
            ],
            'contact' => [
                'title' => 'Contact Us',
                'seo_title' => 'Contact Us | Tanzania Safaris and Trekking',
                'seo_description' => 'Contact Tanzania Safaris and Trekking for safari and Kilimanjaro trip planning support.',
                'content' => [
                    'heading' => 'Reach Out to Us',
                    'body' => 'Find answers in our Faq section or contact us directly for more help.',
                    'form_intro' => 'Send a message and our team will reply with tailored trip advice.',
                ],
            ],
            'faq' => [
                'title' => "Faq's",
                'seo_title' => "Faq's | Tanzania Safaris and Trekking",
                'seo_description' => 'Frequently asked questions about Tanzania safaris, Kilimanjaro treks, and booking support.',
                'content' => [
                    'sidebar_heading' => 'Let us guide you—reach out anytime.',
                    'sidebar_body' => 'Browse common questions or contact our team for a custom itinerary.',
                ],
            ],
        ];

        foreach ($defaults as $slug => $page) {
            Page::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    'title' => $page['title'],
                    'seo_title' => $page['seo_title'],
                    'seo_description' => $page['seo_description'],
                    'content' => $page['content'],
                    'is_published' => true,
                ]
            );
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
