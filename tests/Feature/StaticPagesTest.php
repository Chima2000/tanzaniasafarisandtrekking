<?php

it('renders the about page from the aventour about template', function () {
    $response = $this->get(route('about'));

    $response->assertOk();
    $response->assertSee('About Us', false);
    $response->assertSee('about-section', false);
    $response->assertSee('Behind the Adventure', false);
});

it('renders the contact page from the aventour contact template', function () {
    $response = $this->get(route('contact'));

    $response->assertOk();
    $response->assertSee('Contact Us', false);
    $response->assertSee('contact-section', false);
    $response->assertSee('Reach Out to Us', false);
});

it('renders the faq page from the aventour faq template', function () {
    $response = $this->get(route('faq'));

    $response->assertOk();
    $response->assertSee('Faq’s', false);
    $response->assertSee('faq-page-section', false);
    $response->assertSee('How do I book a tour?', false);
});

it('renders the blog page from the aventour news template', function () {
    $response = $this->get(route('blog'));

    $response->assertOk();
    $response->assertSee('>Blog</', false);
    $response->assertSee('news-section', false);
    $response->assertSee('Best Time to Visit Serengeti', false);
    $response->assertSee('/blog/best-time-serengeti-great-migration', false);
});

it('links the landing menu to the new inner pages', function () {
    $response = $this->get(route('home'));

    $response->assertOk();
    $response->assertSee(route('packages.index', ['category' => 'Safari'], false), false);
    $response->assertSee(route('packages.index', ['category' => 'Trekking'], false), false);
    $response->assertSee(route('packages.index', ['category' => 'Day Trip'], false), false);
    $response->assertSee(route('destinations.index', absolute: false), false);
    $response->assertSee(route('blog', absolute: false), false);
    $response->assertSee(route('about', absolute: false), false);
    $response->assertSee(route('contact', absolute: false), false);
});

it('links home blog cards to individual blog posts', function () {
    $response = $this->get(route('home'));

    $response->assertOk();
    $response->assertSee('id="blog"', false);
    $response->assertSee('Best Time to Visit Serengeti', false);
    $response->assertSee(route('blog.show', 'best-time-serengeti-great-migration', false), false);
    $response->assertSee(route('blog.show', 'choosing-kilimanjaro-route-machame-lemosho-marangu', false), false);
    $response->assertSee(route('blog', absolute: false), false);
    $response->assertDontSee('How to Travel Smart: Tips for First-time Travelers', false);
});
