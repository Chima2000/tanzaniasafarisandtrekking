<?php

use App\Mail\ContactSubmissionMail;
use App\Models\ContactSubmission;
use App\Models\Package;
use App\Models\User;
use App\Support\FormSpamGuard;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

it('stores a contact page submission for admin tracking', function () {
    Mail::fake();

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
        ->assertRedirect()
        ->assertSessionHas('success');

    $contact = ContactSubmission::query()->first();

    expect($contact)->not->toBeNull()
        ->and($contact->full_name)->toBe('Jordan Guest')
        ->and($contact->email)->toBe('jordan@example.com')
        ->and($contact->source)->toBe('contact')
        ->and($contact->status)->toBe('new');

    Mail::assertSent(ContactSubmissionMail::class);
});

it('stores a package enquiry submission linked to the package', function () {
    Mail::fake();

    $package = Package::factory()->create([
        'name' => '4 Days Serengeti Explorer',
        'slug' => '4-days-serengeti-explorer',
    ]);

    $this->from(route('packages.show', $package->slug))
        ->post(route('contact.store'), FormSpamGuard::mergeInto([
            'fname' => 'Sam',
            'lname' => 'Traveler',
            'email' => 'sam@example.com',
            'phone' => '+255700000333',
            'subject' => $package->name,
            'package' => $package->slug,
            'message' => 'Is mid-June open?',
            'source' => 'package_enquiry',
        ]))
        ->assertRedirect()
        ->assertSessionHas('success');

    expect(ContactSubmission::query()->first())
        ->package_slug->toBe($package->slug)
        ->package_name->toBe($package->name)
        ->source->toBe('package_enquiry');
});

it('lists contact submissions in the admin panel', function () {
    $admin = User::factory()->admin()->create();
    $contact = ContactSubmission::factory()->create([
        'full_name' => 'Nora Enquiry',
        'subject' => 'Kilimanjaro dates',
    ]);

    $this->actingAs($admin)
        ->get(route('admin.contacts.index'))
        ->assertOk()
        ->assertSee('Nora Enquiry', false)
        ->assertSee('Kilimanjaro dates', false);

    $this->actingAs($admin)
        ->get(route('admin.contacts.show', $contact))
        ->assertOk()
        ->assertSee('Nora Enquiry', false);
});
