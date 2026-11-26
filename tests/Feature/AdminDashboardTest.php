<?php

use App\Models\BookingRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows website management links on the dashboard', function () {
    $admin = User::factory()->admin()->create();
    BookingRequest::factory()->create([
        'full_name' => 'Amina Guest',
    ]);

    $this->actingAs($admin)
        ->get(route('admin.dashboard'))
        ->assertOk()
        ->assertSee('Packages', false)
        ->assertSee('Destinations', false)
        ->assertSee('Blog posts', false)
        ->assertSee('Popular Packages', false)
        ->assertSee('Recent Bookings', false)
        ->assertSee('Amina Guest', false)
        ->assertSee(route('admin.settings.edit', absolute: false), false)
        ->assertSee(route('admin.packages.index', absolute: false), false)
        ->assertSee(route('admin.bookings.index', absolute: false), false);
});

it('lists packages and destinations for admins', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->get(route('admin.packages.index'))
        ->assertOk();

    $this->actingAs($admin)
        ->get(route('admin.destinations.index'))
        ->assertOk();
});
