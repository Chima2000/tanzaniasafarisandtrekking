<?php

use App\Mail\BookNowRequestMail;
use App\Models\BookingRequest;
use App\Models\Package;
use App\Models\User;
use App\Support\FormSpamGuard;
use App\Support\PackagePricing;
use App\Support\TourCatalog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

it('shows the sell price and availability calendar on the package page', function () {
    $package = Package::factory()->create([
        'name' => '5 Days Ngorongoro Safari',
        'slug' => '5-days-ngorongoro-safari',
        'category' => 'Safari',
        'duration' => '5 Days',
        'cost_per_person' => 1800,
        'profit_per_person' => 625,
        'is_published' => true,
    ]);

    $priceLabel = PackagePricing::formatUsd(2425);

    $this->get(route('packages.show', $package->slug))
        ->assertOk()
        ->assertSee($priceLabel, false)
        ->assertSee('/ Traveler', false)
        ->assertSee('Check Availability', false)
        ->assertSee('id="datepicker"', false)
        ->assertSee('id="packageBookingModal"', false)
        ->assertSee('id="bookNowForm"', false)
        ->assertSee('/aventour/assets/js/package-booking.js', false);
});

it('stores package price and estimated total with booking requests', function () {
    Mail::fake();
    $this->travelTo('2026-09-06 12:00:00');

    $package = Package::factory()->create([
        'name' => '5 Days Ngorongoro Safari',
        'slug' => '5-days-ngorongoro-safari',
        'cost_per_person' => 1800,
        'profit_per_person' => 625,
        'is_published' => true,
    ]);

    $destination = app(TourCatalog::class)->destinations()->first();
    expect($destination)->not->toBeNull();

    $this->from(route('packages.show', $package->slug))
        ->post(route('book-now.store'), FormSpamGuard::mergeInto([
            'full_name' => 'Alex Booker',
            'email' => 'alex@example.com',
            'phone' => '+255700000222',
            'interest' => 'Safari',
            'budget' => '$2,500 – $4,000',
            'duration_days' => 5,
            'destinations' => [$destination['slug']],
            'adults' => 2,
            'children' => 1,
            'accommodation' => 'Comfort mid-range',
            'travel_date' => '2026-12-15',
            'notes' => 'Prefer a mid-range lodge.',
            'package' => $package->slug,
        ]))
        ->assertRedirect()
        ->assertSessionHas('success');

    $booking = BookingRequest::query()->first();

    expect($booking)->not->toBeNull()
        ->and($booking->package_slug)->toBe($package->slug)
        ->and((float) $booking->price_per_person)->toBe(2425.0)
        ->and((float) $booking->estimated_total)->toBe(7275.0);

    Mail::assertSent(BookNowRequestMail::class);
});

it('rejects blocked travel dates for a package booking', function () {
    Mail::fake();
    $this->travelTo('2026-09-06 12:00:00');

    $package = Package::factory()->create([
        'slug' => 'blocked-date-safari',
        'cost_per_person' => 1000,
        'profit_per_person' => 300,
        'blocked_dates' => ['2026-12-25'],
        'is_published' => true,
    ]);

    $destination = app(TourCatalog::class)->destinations()->first();

    $this->from(route('packages.show', $package->slug))
        ->post(route('book-now.store'), FormSpamGuard::mergeInto([
            'full_name' => 'Alex Booker',
            'email' => 'alex@example.com',
            'budget' => '$2,500 – $4,000',
            'duration_days' => 5,
            'destinations' => [$destination['slug']],
            'adults' => 2,
            'children' => 0,
            'accommodation' => 'Comfort mid-range',
            'travel_date' => '2026-12-25',
            'package' => $package->slug,
        ]))
        ->assertSessionHasErrors(['travel_date']);

    expect(BookingRequest::query()->count())->toBe(0);
    Mail::assertNothingSent();
});

it('lets admins set cost profit and blocked dates on a package', function () {
    $admin = User::factory()->admin()->create();
    $package = Package::factory()->create([
        'slug' => 'priced-safari',
        'cost_per_person' => 1000,
        'profit_per_person' => 200,
    ]);

    $this->actingAs($admin)
        ->put(route('admin.packages.update', $package), [
            'name' => $package->name,
            'slug' => $package->slug,
            'location' => $package->location,
            'duration' => $package->duration,
            'category' => $package->category,
            'rating' => $package->rating,
            'image' => $package->image,
            'images_text' => implode("\n", $package->images),
            'overview' => $package->overview,
            'includes_text' => implode("\n", $package->includes),
            'excludes_text' => implode("\n", $package->excludes),
            'cost_per_person' => 2100,
            'profit_per_person' => 700,
            'blocked_dates_text' => "2026-12-24\n2026-12-25",
            'is_published' => true,
            'sort_order' => 1,
        ])
        ->assertRedirect();

    $package->refresh();

    expect((float) $package->cost_per_person)->toBe(2100.0)
        ->and((float) $package->profit_per_person)->toBe(700.0)
        ->and($package->pricePerPerson())->toBe(2800.0)
        ->and($package->blocked_dates)->toBe(['2026-12-24', '2026-12-25']);
});

it('shows priced packages on listing cards', function () {
    Package::factory()->create([
        'name' => '3 Days Tarangire Escape',
        'slug' => '3-days-tarangire-escape',
        'category' => 'Safari',
        'cost_per_person' => 900,
        'profit_per_person' => 300,
        'is_published' => true,
    ]);

    $this->get(route('packages.index'))
        ->assertOk()
        ->assertSee('$1,200', false)
        ->assertSee('/ Traveler', false);
});
