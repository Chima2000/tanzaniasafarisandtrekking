<?php

it('renders the branded 404 page for unknown urls', function () {
    $this->get('/this-page-definitely-does-not-exist-'.uniqid())
        ->assertNotFound()
        ->assertSee('This trail does not lead anywhere', false)
        ->assertSee('Back to Home', false)
        ->assertSee('Browse Packages', false)
        ->assertSee('noindex, follow', false)
        ->assertSee('error-lost-section__media', false)
        ->assertSee('/photos/', false);
});
