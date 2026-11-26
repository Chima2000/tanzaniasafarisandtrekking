<?php

use App\Support\TourCatalog;

it('lists tour packages using the aventour packages template', function () {
    $response = $this->get(route('packages.index'));

    $response->assertOk();
    $response->assertSee('Tour Packages', false);
    $response->assertSee('package-category-intro', false);
    $response->assertSee('Tanzania Tour Packages for Wildlife, Kilimanjaro &amp; Short Excursions', false);
    $response->assertSee('aria-label="Breadcrumb"', false);
    $response->assertSee('package-section', false);
    $response->assertSee('package-sidebar', false);
    $response->assertSee('/photos/', false);
});

it('serves an independent safari packages page', function () {
    $response = $this->get(route('packages.safari'));

    $response->assertOk();
    $response->assertSee('Safari Packages', false);
    $response->assertSee('Tarangire', false);
    $response->assertSee('package-category-intro', false);
    $response->assertSee('Tanzania Safari Packages Across Serengeti, Ngorongoro &amp; Tarangire', false);
    $response->assertSee('Classic park combinations', false);
    $response->assertSee('Tanzania Safari Packages | Serengeti, Ngorongoro & Tarangire');
    $response->assertSee('rel="canonical"', false);
    $response->assertSee(route('packages.safari', absolute: false), false);
});

it('serves an independent kilimanjaro treks page', function () {
    $response = $this->get(route('packages.trekking'));

    $response->assertOk();
    $response->assertSee('Kilimanjaro Treks', false);
    $response->assertSee('Machame', false);
    $response->assertSee('package-category-intro', false);
    $response->assertSee('Kilimanjaro Trek Packages on Machame, Lemosho, Marangu &amp; More', false);
    $response->assertSee('Route by route clarity', false);
    $response->assertSee('Kilimanjaro Trek Packages | Machame, Lemosho, Marangu & More');
});

it('serves an independent day trips page with sidebar filters', function () {
    $response = $this->get(route('packages.day-trips'));

    $response->assertOk();
    $response->assertSee('category-widget', false);
    $response->assertSee('destinations-widget', false);
    $response->assertSee('Experiences', false);
    $response->assertSee('Destinations', false);
    $response->assertSee('Day Adventures', false);
    $response->assertSee('Tanzania Day Trips for Wildlife, Waterfalls &amp; Hot Springs', false);
    $response->assertSee('Day Trip (', false);
    $response->assertSee('class="active"', false);
    $response->assertDontSee('duration-widget', false);
});

it('permanently redirects legacy category query urls to dedicated pages', function () {
    $this->get('/packages?category=Safari')
        ->assertRedirect(route('packages.safari'))
        ->assertStatus(301);

    $this->get('/packages?category=Trekking')
        ->assertRedirect(route('packages.trekking'))
        ->assertStatus(301);

    $this->get('/packages?category=Day%20Trip')
        ->assertRedirect(route('packages.day-trips'))
        ->assertStatus(301);
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

it('shows real day titles and full itinerary copy for trekking packages', function () {
    $response = $this->get(route('packages.show', '7-days-machame-route-kilimanjaro'));

    $response->assertOk();
    $response->assertSee('Machame Gate to Machame Camp', false);
    $response->assertSee('Barranco Camp to Karanga Camp', false);
    $response->assertSee('Barafu Camp to Uhuru Peak to Mweka Camp', false);
    $response->assertDontSee('Acclimatization Trek', false);
    $response->assertDontSee('Wildlife Exploration', false);
    $response->assertSee('Climb the Barranco Wall', false);
});

it('shows park-specific safari day titles instead of generic wildlife labels', function () {
    $response = $this->get(route('packages.show', '5-days-lodge-safari-tarangire-serengeti-and-ngorongoro'));

    $response->assertOk();
    $response->assertSee('Arusha to Tarangire National Park', false);
    $response->assertSee('Full day in Serengeti National Park', false);
    $response->assertSee('Ngorongoro Crater to Arusha', false);
    $response->assertDontSee('Wildlife Exploration', false);
    $response->assertDontSee('Arrival &amp; First Game Drive', false);
});
