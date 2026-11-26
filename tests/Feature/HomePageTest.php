<?php

it('renders the tanzania safaris landing page', function () {
    $response = $this->get(route('home'));

    $response->assertOk();
    $response->assertSee('Tanzania Safaris', false);
    $response->assertSee('Safari Packages', false);
    $response->assertSee('Kilimanjaro Treks', false);
    $response->assertSee('/photos/', false);
    $response->assertSee('/aventour/assets/', false);
    $response->assertSee('/aventour/assets/css/brand.css', false);
});

it('shows the about us section after the hero', function () {
    $response = $this->get(route('home'));

    $response->assertOk();
    $response->assertSee('class="about-section', false);
    $response->assertSee('Inspired by Tanzania, Designed for Your Journey', false);

    $content = $response->getContent();
    $heroPosition = strpos($content, 'banner-section end');
    $aboutPosition = strpos($content, 'class="about-section');

    expect($heroPosition)->not->toBeFalse()
        ->and($aboutPosition)->not->toBeFalse()
        ->and($aboutPosition)->toBeGreaterThan($heroPosition);
});

it('shows the requested main menu items', function () {
    $response = $this->get(route('home'));

    $response->assertOk();
    $response->assertSee('>Safari</a>', false);
    $response->assertSee('>Kilimanjaro Treks</a>', false);
    $response->assertSee('>Day Trips</a>', false);
    $response->assertSee('>Destinations</a>', false);
    $response->assertSee('>Blog</a>', false);
    $response->assertSee('>About Us</a>', false);
    $response->assertSee('>Contact Us</a>', false);

    preg_match('/<ul class="navigation clearfix">(.*?)<\/ul>/s', $response->getContent(), $matches);

    expect($matches[1] ?? '')
        ->toContain('Day Trips')
        ->not->toContain('>Home</a>');
});

it('lists safari and kilimanjaro packages from photos', function () {
    $response = $this->get(route('home'));

    $response->assertOk();
    $response->assertSee('Tarangire', false);
    $response->assertSee('Kilimanjaro', false);
    $response->assertSee('Ngorongoro', false);
    $response->assertSee('id="safari"', false);
    $response->assertSee('id="kilimanjaro"', false);
});
