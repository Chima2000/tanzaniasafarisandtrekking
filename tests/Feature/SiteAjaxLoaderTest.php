<?php

it('includes the site ajax loader on the home page', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee('id="siteAjaxLoader"', false)
        ->assertSee('/aventour/assets/css/ajax-loader.css', false)
        ->assertSee('/aventour/assets/js/ajax-loader.js', false)
        ->assertDontSee('site-ajax-loader is-active', false);
});

it('includes the site ajax loader on public layout pages', function () {
    $this->get(route('book-now'))
        ->assertOk()
        ->assertSee('id="siteAjaxLoader"', false)
        ->assertSee('/aventour/assets/css/ajax-loader.css', false)
        ->assertSee('/aventour/assets/js/ajax-loader.js', false)
        ->assertDontSee('site-ajax-loader is-active', false);

    $this->get(route('about'))
        ->assertOk()
        ->assertSee('id="siteAjaxLoader"', false)
        ->assertSee('/aventour/assets/js/ajax-loader.js', false);
});

it('limits the ajax loader to form submits instead of page navigation', function () {
    $script = file_get_contents(public_path('aventour/assets/js/ajax-loader.js'));

    expect($script)
        ->toContain('window.SiteAjaxLoader')
        ->toContain('setButtonLoading')
        ->toContain('shouldShowLoaderForForm')
        ->toContain('book-now-form')
        ->toContain('contact-form')
        ->toContain('is-loading')
        ->not->toContain('Initial page paint loader')
        ->not->toContain('siteAjaxFetch')
        ->not->toContain("closest('a[href]')");
});
