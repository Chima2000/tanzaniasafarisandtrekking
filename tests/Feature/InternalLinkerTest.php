<?php

use App\Support\InternalLinker;
use App\Support\RichText;

beforeEach(function () {
    InternalLinker::flush();
});

it('wraps destination keywords in internal links', function () {
    $html = InternalLinker::apply('<p>Visit Ngorongoro after Tarangire on a classic safari.</p>');

    expect($html)
        ->toContain('class="internal-link"')
        ->toContain('href="'.route('destinations.show', 'ngorongoro').'"')
        ->toContain('href="'.route('destinations.show', 'tarangire').'"')
        ->toContain('>Ngorongoro</a>')
        ->toContain('>Tarangire</a>');
});

it('links plain list copy without wrapping a paragraph', function () {
    $html = InternalLinker::linkPlain('Combine Serengeti with Machame for a full Northern Circuit trip.');

    expect($html)
        ->not->toContain('<p>')
        ->toContain('href="'.route('destinations.show', 'serengeti').'"')
        ->toContain('href="'.route('packages.trekking').'"')
        ->toContain('href="'.route('packages.safari').'"');
});

it('does not nest links inside existing anchors', function () {
    $html = InternalLinker::apply('<p><a href="/custom">Serengeti National Park</a> and Ngorongoro.</p>');

    expect($html)
        ->toContain('<a href="/custom">Serengeti National Park</a>')
        ->toContain('href="'.route('destinations.show', 'ngorongoro').'"')
        ->not->toContain('href="'.route('destinations.show', 'serengeti').'"');
});

it('skips linking the current page keyword', function () {
    $this->get(route('destinations.show', 'serengeti'));

    InternalLinker::flush();

    $html = InternalLinker::apply('<p>Serengeti pairs beautifully with Ngorongoro.</p>');

    expect($html)
        ->not->toContain('href="'.route('destinations.show', 'serengeti').'"')
        ->toContain('href="'.route('destinations.show', 'ngorongoro').'"');
});

it('applies internal links through rich text rendering', function () {
    $html = RichText::render('Explore Tarangire, then climb Kilimanjaro.');

    expect($html)
        ->toContain('class="internal-link"')
        ->toContain('href="'.route('destinations.show', 'tarangire').'"')
        ->toContain('href="'.route('destinations.show', 'kilimanjaro').'"');
});
