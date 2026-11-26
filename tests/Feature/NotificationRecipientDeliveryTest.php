<?php

use App\Mail\BookNowRequestMail;
use App\Mail\ContactSubmissionMail;
use App\Models\NotificationRecipient;
use App\Support\FormSpamGuard;
use App\Support\NotificationRecipients;
use App\Support\TourCatalog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

it('falls back to the configured desk email when no recipients are active', function () {
    NotificationRecipient::query()->delete();

    config(['mail.book_now_recipient' => 'fallback@example.com']);

    expect(app(NotificationRecipients::class)->emailsFor(NotificationRecipient::TYPE_BOOKING))
        ->toBe(['fallback@example.com']);
});

it('sends booking emails only to recipients that opted into bookings', function () {
    Mail::fake();
    NotificationRecipient::query()->delete();
    $this->travelTo('2026-09-06 12:00:00');

    NotificationRecipient::factory()->bookingOnly()->create([
        'email' => 'bookings@example.com',
    ]);
    NotificationRecipient::factory()->contactOnly()->create([
        'email' => 'contacts@example.com',
    ]);
    NotificationRecipient::factory()->inactive()->create([
        'email' => 'paused@example.com',
        'notification_types' => [NotificationRecipient::TYPE_BOOKING],
    ]);

    $destination = app(TourCatalog::class)->destinations()->first();

    $this->from(route('book-now'))
        ->post(route('book-now.store'), FormSpamGuard::mergeInto([
            'full_name' => 'Alex Booker',
            'email' => 'alex@example.com',
            'phone' => '+255700000222',
            'interest' => 'Safari',
            'budget' => '$2,500 – $4,000',
            'duration_days' => 8,
            'destinations' => [$destination['slug']],
            'adults' => 2,
            'children' => 0,
            'accommodation' => 'Comfort mid-range',
            'travel_date' => '2026-12-15',
            'notes' => 'Prefer a mid-range lodge.',
        ]))
        ->assertRedirectToRoute('book-now');

    Mail::assertSent(BookNowRequestMail::class, function (BookNowRequestMail $mail): bool {
        return $mail->hasTo('bookings@example.com')
            && ! $mail->hasTo('contacts@example.com')
            && ! $mail->hasTo('paused@example.com');
    });
});

it('sends contact emails only to recipients that opted into contacts', function () {
    Mail::fake();
    NotificationRecipient::query()->delete();

    NotificationRecipient::factory()->contactOnly()->create([
        'email' => 'hello@example.com',
    ]);
    NotificationRecipient::factory()->bookingOnly()->create([
        'email' => 'bookings@example.com',
    ]);

    $this->from(route('contact'))
        ->post(route('contact.store'), FormSpamGuard::mergeInto([
            'username' => 'Jordan Guest',
            'email' => 'jordan@example.com',
            'phone' => '+255700000111',
            'subject' => 'Safari planning',
            'interest' => 'Tour inquiry',
            'message' => 'Looking for a family safari in July.',
            'source' => 'contact',
        ]))
        ->assertRedirect();

    Mail::assertSent(ContactSubmissionMail::class, function (ContactSubmissionMail $mail): bool {
        return $mail->hasTo('hello@example.com')
            && ! $mail->hasTo('bookings@example.com');
    });
});
