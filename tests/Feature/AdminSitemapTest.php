<?php

use App\Models\User;
use App\Services\SitemapGenerator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

it('allows admins to generate and regenerate the sitemap', function () {
    Storage::fake('local');

    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->get(route('admin.sitemap.index'))
        ->assertOk()
        ->assertSee('XML Sitemap', false);

    $this->actingAs($admin)
        ->post(route('admin.sitemap.generate'))
        ->assertRedirect();

    expect(app(SitemapGenerator::class)->exists())->toBeTrue();

    $this->actingAs($admin)
        ->post(route('admin.sitemap.regenerate'))
        ->assertRedirect()
        ->assertSessionHas('success');
});

it('adds a noindex header on admin pages', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->get(route('admin.dashboard'))
        ->assertOk()
        ->assertHeader('X-Robots-Tag', 'noindex, nofollow');
});
