<?php

use App\Support\JsonLd;
use Tests\TestCase;

uses(TestCase::class);

it('builds a schema graph with organization and website nodes', function () {
    $document = JsonLd::document([
        'siteSettings' => [
            'company_name' => 'Tanzania Safaris and Trekking',
            'tagline' => 'Local safari experts',
            'phone' => '+255 769 079 298',
            'email' => 'info@example.com',
            'address' => 'Arusha, Tanzania',
            'facebook_url' => 'https://facebook.com/example',
        ],
        'pageSeo' => [
            'title' => 'Home | Tanzania Safaris and Trekking',
            'description' => 'Plan your Tanzania safari.',
        ],
        'featuredPackages' => [
            ['name' => 'Serengeti Safari', 'slug' => 'serengeti-safari'],
        ],
    ], 'home');

    expect($document['@context'])->toBe('https://schema.org')
        ->and($document['@graph'])->toBeArray()
        ->and(collect($document['@graph'])->pluck('@type')->all())
        ->toContain('TravelAgency', 'WebSite', 'WebPage', 'BreadcrumbList', 'ItemList');
});

it('encodes json-ld safely for script tags', function () {
    $encoded = JsonLd::encode([
        '@context' => 'https://schema.org',
        '@type' => 'WebPage',
        'name' => 'Safari <Trip> & Tour',
    ]);

    expect($encoded)->toContain('\u003C')
        ->and($encoded)->toContain('\u0026')
        ->and($encoded)->not->toContain('<Trip>');
});
