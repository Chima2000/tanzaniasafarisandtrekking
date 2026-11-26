<?php

use App\Support\TourCatalog;

it('lists tour packages using the aventour packages template', function () {
    $response = $this->get(route('packages.index'));

    $response->assertOk();
    $response->assertSee('Tour Packages', false);
    $response->assertSee('aria-label="Breadcrumb"', false);
    $response->assertSee('package-section', false);
    $response->assertSee('package-sidebar', false);
    $response->assertSee('/photos/', false);
});

it('filters safari packages from the landing navigation', function () {
    $response = $this->get(route('packages.index', ['category' => 'Safari']));

    $response->assertOk();
    $response->assertSee('Safari Packages', false);
    $response->assertSee('Tarangire', false);
});

it('renders experiences and destinations sidebar filters with template markup', function () {
    $response = $this->get(route('packages.index', ['category' => 'Day Trip']));

    $response->assertOk();
    $response->assertSee('category-widget', false);
    $response->assertSee('destinations-widget', false);
    $response->assertSee('Experiences', false);
    $response->assertSee('Destinations', false);
    $response->assertSee('Day Trip (', false);
    $response->assertSee('class="active"', false);
    $response->assertDontSee('duration-widget', false);
});

it('shows a single package with the tour-details template sections', function () {
    $package = app(TourCatalog::class)->packages()->first();

    expect($package)->not->toBeNull();

    $response = $this->get(route('packages.show', $package['slug']));

    $response->assertOk();
    $response->assertSee($package['name'], false);
    $response->assertSee('Tour Packages', false);
    $response->assertDontSee('Single Package', false);
    $response->assertSee('tour-details', false);
    $response->assertSee('tour-scroll-spy', false);
    $response->assertSee('Overview', false);
    $response->assertSee('Itinerary', false);
    $response->assertSee('Includes &amp; Excludes', false);
    $response->assertSee('Tour Itinerary', false);
    $response->assertSee('itinerary-day-by-day', false);
    $response->assertSee('itinerary-day__image', false);
    $response->assertSee('Tour Gallery', false);
    $response->assertSee('single-item-carousel', false);
    $response->assertSee('Enquire About This Tour', false);
    $response->assertSee('has-tour-sticky', false);
    $response->assertSee('tour-gallery', false);
});

it('returns not found for an unknown package slug', function () {
    $this->get(route('packages.show', 'missing-package-slug'))
        ->assertNotFound();
});
