<?php

use App\Models\NotificationRecipient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('redirects guests away from notification recipient settings', function () {
    $this->get(route('admin.notifications.index'))
        ->assertRedirect(route('admin.login'));

    $this->post(route('admin.notifications.store'), [
        'email' => 'desk@example.com',
        'notification_types' => [NotificationRecipient::TYPE_BOOKING],
    ])->assertRedirect(route('admin.login'));
});

it('forbids non-admin users from managing notification recipients', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('admin.notifications.index'))
        ->assertForbidden();
});

it('lets an admin add an email and choose which notifications it receives', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->get(route('admin.notifications.index'))
        ->assertOk()
        ->assertSee('Email Notifications', false)
        ->assertSee('Booking requests', false)
        ->assertSee('Contact / enquiry messages', false);

    $this->actingAs($admin)
        ->post(route('admin.notifications.store'), [
            'name' => 'Operations',
            'email' => 'Ops@Example.com',
            'notification_types' => [NotificationRecipient::TYPE_BOOKING],
            'is_active' => '1',
        ])
        ->assertRedirect(route('admin.notifications.index'))
        ->assertSessionHas('success', 'Notification email added.');

    $recipient = NotificationRecipient::query()->where('email', 'ops@example.com')->first();

    expect($recipient)->not->toBeNull()
        ->and($recipient->name)->toBe('Operations')
        ->and($recipient->notification_types)->toBe([NotificationRecipient::TYPE_BOOKING])
        ->and($recipient->is_active)->toBeTrue();
});

it('requires at least one notification type when saving a recipient', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->from(route('admin.notifications.index'))
        ->post(route('admin.notifications.store'), [
            'name' => 'Sales',
            'email' => 'sales@example.com',
            'notification_types' => [],
            'is_active' => '1',
        ])
        ->assertRedirect(route('admin.notifications.index'))
        ->assertSessionHasErrors('notification_types');
});

it('lets an admin update and remove a notification recipient', function () {
    $admin = User::factory()->admin()->create();
    $recipient = NotificationRecipient::factory()->create([
        'name' => 'Front desk',
        'email' => 'front@example.com',
        'notification_types' => [NotificationRecipient::TYPE_CONTACT],
    ]);

    $this->actingAs($admin)
        ->post(route('admin.notifications.update', $recipient), [
            'name' => 'Reservations',
            'email' => 'reservations@example.com',
            'notification_types' => [
                NotificationRecipient::TYPE_BOOKING,
                NotificationRecipient::TYPE_CONTACT,
            ],
            'is_active' => '0',
        ])
        ->assertRedirect(route('admin.notifications.index'))
        ->assertSessionHas('success', 'Notification email updated.');

    $recipient->refresh();

    expect($recipient->name)->toBe('Reservations')
        ->and($recipient->email)->toBe('reservations@example.com')
        ->and($recipient->is_active)->toBeFalse()
        ->and($recipient->notification_types)->toBe([
            NotificationRecipient::TYPE_BOOKING,
            NotificationRecipient::TYPE_CONTACT,
        ]);

    $this->actingAs($admin)
        ->post(route('admin.notifications.destroy', $recipient))
        ->assertRedirect(route('admin.notifications.index'))
        ->assertSessionHas('success', 'Notification email removed.');

    expect(NotificationRecipient::query()->whereKey($recipient->id)->exists())->toBeFalse();
});
