<?php

use App\Models\Package;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('includes organization website and webpage json-ld on the home page', function () {
    $response = $this->get(route('home'));

    $response->assertOk();
    $response->assertSee('application/ld+json', false);
    $response->assertSee('TravelAgency', false);
    $response->assertSee('WebSite', false);
    $response->assertSee('WebPage', false);
    $response->assertSee('BreadcrumbList', false);
});

it('includes tourist trip json-ld on package detail pages', function () {
    $package = Package::factory()->create([
        'name' => 'JSON LD Safari',
        'slug' => 'json-ld-safari',
        'seo_title' => 'JSON LD Safari SEO',
        'seo_description' => 'Structured data safari package.',
        'is_published' => true,
    ]);

    $response = $this->get(route('packages.show', $package->slug));

    $response->assertOk();
    $response->assertSee('TouristTrip', false);
    $response->assertSee('JSON LD Safari', false);
    $response->assertSee('Offer', false);
});

it('marks search results as noindex', function () {
    $response = $this->get(route('search.index', ['q' => 'serengeti']));

    $response->assertOk();
    $response->assertSee('noindex, follow', false);
    $response->assertSee('SearchResultsPage', false);
});
