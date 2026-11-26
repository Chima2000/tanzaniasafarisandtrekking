<?php

use App\Models\Package;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders default open graph and twitter tags on public pages', function () {
    $response = $this->get(route('about'));

    $response->assertOk();
    $response->assertSee('property="og:title"', false);
    $response->assertSee('property="og:description"', false);
    $response->assertSee('name="twitter:card"', false);
    $response->assertSee('rel="canonical"', false);
    $response->assertSee('geo.region', false);
});

it('uses package seo fields on the package detail page', function () {
    $package = Package::factory()->create([
        'name' => 'Meta Safari Package',
        'slug' => 'meta-safari-package',
        'seo_title' => 'Custom Package SEO Title',
        'seo_description' => 'Custom package meta description for search.',
        'seo_keywords' => 'tanzania safari, serengeti',
        'is_published' => true,
    ]);

    $response = $this->get(route('packages.show', $package->slug));

    $response->assertOk();
    $response->assertSee('<title>Custom Package SEO Title</title>', false);
    $response->assertSee('Custom package meta description for search.', false);
    $response->assertSee('tanzania safari, serengeti', false);
    $response->assertSee('property="og:type" content="product"', false);
});
