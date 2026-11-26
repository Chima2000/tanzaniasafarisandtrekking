<?php

use App\Support\TourCatalog;

it('renders the search page with a query', function () {
    $package = app(TourCatalog::class)->packages()->first();

    expect($package)->not->toBeNull();

    $response = $this->get(route('search.index', ['q' => $package['name']]));

    $response->assertOk();
    $response->assertSee('Search results', false);
    $response->assertSee($package['name'], false);
    $response->assertSee(route('packages.show', $package['slug'], absolute: false), false);
});

it('returns live search suggestions as json', function () {
    $package = app(TourCatalog::class)->packages()->first();

    expect($package)->not->toBeNull();

    $needle = str($package['name'])
        ->replaceMatches('/[^A-Za-z]+/', ' ')
        ->trim()
        ->before(' ')
        ->toString();

    if (strlen($needle) < 3) {
        $needle = str($package['name'])->substr(0, 8)->trim()->toString();
    }

    expect(strlen($needle))->toBeGreaterThanOrEqual(2);

    $response = $this->getJson(route('search.suggest', ['q' => $needle]));

    $response->assertOk();
    $response->assertJsonPath('query', $needle);
    $response->assertJsonStructure([
        'query',
        'count',
        'view_all_url',
        'results' => [
            '*' => ['type', 'type_label', 'title', 'description', 'url', 'image'],
        ],
    ]);

    expect($response->json('count'))->toBeGreaterThan(0);
    expect(
        collect($response->json('results'))
            ->contains(fn (array $result): bool => str_contains(strtolower($result['title']), strtolower($needle)))
    )->toBeTrue();
});

it('includes the header search control on the home page', function () {
    $response = $this->get(route('home'));

    $response->assertOk();
    $response->assertSee('data-header-search', false);
    $response->assertSee('Open site search', false);
    $response->assertSee(route('search.suggest', absolute: false), false);
    $response->assertSee('header-search.js', false);
});

it('rejects overly long search queries', function () {
    $this->get(route('search.index', ['q' => str_repeat('a', 121)]))
        ->assertSessionHasErrors('q');

    $this->getJson(route('search.suggest', ['q' => str_repeat('a', 121)]))
        ->assertStatus(422);
});
