<?php

namespace App\Support;

class PageBlueprint
{
    /**
     * @return array<string, array{
     *     title: string,
     *     public_route: string|null,
     *     public_route_params?: array<string, mixed>,
     *     sections: list<array{id: string, label: string, fields: list<array{key: string, label: string, type: string, rows?: int, hint?: string}>}>
     * }>
     */
    public function all(): array
    {
        return [
            'home' => [
                'title' => 'Home',
                'public_route' => 'home',
                'sections' => [
                    [
                        'id' => 'hero-slideshow',
                        'label' => 'Hero slideshow',
                        'fields' => [
                            [
                                'key' => 'hero_slides',
                                'label' => 'Slides',
                                'type' => 'hero_slides',
                                'hint' => 'Add image or video slides. Changes autosave and refresh the live preview instantly.',
                            ],
                        ],
                    ],
                    [
                        'id' => 'about',
                        'label' => 'About section',
                        'fields' => [
                            ['key' => 'about_subtitle', 'label' => 'Subtitle', 'type' => 'text'],
                            ['key' => 'about_heading', 'label' => 'Heading', 'type' => 'text'],
                            ['key' => 'about_body', 'label' => 'Body', 'type' => 'richtext', 'rows' => 4],
                            ['key' => 'about_cta', 'label' => 'Button label', 'type' => 'text'],
                            ['key' => 'about_feature_one_title', 'label' => 'Feature 1 title', 'type' => 'text'],
                            ['key' => 'about_feature_one_body', 'label' => 'Feature 1 body', 'type' => 'richtext', 'rows' => 2],
                            ['key' => 'about_feature_two_title', 'label' => 'Feature 2 title', 'type' => 'text'],
                            ['key' => 'about_feature_two_body', 'label' => 'Feature 2 body', 'type' => 'richtext', 'rows' => 2],
                            ['key' => 'about_image_1', 'label' => 'Image 1', 'type' => 'image'],
                            ['key' => 'about_image_1_position', 'label' => 'Image 1 position', 'type' => 'position'],
                            ['key' => 'about_image_2', 'label' => 'Image 2', 'type' => 'image'],
                            ['key' => 'about_image_2_position', 'label' => 'Image 2 position', 'type' => 'position'],
                            ['key' => 'about_image_3', 'label' => 'Video box image', 'type' => 'image'],
                            ['key' => 'about_image_3_position', 'label' => 'Video box position', 'type' => 'position'],
                            ['key' => 'about_image_4', 'label' => 'Side image', 'type' => 'image'],
                            ['key' => 'about_image_4_position', 'label' => 'Side image position', 'type' => 'position'],
                        ],
                    ],
                    [
                        'id' => 'categories',
                        'label' => 'Travel styles',
                        'fields' => [
                            ['key' => 'categories_subtitle', 'label' => 'Subtitle', 'type' => 'text'],
                            ['key' => 'categories_heading', 'label' => 'Heading', 'type' => 'text'],
                            ['key' => 'categories_body', 'label' => 'Body', 'type' => 'richtext', 'rows' => 2],
                            ['key' => 'categories_cta', 'label' => 'Link label', 'type' => 'text'],
                            ['key' => 'categories_bg', 'label' => 'Background image', 'type' => 'image'],
                            ['key' => 'categories_bg_position', 'label' => 'Background position', 'type' => 'position'],
                        ],
                    ],
                    [
                        'id' => 'destinations',
                        'label' => 'Destinations section',
                        'fields' => [
                            ['key' => 'destinations_subtitle', 'label' => 'Subtitle', 'type' => 'text'],
                            ['key' => 'destinations_heading', 'label' => 'Heading', 'type' => 'text'],
                            ['key' => 'destinations_body', 'label' => 'Body', 'type' => 'richtext', 'rows' => 2],
                        ],
                    ],
                    [
                        'id' => 'journeys',
                        'label' => 'Journeys section',
                        'fields' => [
                            ['key' => 'journeys_subtitle', 'label' => 'Subtitle', 'type' => 'text'],
                            ['key' => 'journeys_heading', 'label' => 'Heading', 'type' => 'richtext', 'rows' => 2],
                            ['key' => 'journeys_body', 'label' => 'Intro body', 'type' => 'richtext', 'rows' => 3],
                            ['key' => 'journeys_card_one_title', 'label' => 'Card 1 title', 'type' => 'text'],
                            ['key' => 'journeys_card_one_body', 'label' => 'Card 1 body', 'type' => 'richtext', 'rows' => 2],
                            ['key' => 'journeys_card_two_title', 'label' => 'Card 2 title', 'type' => 'text'],
                            ['key' => 'journeys_card_two_body', 'label' => 'Card 2 body', 'type' => 'richtext', 'rows' => 2],
                            ['key' => 'journeys_image_1', 'label' => 'Image 1', 'type' => 'image'],
                            ['key' => 'journeys_image_1_position', 'label' => 'Image 1 position', 'type' => 'position'],
                            ['key' => 'journeys_image_2', 'label' => 'Image 2', 'type' => 'image'],
                            ['key' => 'journeys_image_2_position', 'label' => 'Image 2 position', 'type' => 'position'],
                            ['key' => 'journeys_image_3', 'label' => 'Image 3', 'type' => 'image'],
                            ['key' => 'journeys_image_3_position', 'label' => 'Image 3 position', 'type' => 'position'],
                        ],
                    ],
                    [
                        'id' => 'safari-packages',
                        'label' => 'Safari packages block',
                        'fields' => [
                            ['key' => 'safari_subtitle', 'label' => 'Subtitle', 'type' => 'text'],
                            ['key' => 'safari_heading', 'label' => 'Heading', 'type' => 'text'],
                            ['key' => 'safari_body', 'label' => 'Body', 'type' => 'richtext', 'rows' => 2],
                            ['key' => 'safari_footer', 'label' => 'Footer text', 'type' => 'text'],
                            ['key' => 'safari_cta', 'label' => 'Link label', 'type' => 'text'],
                        ],
                    ],
                    [
                        'id' => 'trek-packages',
                        'label' => 'Kilimanjaro packages block',
                        'fields' => [
                            ['key' => 'trek_subtitle', 'label' => 'Subtitle', 'type' => 'text'],
                            ['key' => 'trek_heading', 'label' => 'Heading', 'type' => 'text'],
                            ['key' => 'trek_body', 'label' => 'Body', 'type' => 'richtext', 'rows' => 2],
                            ['key' => 'trek_footer', 'label' => 'Footer text', 'type' => 'text'],
                            ['key' => 'trek_cta', 'label' => 'Link label', 'type' => 'text'],
                        ],
                    ],
                    [
                        'id' => 'chooseus',
                        'label' => 'Why choose us',
                        'fields' => [
                            ['key' => 'chooseus_subtitle', 'label' => 'Subtitle', 'type' => 'text'],
                            ['key' => 'chooseus_heading', 'label' => 'Heading', 'type' => 'text'],
                            ['key' => 'chooseus_body', 'label' => 'Body', 'type' => 'richtext', 'rows' => 2],
                            ['key' => 'chooseus_item_1_title', 'label' => 'Item 1 title', 'type' => 'text'],
                            ['key' => 'chooseus_item_1_body', 'label' => 'Item 1 body', 'type' => 'richtext', 'rows' => 2],
                            ['key' => 'chooseus_item_2_title', 'label' => 'Item 2 title', 'type' => 'text'],
                            ['key' => 'chooseus_item_2_body', 'label' => 'Item 2 body', 'type' => 'richtext', 'rows' => 2],
                            ['key' => 'chooseus_item_3_title', 'label' => 'Item 3 title', 'type' => 'text'],
                            ['key' => 'chooseus_item_3_body', 'label' => 'Item 3 body', 'type' => 'richtext', 'rows' => 2],
                            ['key' => 'chooseus_bg', 'label' => 'Background image', 'type' => 'image'],
                            ['key' => 'chooseus_bg_position', 'label' => 'Background position', 'type' => 'position'],
                        ],
                    ],
                    [
                        'id' => 'blog',
                        'label' => 'Blog section',
                        'fields' => [
                            ['key' => 'blog_subtitle', 'label' => 'Subtitle', 'type' => 'text'],
                            ['key' => 'blog_heading', 'label' => 'Heading', 'type' => 'text'],
                            ['key' => 'blog_body', 'label' => 'Body', 'type' => 'richtext', 'rows' => 2],
                            ['key' => 'blog_footer', 'label' => 'Footer text', 'type' => 'text'],
                            ['key' => 'blog_cta', 'label' => 'Link label', 'type' => 'text'],
                        ],
                    ],
                    [
                        'id' => 'testimonials',
                        'label' => 'Testimonials',
                        'fields' => [
                            ['key' => 'testimonials_subtitle', 'label' => 'Subtitle', 'type' => 'text'],
                            ['key' => 'testimonials_heading', 'label' => 'Heading', 'type' => 'text'],
                            ['key' => 'testimonial_1_quote', 'label' => 'Quote 1', 'type' => 'richtext', 'rows' => 3],
                            ['key' => 'testimonial_1_name', 'label' => 'Name 1', 'type' => 'text'],
                            ['key' => 'testimonial_1_location', 'label' => 'Location 1', 'type' => 'text'],
                            ['key' => 'testimonial_1_rating', 'label' => 'Rating 1', 'type' => 'text'],
                            ['key' => 'testimonial_1_image', 'label' => 'Photo 1', 'type' => 'image'],
                            ['key' => 'testimonial_1_image_position', 'label' => 'Photo 1 position', 'type' => 'position'],
                            ['key' => 'testimonial_2_quote', 'label' => 'Quote 2', 'type' => 'richtext', 'rows' => 3],
                            ['key' => 'testimonial_2_name', 'label' => 'Name 2', 'type' => 'text'],
                            ['key' => 'testimonial_2_location', 'label' => 'Location 2', 'type' => 'text'],
                            ['key' => 'testimonial_2_rating', 'label' => 'Rating 2', 'type' => 'text'],
                            ['key' => 'testimonial_2_image', 'label' => 'Photo 2', 'type' => 'image'],
                            ['key' => 'testimonial_2_image_position', 'label' => 'Photo 2 position', 'type' => 'position'],
                            ['key' => 'testimonial_3_quote', 'label' => 'Quote 3', 'type' => 'richtext', 'rows' => 3],
                            ['key' => 'testimonial_3_name', 'label' => 'Name 3', 'type' => 'text'],
                            ['key' => 'testimonial_3_location', 'label' => 'Location 3', 'type' => 'text'],
                            ['key' => 'testimonial_3_rating', 'label' => 'Rating 3', 'type' => 'text'],
                            ['key' => 'testimonial_3_image', 'label' => 'Photo 3', 'type' => 'image'],
                            ['key' => 'testimonial_3_image_position', 'label' => 'Photo 3 position', 'type' => 'position'],
                            ['key' => 'testimonial_bg', 'label' => 'Side image', 'type' => 'image'],
                            ['key' => 'testimonial_bg_position', 'label' => 'Side image position', 'type' => 'position'],
                        ],
                    ],
                    [
                        'id' => 'faq',
                        'label' => 'FAQ teaser',
                        'fields' => [
                            ['key' => 'faq_subtitle', 'label' => 'Subtitle', 'type' => 'text'],
                            ['key' => 'faq_heading', 'label' => 'Heading', 'type' => 'text'],
                            ['key' => 'faq_body', 'label' => 'Body', 'type' => 'richtext', 'rows' => 2],
                            ['key' => 'faq_sidebar_heading', 'label' => 'Sidebar heading', 'type' => 'text'],
                            ['key' => 'faq_sidebar_body', 'label' => 'Sidebar body', 'type' => 'richtext', 'rows' => 2],
                            ['key' => 'faq_cta', 'label' => 'Button label', 'type' => 'text'],
                        ],
                    ],
                    [
                        'id' => 'award',
                        'label' => 'Award banner',
                        'fields' => [
                            ['key' => 'award_heading', 'label' => 'Heading', 'type' => 'text'],
                            ['key' => 'award_body', 'label' => 'Body', 'type' => 'richtext', 'rows' => 2],
                            ['key' => 'award_bg', 'label' => 'Background image', 'type' => 'image'],
                            ['key' => 'award_bg_position', 'label' => 'Background position', 'type' => 'position'],
                        ],
                    ],
                    [
                        'id' => 'platform-reviews',
                        'label' => 'Platform reviews',
                        'fields' => [
                            ['key' => 'reviews_subtitle', 'label' => 'Subtitle', 'type' => 'text'],
                            ['key' => 'reviews_heading', 'label' => 'Heading', 'type' => 'text'],
                            ['key' => 'reviews_body', 'label' => 'Intro', 'type' => 'richtext', 'rows' => 2],
                            ['key' => 'reviews_tripadvisor_json', 'label' => 'TripAdvisor reviews JSON', 'type' => 'textarea', 'rows' => 10, 'hint' => 'JSON array of objects: [{"author":"...","title":"...","quote":"..."}]'],
                            ['key' => 'reviews_google_json', 'label' => 'Google reviews JSON', 'type' => 'textarea', 'rows' => 10, 'hint' => 'JSON array of objects: [{"author":"...","title":"...","quote":"..."}]'],
                        ],
                    ],
                ],
            ],
            'about' => [
                'title' => 'About Us',
                'public_route' => 'about',
                'sections' => [
                    [
                        'id' => 'about-main',
                        'label' => 'Intro',
                        'fields' => [
                            ['key' => 'subtitle', 'label' => 'Subtitle', 'type' => 'text'],
                            ['key' => 'heading', 'label' => 'Heading', 'type' => 'text'],
                            ['key' => 'body', 'label' => 'Body', 'type' => 'richtext', 'rows' => 5],
                            ['key' => 'body_extra', 'label' => 'Body (second paragraph)', 'type' => 'richtext', 'rows' => 3],
                            ['key' => 'feature_one_title', 'label' => 'Feature 1 title', 'type' => 'text'],
                            ['key' => 'feature_one_body', 'label' => 'Feature 1 body', 'type' => 'richtext', 'rows' => 2],
                            ['key' => 'feature_two_title', 'label' => 'Feature 2 title', 'type' => 'text'],
                            ['key' => 'feature_two_body', 'label' => 'Feature 2 body', 'type' => 'richtext', 'rows' => 2],
                            ['key' => 'cta_label', 'label' => 'Button label', 'type' => 'text'],
                            ['key' => 'image_1', 'label' => 'Image 1', 'type' => 'image'],
                            ['key' => 'image_1_position', 'label' => 'Image 1 position', 'type' => 'position'],
                            ['key' => 'image_2', 'label' => 'Image 2', 'type' => 'image'],
                            ['key' => 'image_2_position', 'label' => 'Image 2 position', 'type' => 'position'],
                            ['key' => 'image_3', 'label' => 'Image 3', 'type' => 'image'],
                            ['key' => 'image_3_position', 'label' => 'Image 3 position', 'type' => 'position'],
                            ['key' => 'image_4', 'label' => 'Image 4', 'type' => 'image'],
                            ['key' => 'image_4_position', 'label' => 'Image 4 position', 'type' => 'position'],
                        ],
                    ],
                    [
                        'id' => 'about-mission',
                        'label' => 'Mission & vision',
                        'fields' => [
                            ['key' => 'mission_subtitle', 'label' => 'Section subtitle', 'type' => 'text'],
                            ['key' => 'mission_heading', 'label' => 'Section heading', 'type' => 'text'],
                            ['key' => 'mission_intro', 'label' => 'Section intro', 'type' => 'richtext', 'rows' => 2],
                            ['key' => 'mission_title', 'label' => 'Mission title', 'type' => 'text'],
                            ['key' => 'mission_body', 'label' => 'Mission body', 'type' => 'richtext', 'rows' => 4],
                            ['key' => 'vision_title', 'label' => 'Vision title', 'type' => 'text'],
                            ['key' => 'vision_body', 'label' => 'Vision body', 'type' => 'richtext', 'rows' => 4],
                        ],
                    ],
                    [
                        'id' => 'about-story',
                        'label' => 'Our story',
                        'fields' => [
                            ['key' => 'story_subtitle', 'label' => 'Subtitle', 'type' => 'text'],
                            ['key' => 'story_heading', 'label' => 'Heading', 'type' => 'text'],
                            ['key' => 'story_body_1', 'label' => 'Paragraph 1', 'type' => 'richtext', 'rows' => 3],
                            ['key' => 'story_body_2', 'label' => 'Paragraph 2', 'type' => 'richtext', 'rows' => 3],
                            ['key' => 'story_body_3', 'label' => 'Paragraph 3', 'type' => 'richtext', 'rows' => 3],
                        ],
                    ],
                ],
            ],
            'contact' => [
                'title' => 'Contact Us',
                'public_route' => 'contact',
                'sections' => [
                    [
                        'id' => 'contact-main',
                        'label' => 'Page content',
                        'fields' => [
                            ['key' => 'heading', 'label' => 'Heading', 'type' => 'text'],
                            ['key' => 'body', 'label' => 'Body', 'type' => 'richtext', 'rows' => 4],
                            ['key' => 'form_intro', 'label' => 'Form intro', 'type' => 'richtext', 'rows' => 3],
                        ],
                    ],
                ],
            ],
            'faq' => [
                'title' => "Faq's",
                'public_route' => 'faq',
                'sections' => [
                    [
                        'id' => 'faq-main',
                        'label' => 'Page content',
                        'fields' => [
                            ['key' => 'sidebar_heading', 'label' => 'Sidebar heading', 'type' => 'text'],
                            ['key' => 'sidebar_body', 'label' => 'Sidebar body', 'type' => 'richtext', 'rows' => 3],
                        ],
                    ],
                ],
            ],
            'packages' => [
                'title' => 'Packages',
                'public_route' => 'packages.index',
                'sections' => [
                    [
                        'id' => 'packages-main',
                        'label' => 'Listing chrome',
                        'fields' => [
                            ['key' => 'page_title', 'label' => 'Page title', 'type' => 'text'],
                            ['key' => 'subtitle', 'label' => 'Subtitle', 'type' => 'text'],
                            ['key' => 'intro', 'label' => 'Intro', 'type' => 'richtext', 'rows' => 3],
                        ],
                    ],
                ],
            ],
            'destinations' => [
                'title' => 'Destinations',
                'public_route' => 'destinations.index',
                'sections' => [
                    [
                        'id' => 'destinations-main',
                        'label' => 'Listing chrome',
                        'fields' => [
                            ['key' => 'page_title', 'label' => 'Page title', 'type' => 'text'],
                            ['key' => 'subtitle', 'label' => 'Subtitle', 'type' => 'text'],
                            ['key' => 'intro', 'label' => 'Intro', 'type' => 'richtext', 'rows' => 3],
                        ],
                    ],
                ],
            ],
            'blog' => [
                'title' => 'Blog',
                'public_route' => 'blog',
                'sections' => [
                    [
                        'id' => 'blog-main',
                        'label' => 'Listing chrome',
                        'fields' => [
                            ['key' => 'page_title', 'label' => 'Page title', 'type' => 'text'],
                            ['key' => 'subtitle', 'label' => 'Subtitle', 'type' => 'text'],
                            ['key' => 'intro', 'label' => 'Intro', 'type' => 'richtext', 'rows' => 3],
                        ],
                    ],
                ],
            ],
            'book-now' => [
                'title' => 'Book Now',
                'public_route' => 'book-now',
                'sections' => [
                    [
                        'id' => 'book-now-main',
                        'label' => 'Page content',
                        'fields' => [
                            ['key' => 'page_title', 'label' => 'Page title', 'type' => 'text'],
                            ['key' => 'heading', 'label' => 'Heading', 'type' => 'text'],
                            ['key' => 'body', 'label' => 'Body', 'type' => 'richtext', 'rows' => 4],
                            ['key' => 'form_intro', 'label' => 'Form intro', 'type' => 'richtext', 'rows' => 3],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array{title: string, public_route: string|null, sections: list<array{id: string, label: string, fields: list<array<string, mixed>>}>}|null
     */
    public function for(string $slug): ?array
    {
        return $this->all()[$slug] ?? null;
    }

    /**
     * @return array<string, mixed>
     */
    public function defaultContent(string $slug): array
    {
        $blueprint = $this->for($slug);

        if ($blueprint === null) {
            return [];
        }

        $defaults = $this->seedDefaults()[$slug]['content'] ?? [];
        $content = [];

        foreach ($blueprint['sections'] as $section) {
            foreach ($section['fields'] as $field) {
                $key = $field['key'];
                $type = $field['type'] ?? 'text';

                if ($type === 'hero_slides') {
                    $content[$key] = is_array($defaults[$key] ?? null)
                        ? $defaults[$key]
                        : app(HomeHeroSlides::class)->defaultSlides();

                    continue;
                }

                $content[$key] = (string) ($defaults[$key] ?? ($type === 'position' ? '50% 50%' : ''));
            }
        }

        return $content;
    }

    /**
     * @return array<string, array{title: string, seo_title: string, seo_description: string, content: array<string, string>}>
     */
    public function seedDefaults(): array
    {
        return [
            'home' => [
                'title' => 'Home',
                'seo_title' => 'Tanzania Safaris and Trekking',
                'seo_description' => 'Plan safari and Kilimanjaro adventures across Tanzania with local experts.',
                'content' => [
                    'hero_slides' => (new HomeHeroSlides)->defaultSlides(),
                    'about_subtitle' => 'About Us',
                    'about_heading' => 'Inspired by Tanzania, Designed for Your Journey',
                    'about_body' => 'We craft safari and Kilimanjaro experiences across Serengeti, Ngorongoro, Tarangire, and beyond—personal itineraries with trusted local guides, careful pacing, and authentic wilderness moments.',
                    'about_cta' => 'Learn More',
                    'about_feature_one_title' => 'Curated With Care',
                    'about_feature_one_body' => 'Designed for comfort, quality and unforgettable memories.',
                    'about_feature_two_title' => 'Explore With Ease',
                    'about_feature_two_body' => 'Travel made smooth, simple, and worry-free for you.',
                    'about_image_1' => '',
                    'about_image_1_position' => '50% 50%',
                    'about_image_2' => '',
                    'about_image_2_position' => '50% 50%',
                    'about_image_3' => '',
                    'about_image_3_position' => '50% 50%',
                    'about_image_4' => '',
                    'about_image_4_position' => '50% 50%',
                    'categories_subtitle' => 'Travel Styles',
                    'categories_heading' => 'Choose Your Tanzania Style',
                    'categories_body' => 'Safari, summit, culture — your Tanzania, your way.',
                    'categories_cta' => 'View All Categories',
                    'categories_bg' => '',
                    'categories_bg_position' => '50% 50%',
                    'destinations_subtitle' => 'Popular Destinations',
                    'destinations_heading' => 'Iconic Places in Tanzania',
                    'destinations_body' => 'Explore our most loved safari and trekking experiences.',
                    'journeys_subtitle' => 'Behind the Journey',
                    'journeys_heading' => 'Personal Safaris, Memorable Experiences',
                    'journeys_body' => 'Travel is more than seeing places — it\'s about making memories that last. Whether you dream of wildlife, culture, or Kilimanjaro, our local experts plan your perfect Tanzania trip.',
                    'journeys_card_one_title' => 'Made for Small Groups',
                    'journeys_card_one_body' => 'Explore freely with personalized routes and private service.',
                    'journeys_card_two_title' => 'Private Travel Made Easy',
                    'journeys_card_two_body' => 'Tailored trips that fit your pace, style, and comfort.',
                    'journeys_image_1' => '',
                    'journeys_image_1_position' => '50% 50%',
                    'journeys_image_2' => '',
                    'journeys_image_2_position' => '50% 50%',
                    'journeys_image_3' => '',
                    'journeys_image_3_position' => '50% 50%',
                    'safari_subtitle' => 'Safari Packages',
                    'safari_heading' => 'Wildlife Safari Adventures',
                    'safari_body' => 'Explore Tarangire, Serengeti, Ngorongoro, and more.',
                    'safari_footer' => 'Discover more Tanzania safari journeys.',
                    'safari_cta' => 'View Safari Packages',
                    'trek_subtitle' => 'Kilimanjaro Treks',
                    'trek_heading' => 'Climb Mount Kilimanjaro',
                    'trek_body' => 'Marangu, Machame, Lemosho, Rongai, Umbwe, and Northern Circuit.',
                    'trek_footer' => 'Discover more Kilimanjaro climbing routes.',
                    'trek_cta' => 'View Trek Packages',
                    'chooseus_subtitle' => 'Why Tanzania Safaris and Trekking',
                    'chooseus_heading' => 'Your Trusted Travel Partner',
                    'chooseus_body' => 'Guiding you to unforgettable experiences across the world’s wonders.',
                    'chooseus_item_1_title' => 'Curated Tours',
                    'chooseus_item_1_body' => 'Only the best destinations and experiences, selected just for you.',
                    'chooseus_item_2_title' => 'Guides Near You',
                    'chooseus_item_2_body' => 'Get the best value with fair, transparent pricing and no hidden costs.',
                    'chooseus_item_3_title' => 'Safety Knowledge',
                    'chooseus_item_3_body' => 'Safe and simple payments for a stress-free booking experience.',
                    'chooseus_bg' => '',
                    'chooseus_bg_position' => '50% 50%',
                    'blog_subtitle' => 'Blog Post',
                    'blog_heading' => 'Latest From the Blog',
                    'blog_body' => 'Safari tips, trek advice, and Northern Circuit stories.',
                    'blog_footer' => 'Continue your adventure through our blog.',
                    'blog_cta' => 'All Blog Posts',
                    'testimonials_subtitle' => 'Testimonials',
                    'testimonials_heading' => 'Stories From Travelers',
                    'testimonial_1_quote' => 'This was hands down the best trip of my life! Everything was perfectly organized — from airport pickup to local guides. I felt safe, excited, and totally free.',
                    'testimonial_1_name' => 'Lucas Benjamin',
                    'testimonial_1_location' => 'Pasadena, CA',
                    'testimonial_1_rating' => '4.6 out of 5',
                    'testimonial_1_image' => '',
                    'testimonial_1_image_position' => '50% 50%',
                    'testimonial_2_quote' => 'This was hands down the best trip of my life! Everything was perfectly organized — from airport pickup to local guides. I felt safe, excited, and totally free.',
                    'testimonial_2_name' => 'Jacob Lawrence',
                    'testimonial_2_location' => 'Melbourne, AU',
                    'testimonial_2_rating' => '4.9 out of 5',
                    'testimonial_2_image' => '',
                    'testimonial_2_image_position' => '50% 50%',
                    'testimonial_3_quote' => 'Thanks to Tanzania Safaris and Trekking’s, we discovered hidden gems and enjoyed every moment. Professional, friendly, and reliable, they made unforgettable.',
                    'testimonial_3_name' => 'Jaffer Ahmedt',
                    'testimonial_3_location' => 'Dubai, UAE',
                    'testimonial_3_rating' => '5.0 out of 5',
                    'testimonial_3_image' => '',
                    'testimonial_3_image_position' => '50% 50%',
                    'testimonial_bg' => '',
                    'testimonial_bg_position' => '50% 50%',
                    'faq_subtitle' => 'Faq’s',
                    'faq_heading' => 'Frequently Asked Questions',
                    'faq_body' => 'Travel planning has never been easier.',
                    'faq_sidebar_heading' => 'Let us guide you—reach out anytime.',
                    'faq_sidebar_body' => 'Got more questions? Find answers on our FAQ page',
                    'faq_cta' => 'Read All Faq’s',
                    'award_heading' => 'Recognized for Excellence in Travel',
                    'award_body' => 'Celebrated by travelers and trusted worldwide.',
                    'award_bg' => '',
                    'award_bg_position' => '50% 50%',
                    'reviews_subtitle' => 'Traveler Reviews',
                    'reviews_heading' => 'Rated Excellent on TripAdvisor & Google',
                    'reviews_body' => 'Choose a tab, then swipe through reviews without leaving this page.',
                    'reviews_tripadvisor_json' => '',
                    'reviews_google_json' => '',
                ],
            ],
            'about' => [
                'title' => 'About Us',
                'seo_title' => 'About Us | Tanzania Safaris and Trekking',
                'seo_description' => 'Learn about Tanzania Safaris and Trekking, our local safari and Kilimanjaro team, and how we plan Northern Circuit journeys from Arusha.',
                'content' => [
                    'subtitle' => 'Tanzania Safaris and Trekking',
                    'heading' => 'A Local Safari & Kilimanjaro Company Based in Arusha',
                    'body' => 'Tanzania Safaris and Trekking is a locally run travel company specializing in Northern Circuit wildlife safaris, Mount Kilimanjaro climbs, day trips, and cultural experiences. From our base in Arusha we plan private and small-group itineraries with clear park days, trusted mountain crews, and practical trip support before you arrive.',
                    'body_extra' => 'Whether you are planning a short Ngorongoro day, a classic Serengeti safari, or a multi-day Kilimanjaro route, we keep the process clear: route options, accommodation style, inclusions, and a day-by-day outline before you confirm.',
                    'feature_one_title' => 'Local Planning Desk',
                    'feature_one_body' => 'Itineraries are built around real park logistics, seasonal wildlife movement, and the pace that fits your group.',
                    'feature_two_title' => 'Field Teams You Can Trust',
                    'feature_two_body' => 'Safari guides and Kilimanjaro crews handle the day-to-day details so you can focus on the journey itself.',
                    'cta_label' => 'Explore Packages',
                    'image_1' => '',
                    'image_1_position' => '50% 50%',
                    'image_2' => '',
                    'image_2_position' => '50% 50%',
                    'image_3' => '',
                    'image_3_position' => '50% 50%',
                    'image_4' => '',
                    'image_4_position' => '50% 50%',
                    'mission_subtitle' => 'Mission & Vision',
                    'mission_heading' => 'What Guides Our Work in Tanzania',
                    'mission_intro' => 'We exist to make Tanzania travel practical, well paced, and locally grounded from first enquiry to final drop-off.',
                    'mission_title' => 'Plan Tanzania trips that work on the ground',
                    'mission_body' => 'Our mission is to design safari and Kilimanjaro journeys with honest timing, reliable transport, experienced guides, and inclusions that match what travelers actually need in the parks and on the mountain.',
                    'vision_title' => 'Be a trusted Arusha partner for Africa travel',
                    'vision_body' => 'We aim to be the company travelers return to for Tanzania wildlife, Kilimanjaro, and short regional add-ons because the planning felt straightforward and the field delivery matched the promise.',
                    'story_subtitle' => 'Our Story',
                    'story_heading' => 'Built Around Northern Tanzania Travel',
                    'story_body_1' => 'Tanzania Safaris and Trekking grew from day-to-day work on the Northern Circuit: Tarangire game drives, Serengeti plains routes, Ngorongoro crater mornings, and Kilimanjaro departures from Moshi and Arusha. That field experience shapes how we write packages and advise clients.',
                    'story_body_2' => 'Instead of selling one generic tour style, we help you choose the right format. Lodge or camping safari. Five-day Marangu or eight-day Lemosho. A one-day crater visit or a longer circuit that includes Lake Eyasi. The goal is a trip that fits your dates, fitness, and travel style.',
                    'story_body_3' => 'Every enquiry is handled by people who understand park fees, gate timing, altitude pacing, and seasonal wildlife patterns. That local detail is what turns a brochure idea into a workable Tanzania itinerary.',
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
            'packages' => [
                'title' => 'Packages',
                'seo_title' => 'Tour Packages | Tanzania Safaris and Trekking',
                'seo_description' => 'Browse safari, Kilimanjaro, day trip, and cultural packages across Tanzania.',
                'content' => [
                    'page_title' => 'Tour Packages',
                    'subtitle' => 'Choose Your Adventure',
                    'intro' => 'Safari circuits, Kilimanjaro routes, day trips, and cultural experiences—crafted with local expertise.',
                ],
            ],
            'destinations' => [
                'title' => 'Destinations',
                'seo_title' => 'Destinations | Tanzania Safaris and Trekking',
                'seo_description' => 'Explore iconic Tanzania destinations from Serengeti to Kilimanjaro.',
                'content' => [
                    'page_title' => 'Destinations',
                    'subtitle' => 'Where Adventures Begin',
                    'intro' => 'Discover the parks, peaks, and cultural heartlands that define Tanzania travel.',
                ],
            ],
            'blog' => [
                'title' => 'Blog',
                'seo_title' => 'Blog | Tanzania Safaris and Trekking',
                'seo_description' => 'Safari tips, trek advice, and Northern Circuit stories from Tanzania.',
                'content' => [
                    'page_title' => 'Blog',
                    'subtitle' => 'Stories & Tips',
                    'intro' => 'Practical advice and inspiration for planning your Tanzania safari or Kilimanjaro trek.',
                ],
            ],
            'book-now' => [
                'title' => 'Book Now',
                'seo_title' => 'Book Now | Tanzania Safaris and Trekking',
                'seo_description' => 'Request a safari or Kilimanjaro booking with Tanzania Safaris and Trekking.',
                'content' => [
                    'page_title' => 'Book Now',
                    'heading' => 'Start Your Tanzania Adventure',
                    'body' => 'Tell us about your travel dates, group size, and dream itinerary. Our team will follow up with a tailored plan.',
                    'form_intro' => 'Share a few details and we will confirm availability and next steps.',
                ],
            ],
        ];
    }

    public function publicUrl(string $slug): ?string
    {
        $blueprint = $this->for($slug);

        if ($blueprint === null || empty($blueprint['public_route'])) {
            return null;
        }

        return route($blueprint['public_route'], $blueprint['public_route_params'] ?? []);
    }
}
