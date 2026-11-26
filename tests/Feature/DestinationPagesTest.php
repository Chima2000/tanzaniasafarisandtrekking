<?php

it('lists destinations using the aventour destinations template', function () {
    $response = $this->get(route('destinations.index'));

    $response->assertOk();
    $response->assertSee('>Destinations</', false);
    $response->assertSee('Tanzania Safari Destinations Guide', false);
    $response->assertSee('destinations-section', false);
    $response->assertSee('popular-section', false);
    $response->assertSee('Serengeti', false);
    $response->assertSee('Kilimanjaro', false);
    $response->assertSee('meta name="description"', false);
    $response->assertSee('application/ld+json', false);
});

it('shows a destination detail page with story mode topics and seo markup', function () {
    $response = $this->get(route('destinations.show', 'serengeti'));

    $response->assertOk();
    $response->assertSee('Serengeti National Park', false);
    $response->assertSee('Serengeti National Park Safari Tours', false);
    $response->assertSee('Destination Story Mode', false);
    $response->assertSee('Where the Plains Tell Their Own Story', false);
    $response->assertSee('Chapter 01', false);
    $response->assertSee('Great Migration', false);
    $response->assertSee('Tours Featuring Serengeti', false);
    $response->assertSee('package-block-one', false);
    $response->assertSee('TouristDestination', false);
    $response->assertSee('FAQPage', false);
    $response->assertSee('When is the best time for a Serengeti safari?', false);
    $response->assertSee('Related Tanzania Destinations', false);
});

it('shows destination-specific details on every destination page', function (string $slug, string $needle) {
    $response = $this->get(route('destinations.show', $slug));

    $response->assertOk();
    $response->assertSee('Destination Story Mode', false);
    $response->assertSee($needle, false);
    $response->assertSee('Key Details for Travelers', false);
    $response->assertSee('Plan With Local Detail', false);
})->with([
    ['serengeti', 'Great Migration'],
    ['ngorongoro', 'Crater'],
    ['kilimanjaro', 'Uhuru Peak'],
    ['tarangire', 'baobab'],
    ['arusha', 'Materuni'],
]);

it('returns not found for an unknown destination', function () {
    $this->get(route('destinations.show', 'atlantis'))
        ->assertNotFound();
});
