<?php

use App\Models\BookingRequest;
use App\Models\ContactSubmission;
use App\Support\FormSpamGuard;
use App\Support\TourCatalog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

it('rejects booking submissions that fill the honeypot field', function () {
    Mail::fake();
    $this->travelTo('2026-09-06 12:00:00');

    $destination = app(TourCatalog::class)->destinations()->first();

    $payload = FormSpamGuard::mergeInto([
        'full_name' => 'Spam Bot',
        'email' => 'bot@example.com',
        'budget' => '$2,500 – $4,000',
        'duration_days' => 8,
        'destinations' => [$destination['slug']],
        'adults' => 2,
        'children' => 0,
        'accommodation' => 'Comfort mid-range',
        'travel_date' => '2026-12-15',
    ]);
    $payload['company_website'] = 'https://spam.example';

    $this->from(route('book-now'))
        ->post(route('book-now.store'), $payload)
        ->assertSessionHasErrors(['company_website']);

    expect(BookingRequest::query()->count())->toBe(0);
    Mail::assertNothingSent();
});

it('rejects booking submissions with a forged form guard token', function () {
    Mail::fake();
    $this->travelTo('2026-09-06 12:00:00');

    $destination = app(TourCatalog::class)->destinations()->first();
    $payload = FormSpamGuard::mergeInto([
        'full_name' => 'Spam Bot',
        'email' => 'bot@example.com',
        'budget' => '$2,500 – $4,000',
        'duration_days' => 8,
        'destinations' => [$destination['slug']],
        'adults' => 2,
        'children' => 0,
        'accommodation' => 'Comfort mid-range',
        'travel_date' => '2026-12-15',
    ]);
    $payload['form_guard'] = str_repeat('a', 64);

    $this->from(route('book-now'))
        ->post(route('book-now.store'), $payload)
        ->assertSessionHasErrors(['form_guard']);

    expect(BookingRequest::query()->count())->toBe(0);
});

it('rejects contact submissions that fill the honeypot field', function () {
    Mail::fake();

    $payload = FormSpamGuard::mergeInto([
        'username' => 'Spam Bot',
        'email' => 'bot@example.com',
        'subject' => 'Buy now',
        'message' => 'Cheap watches',
        'source' => 'contact',
    ]);
    $payload['company_website'] = 'http://spam.test';

    $this->from(route('contact'))
        ->post(route('contact.store'), $payload)
        ->assertSessionHasErrors(['company_website']);

    expect(ContactSubmission::query()->count())->toBe(0);
    Mail::assertNothingSent();
});

it('includes spam guard fields on the contact form', function () {
    $this->get(route('contact'))
        ->assertOk()
        ->assertSee('ohnohoney', false)
        ->assertSee('name="company_website"', false)
        ->assertSee('name="form_started_at"', false)
        ->assertSee('name="form_guard"', false);
});
