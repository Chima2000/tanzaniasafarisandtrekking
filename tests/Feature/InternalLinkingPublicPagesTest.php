<?php

use App\Support\InternalLinker;

beforeEach(function () {
    InternalLinker::flush();
});

it('auto links destination keywords on the about page with brand link markup', function () {
    $response = $this->get(route('about'));

    $response->assertOk();
    $response->assertSee('class="internal-link"', false);
    $response->assertSee('href="'.route('destinations.show', 'serengeti').'"', false);
    $response->assertSee('href="'.route('destinations.show', 'tarangire').'"', false);
    $response->assertSee('href="'.route('destinations.show', 'ngorongoro').'"', false);
    $response->assertSee('href="'.route('destinations.show', 'kilimanjaro').'"', false);
});

it('cross links related destinations from a destination guide', function () {
    $response = $this->get(route('destinations.show', 'serengeti'));

    $response->assertOk();
    $response->assertSee('class="internal-link"', false);
    $response->assertSee('href="'.route('destinations.show', 'ngorongoro').'"', false);
    $response->assertSee('href="'.route('destinations.show', 'tarangire').'"', false);
    $response->assertDontSee('<a class="internal-link" href="'.route('destinations.show', 'serengeti').'">', false);
});

it('links destination keywords from trekking category intro copy', function () {
    $response = $this->get(route('packages.trekking'));

    $response->assertOk();
    $response->assertSee('class="internal-link"', false);
    $response->assertSee('href="'.route('destinations.show', 'kilimanjaro').'"', false);
});

it('publishes brand colored internal link styles', function () {
    $css = file_get_contents(public_path('aventour/assets/css/brand.css'));

    expect($css)
        ->toContain('a.internal-link')
        ->toContain('.has-internal-links a')
        ->toContain('font-weight: 700')
        ->toContain('text-decoration: underline')
        ->toContain('var(--color-primary)');
});
