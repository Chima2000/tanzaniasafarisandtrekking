<?php

use App\Support\RichText;

it('converts plain text into paragraphs for rendering', function () {
    $html = RichText::render("Hello world.\n\nSecond paragraph.");

    expect($html)
        ->toContain('<p>Hello world.</p>')
        ->toContain('<p>Second paragraph.</p>');
});

it('keeps safe rich text formatting and strips scripts', function () {
    $html = RichText::sanitize('<p><strong>Bold</strong> and <em>italic</em></p><script>alert(1)</script><p onclick="x">Safe</p>');

    expect($html)
        ->toContain('<strong>Bold</strong>')
        ->toContain('<em>italic</em>')
        ->not->toContain('<script>')
        ->not->toContain('onclick');
});

it('allows images and local video tags', function () {
    $html = RichText::sanitize('<p><img src="/photos/lion.jpg" alt="Lion"><video controls src="/videos/clip.mp4"></video></p>');

    expect($html)
        ->toContain('src="/photos/lion.jpg"')
        ->toContain('<video')
        ->toContain('src="/videos/clip.mp4"');
});

it('blocks javascript urls', function () {
    $html = RichText::sanitize('<a href="javascript:alert(1)">Click</a><img src="javascript:alert(1)">');

    expect($html)
        ->not->toContain('javascript:');
});
