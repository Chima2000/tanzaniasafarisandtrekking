<?php

use App\Models\Faq;
use App\Models\Package;
use App\Models\Page;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('stores and renders rich text on managed pages', function () {
    $admin = User::factory()->admin()->create();
    $page = Page::query()->create([
        'slug' => 'about',
        'title' => 'About Us',
        'content' => [],
        'is_published' => true,
    ]);

    $this->actingAs($admin)
        ->put(route('admin.pages.update', $page), [
            'title' => 'About Us',
            'seo_title' => 'About',
            'seo_description' => 'About us',
            'is_published' => true,
            'content' => [
                'heading' => 'Our Story',
                'body' => '<p>We craft <strong>safaris</strong> and climbs.</p><p><img src="/photos/serengeti.jpg" alt="Serengeti"></p>',
                'body_extra' => '<p>Second <em>paragraph</em>.</p>',
            ],
        ])
        ->assertRedirect(route('admin.pages.edit', $page));

    $content = $page->fresh()->content;

    expect($content['body'])
        ->toContain('<strong>safaris</strong>')
        ->toContain('/photos/serengeti.jpg')
        ->and($content['body_extra'])->toContain('<em>paragraph</em>');

    $this->get(route('about'))
        ->assertOk()
        ->assertSee('<strong>safaris</strong>', false)
        ->assertSee('src="/photos/serengeti.jpg"', false)
        ->assertSee('<em>paragraph</em>', false);
});

it('stores rich package overview and itinerary descriptions', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->post(route('admin.packages.store'), [
            'name' => 'Rich Safari',
            'slug' => 'rich-safari',
            'location' => 'Serengeti',
            'duration' => '3 Days',
            'category' => 'Safari',
            'rating' => '5.0',
            'image' => '/photos/demo.jpg',
            'images_text' => '/photos/demo.jpg',
            'overview' => '<p>A <strong>bold</strong> overview.</p>',
            'itinerary' => [
                [
                    'day' => 'Day 1',
                    'title' => 'Arrival',
                    'description' => '<p>Meet your <em>guide</em>.</p>',
                    'meals' => 'Dinner',
                    'destinations' => 'Arusha',
                ],
            ],
            'includes_text' => 'Guide',
            'excludes_text' => 'Flights',
            'is_published' => true,
            'sort_order' => 1,
        ])
        ->assertRedirect();

    $package = Package::query()->where('slug', 'rich-safari')->first();

    expect($package)->not->toBeNull()
        ->and($package->overview)->toContain('<strong>bold</strong>')
        ->and($package->itinerary[0]['description'])->toContain('<em>guide</em>');

    $this->get(route('packages.show', 'rich-safari'))
        ->assertOk()
        ->assertSee('<strong>bold</strong>', false)
        ->assertSee('<em>guide</em>', false);
});

it('stores rich faq answers for the public faq page', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->post(route('admin.faqs.store'), [
            'question' => 'Can I bring a camera?',
            'answer' => '<p>Yes — <strong>bring extra batteries</strong>.</p>',
            'sort_order' => 1,
            'is_published' => true,
        ])
        ->assertRedirect();

    expect(Faq::query()->where('question', 'Can I bring a camera?')->value('answer'))
        ->toContain('<strong>bring extra batteries</strong>');

    $this->get(route('faq'))
        ->assertOk()
        ->assertSee('<strong>bring extra batteries</strong>', false);
});
