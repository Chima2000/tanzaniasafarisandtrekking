<?php

use App\Mail\BookNowRequestMail;
use App\Models\BookingRequest;
use App\Support\FormSpamGuard;
use App\Support\TourCatalog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

it('renders the book now page with the template booking form', function () {
    $response = $this->get(route('book-now'));

    $response->assertOk();
    $response->assertSee('Book Now', false);
    $response->assertSee('data-cms-field="heading"', false);
    $response->assertSee('Tell Us About Your Trip', false);
    $response->assertSee('Let’s Lock In Your Dates', false);
    $response->assertSee('booking-section', false);
    $response->assertSee('book-now-follow-up', false);
    $response->assertSee('id="bookNowForm"', false);
    $response->assertSee('booking-form', false);
    $response->assertSee('Trip basics', false);
    $response->assertSee('Where do you want to go?', false);
    $response->assertSee('Budget and stay style', false);
    $response->assertSee('Send Booking Request', false);
    $response->assertSee('ohnohoney', false);
    $response->assertSee('name="company_website"', false);
    $response->assertSee('name="form_guard"', false);
    $response->assertSee('/aventour/assets/js/book-now.js', false);
    $response->assertSee(route('book-now.store', absolute: false), false);
    $response->assertDontSee('id="tripPlanner"', false);
    $response->assertDontSee('ja-planner', false);

    $html = $response->getContent();
    expect(strpos($html, 'id="bookNowForm"'))->toBeLessThan(strpos($html, 'Let’s Lock In Your Dates'));
});

it('links the header book now button to the book now page instead of contact', function () {
    $html = $this->get(route('home'))
        ->assertOk()
        ->getContent();

    expect($html)->toContain(route('book-now', absolute: false));
    expect($html)->not->toMatch('/href="[^"]*\/contact"[^>]*>\s*<span>Book Now<\/span>/');
});

it('stores a valid booking request and emails the desk', function () {
    Mail::fake();
    $this->travelTo('2026-09-06 12:00:00');

    $destination = app(TourCatalog::class)->destinations()->first();

    expect($destination)->not->toBeNull();

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
        ->assertRedirectToRoute('book-now')
        ->assertSessionHas('success');

    expect(BookingRequest::query()->count())->toBe(1);
    expect(BookingRequest::query()->first()?->email)->toBe('alex@example.com');

    Mail::assertSent(BookNowRequestMail::class, function (BookNowRequestMail $mail): bool {
        return $mail->hasTo('info@tanzaniasafarisandtrekking.com')
            && $mail->hasReplyTo('alex@example.com')
            && $mail->payload['full_name'] === 'Alex Booker';
    });
});

it('rejects an empty booking payload and keeps validation errors', function () {
    Mail::fake();

    $this->from(route('book-now'))
        ->post(route('book-now.store'), [])
        ->assertRedirectToRoute('book-now')
        ->assertSessionHasErrors(['full_name', 'email', 'budget', 'duration_days', 'destinations', 'adults', 'accommodation', 'travel_date']);

    expect(BookingRequest::query()->count())->toBe(0);
    Mail::assertNothingSent();
});

it('includes red validation hooks in the booking form markup', function () {
    $this->get(route('book-now'))
        ->assertOk()
        ->assertSee('field-error', false)
        ->assertSee('data-error-for="full_name"', false)
        ->assertSee('data-error-for="email"', false)
        ->assertSee('data-error-for="destinations"', false)
        ->assertSee('/aventour/assets/css/book-now.css', false);
});

it('exposes edit pens on the request summary for quick changes', function () {
    $this->get(route('book-now'))
        ->assertOk()
        ->assertSee('Request Summary', false)
        ->assertSee('pen on any row to edit', false)
        ->assertSee('data-book-summary-list', false)
        ->assertSee('fas fa-pen', false)
        ->assertSee('/aventour/assets/js/book-now.js', false);

    $script = file_get_contents(public_path('aventour/assets/js/book-now.js'));

    expect($script)
        ->toContain('data-summary-edit')
        ->toContain('book-now-summary__edit')
        ->toContain('editSummaryItem')
        ->toContain('SiteNotice')
        ->not->toContain('window.alert');
});

it('renders the template center notice popup instead of a browser alert', function () {
    $this->get(route('book-now'))
        ->assertOk()
        ->assertSee('id="siteNoticePopup"', false)
        ->assertSee('close-search', false)
        ->assertSee('/aventour/assets/js/site-notice.js', false)
        ->assertSee('/aventour/assets/css/site-notice.css', false);
});
