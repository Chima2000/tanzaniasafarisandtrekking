<?php

use App\Models\Package;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('lets an admin create and publish a package for the public site', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->post(route('admin.packages.store'), [
            'name' => '4 Days Serengeti Explorer',
            'slug' => '4-days-serengeti-explorer',
            'location' => 'Serengeti',
            'duration' => '4 Days',
            'category' => 'Safari',
            'rating' => '5.0',
            'image' => '/photos/demo.jpg',
            'images_text' => "/photos/demo.jpg\n/photos/demo-2.jpg",
            'overview' => 'A compact Serengeti safari.',
            'includes_text' => "Park fees\nGuide",
            'excludes_text' => 'Flights',
            'is_published' => true,
            'is_featured' => true,
            'sort_order' => 1,
        ])
        ->assertRedirect();

    $package = Package::query()->where('slug', '4-days-serengeti-explorer')->first();

    expect($package)->not->toBeNull()
        ->and($package->is_published)->toBeTrue();

    $this->get(route('packages.show', '4-days-serengeti-explorer'))
        ->assertOk()
        ->assertSee('4 Days Serengeti Explorer', false)
        ->assertSee('A compact Serengeti safari.', false);
});
