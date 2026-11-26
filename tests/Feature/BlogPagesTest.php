<?php

it('lists blog posts on the blog index', function () {
    $response = $this->get(route('blog'));

    $response->assertOk();
    $response->assertSee('>Blog</', false);
    $response->assertSee('news-section', false);
    $response->assertSee('Best Time to Visit Serengeti', false);
    $response->assertSee(route('blog.show', 'best-time-serengeti-great-migration', false), false);
    $response->assertSee(route('blog.show', 'choosing-kilimanjaro-route-machame-lemosho-marangu', false), false);
});

it('shows an individual blog post page', function () {
    $response = $this->get(route('blog.show', 'best-time-serengeti-great-migration'));

    $response->assertOk();
    $response->assertSee('Best Time to Visit Serengeti for the Great Migration', false);
    $response->assertSee('blog-details-content', false);
    $response->assertSee('Match the Season to the Story', false);
    $response->assertSee('Practical Tips', false);
    $response->assertSee('Back to Blog', false);
    $response->assertSee('BlogPosting', false);
});

it('renders related stories with template sidebar thumbnail markup', function () {
    $response = $this->get(route('blog.show', 'choosing-kilimanjaro-route-machame-lemosho-marangu'));

    $response->assertOk();
    $response->assertSee('More Stories', false);
    $response->assertSee('class="image-box"', false);
    $response->assertSee('class="inner"', false);
    $response->assertDontSee('class="post-thumb"', false);
});

it('shows every published blog post', function (string $slug, string $needle) {
    $response = $this->get(route('blog.show', $slug));

    $response->assertOk();
    $response->assertSee($needle, false);
    $response->assertSee('Back to Blog', false);
})->with([
    ['best-time-serengeti-great-migration', 'Great Migration'],
    ['choosing-kilimanjaro-route-machame-lemosho-marangu', 'Machame'],
    ['ngorongoro-crater-one-day-what-to-expect', 'crater floor'],
    ['tarangire-perfect-safari-opener-from-arusha', 'baobab'],
    ['day-trips-near-arusha-materuni-chemka-park-safaris', 'Materuni'],
    ['plan-northern-circuit-safari-with-confidence', 'Northern Circuit'],
]);

it('returns not found for an unknown blog post', function () {
    $this->get(route('blog.show', 'missing-post'))
        ->assertNotFound();
});
