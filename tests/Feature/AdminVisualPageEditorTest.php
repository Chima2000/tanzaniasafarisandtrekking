<?php

use App\Models\Page;
use App\Models\User;
use App\Support\CatalogSync;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

it('seeds every client page for the visual editor', function () {
    app(CatalogSync::class)->syncPages();

    expect(Page::query()->pluck('slug')->sort()->values()->all())->toBe([
        'about',
        'blog',
        'book-now',
        'contact',
        'destinations',
        'faq',
        'home',
        'packages',
    ]);
});

it('lets an admin edit homepage hero slides with live preview and see them on the site', function () {
    app(CatalogSync::class)->syncPages();

    $admin = User::factory()->admin()->create();
    $page = Page::query()->where('slug', 'home')->firstOrFail();

    $this->actingAs($admin)
        ->get(route('admin.pages.edit', $page))
        ->assertOk()
        ->assertSee('Visual page editor', false)
        ->assertSee('Live preview', false)
        ->assertSee('cms-preview-frame', false)
        ->assertSee('Hero slideshow', false)
        ->assertSee('Add slide', false)
        ->assertSee('content[hero_slides][0][title]', false);

    $this->actingAs($admin)
        ->put(route('admin.pages.update', $page), [
            'title' => 'Home',
            'seo_title' => 'Edited Home SEO',
            'seo_description' => 'Edited home description',
            'is_published' => true,
            'content' => [
                'hero_slides' => [
                    [
                        'type' => 'image',
                        'media' => '/photos/hero.jpg',
                        'poster' => '',
                        'position' => '20% 80%',
                        'kicker' => 'Hello Safari',
                        'title' => 'Edited Hero Title Live',
                        'body' => 'Edited hero body for live preview.',
                        'cta' => 'Explore Now',
                        'cta_url' => '/safari-packages',
                        'badge_label' => 'Adventures start from',
                        'badge_value' => '12+',
                        'badge_text' => 'Packages',
                    ],
                    [
                        'type' => 'video',
                        'media' => '/storage/uploads/hero.mp4',
                        'poster' => '/photos/hero-poster.jpg',
                        'position' => '50% 50%',
                        'kicker' => 'Watch Tanzania',
                        'title' => 'Video Hero Slide',
                        'body' => 'A dynamic video slide for the homepage.',
                        'cta' => 'Plan Trip',
                        'cta_url' => '/book-now',
                        'badge_label' => 'Adventures start from',
                        'badge_value' => 'Live',
                        'badge_text' => 'Film',
                    ],
                ],
                'about_heading' => 'Edited About Heading',
            ],
        ], [
            'Accept' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
        ])
        ->assertOk()
        ->assertJson(['ok' => true]);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('Edited Hero Title Live', false)
        ->assertSee('Video Hero Slide', false)
        ->assertSee('banner-video', false)
        ->assertSee('/storage/uploads/hero.mp4', false)
        ->assertSee('Edited About Heading', false)
        ->assertSee('background-position: 20% 80%', false);
});

it('uploads images and videos for the admin media library endpoint', function () {
    Storage::fake('public');

    $admin = User::factory()->admin()->create();
    $image = UploadedFile::fake()->image('hero.jpg', 800, 600);

    $imageResponse = $this->actingAs($admin)
        ->postJson(route('admin.media.upload'), [
            'file' => $image,
        ])
        ->assertOk()
        ->assertJsonStructure(['url', 'path']);

    Storage::disk('public')->assertExists($imageResponse->json('path'));

    $video = UploadedFile::fake()->create('hero.mp4', 1024, 'video/mp4');

    $videoResponse = $this->actingAs($admin)
        ->postJson(route('admin.media.upload'), [
            'file' => $video,
        ])
        ->assertOk()
        ->assertJsonStructure(['url', 'path', 'category']);

    expect($videoResponse->json('category'))->toBe('video');
    Storage::disk('public')->assertExists($videoResponse->json('path'));
});
