<?php

use App\Support\TourCatalog;

it('keeps homepage section, destination, and vibe images unique from each other and package covers', function () {
    $catalog = app(TourCatalog::class);
    $packages = $catalog->packages();
    $homeMedia = $catalog->homeMedia();
    $sectionImages = $homeMedia['sectionImages'];

    expect($packages)->not->toBeEmpty();

    $packageCovers = $packages->pluck('image')->filter()->unique()->values()->all();

    $decorative = collect([
        ...$sectionImages['banner'],
        ...$sectionImages['about'],
        ...$sectionImages['journeys'],
        $sectionImages['categories_bg'],
        $sectionImages['chooseus_bg'],
        $sectionImages['testimonial_bg'],
        ...$sectionImages['testimonials'],
        $sectionImages['award_bg'],
        ...$sectionImages['gallery'],
        ...$homeMedia['destinations']->pluck('image')->all(),
        ...$homeMedia['vibes']->pluck('image')->all(),
    ])->filter()->values();

    expect($decorative->unique()->count())->toBe($decorative->count());

    foreach ($decorative as $image) {
        expect($packageCovers)->not->toContain($image);
    }
});

it('gives each package a unique cover image', function () {
    $covers = app(TourCatalog::class)->packages()->pluck('image')->filter();

    expect($covers->unique()->count())->toBe($covers->count());
});

it('renders the homepage without repeating package covers in the hero banner', function () {
    $catalog = app(TourCatalog::class);
    $packages = $catalog->packages();
    $homeMedia = $catalog->homeMedia();
    $banner = $homeMedia['sectionImages']['banner'];

    $response = $this->get(route('home'));

    $response->assertOk();

    foreach ($banner as $image) {
        $response->assertSee($image, false);
        expect($packages->pluck('image'))->not->toContain($image);
    }
});
