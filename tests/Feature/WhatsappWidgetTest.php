<?php

use App\Models\User;
use App\Models\WhatsappContact;
use App\Models\WhatsappSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

it('redirects guests away from whatsapp settings', function () {
    $this->get(route('admin.whatsapp.edit'))
        ->assertRedirect(route('admin.login'));

    $this->post(route('admin.whatsapp.contacts.store'), [
        'name' => 'Erick Aron',
        'title' => 'Tour Director',
        'phone_number' => '+255 700 111 222',
    ])->assertRedirect(route('admin.login'));
});

it('forbids non-admin users from opening whatsapp settings', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('admin.whatsapp.edit'))
        ->assertForbidden();
});

it('hides the button on public pages until a number is saved', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->get(route('admin.whatsapp.edit'))
        ->assertOk()
        ->assertSee('WhatsApp Widget', false)
        ->assertSee('#25d366', false)
        ->assertSee('Staff login', false);

    $this->get(route('home'))
        ->assertOk()
        ->assertDontSee('klt-whatsapp', false);

    auth()->logout();

    $this->get(route('admin.login'))
        ->assertOk()
        ->assertDontSee('data-wa-dock', false);
});

it('shows the saved number only on selected pages', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->post(route('admin.whatsapp.update'), whatsappPayload())
        ->assertRedirect(route('admin.whatsapp.edit'))
        ->assertSessionHas('success', 'WhatsApp settings saved.');

    $setting = WhatsappSetting::query()->firstOrFail();

    expect($setting->phone_number)->toBe('+255749719998')
        ->and($setting->icon_color)->toBe('#112233')
        ->and($setting->corner)->toBe('bottom-left')
        ->and($setting->offset_x)->toBe(40)
        ->and($setting->offset_y)->toBe(80)
        ->and($setting->icon_width)->toBe(64)
        ->and($setting->icon_height)->toBe(72)
        ->and($setting->show_on_all_pages)->toBeFalse()
        ->and($setting->pages)->toBe(['home'])
        ->and($setting->chatUrl())->toBe('https://wa.me/255749719998?text=Hello%20team');

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('https://wa.me/255749719998?text=Hello%20team', false)
        ->assertSee('data-corner="bottom-left"', false)
        ->assertSee('--klt-wa-color: #112233', false)
        ->assertSee('--klt-wa-w: 64px', false)
        ->assertSee('--klt-wa-h: 72px', false)
        ->assertSee('Tanzania Safaris and Trekking', false);

    $this->get(route('contact'))
        ->assertOk()
        ->assertDontSee('data-wa-dock', false);

    $this->actingAs($admin)
        ->get(route('admin.dashboard'))
        ->assertOk()
        ->assertDontSee('data-wa-dock', false);
});

it('shows the button across the public site admin and login when all pages is enabled', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->post(route('admin.whatsapp.update'), whatsappPayload([
            'show_on_all_pages' => '1',
            'pages' => [],
            'corner' => 'bottom-right',
            'icon_color' => '#25d366',
        ]))
        ->assertRedirect(route('admin.whatsapp.edit'));

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('data-wa-dock', false);

    $this->get(route('faq'))
        ->assertOk()
        ->assertSee('data-wa-dock', false);

    auth()->logout();

    $this->get(route('admin.login'))
        ->assertOk()
        ->assertSee('data-wa-dock', false);

    $this->actingAs($admin)
        ->get(route('admin.dashboard'))
        ->assertOk()
        ->assertSee('data-wa-dock', false)
        ->assertSee('https://wa.me/255749719998?text=Hello%20team', false);
});

it('hides the button after the number is removed', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->post(route('admin.whatsapp.update'), whatsappPayload([
            'show_on_all_pages' => '1',
        ]));

    $this->actingAs($admin)
        ->post(route('admin.whatsapp.destroy'))
        ->assertRedirect(route('admin.whatsapp.edit'))
        ->assertSessionHas('success', 'WhatsApp number removed. The button is hidden.');

    $setting = WhatsappSetting::query()->firstOrFail();

    expect($setting->phone_number)->toBeNull()
        ->and($setting->enabled)->toBeFalse();

    $this->get(route('home'))
        ->assertOk()
        ->assertDontSee('data-wa-dock', false);
});

it('rejects a short whatsapp number', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->from(route('admin.whatsapp.edit'))
        ->post(route('admin.whatsapp.update'), whatsappPayload([
            'phone_number' => '12345',
        ]))
        ->assertRedirect(route('admin.whatsapp.edit'))
        ->assertSessionHasErrors([
            'phone_number' => 'Enter a WhatsApp number with 8 to 15 digits.',
        ]);
});

it('rejects a non-hex icon color', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->from(route('admin.whatsapp.edit'))
        ->post(route('admin.whatsapp.update'), whatsappPayload([
            'icon_color' => 'red',
        ]))
        ->assertRedirect(route('admin.whatsapp.edit'))
        ->assertSessionHasErrors('icon_color');

    $this->assertDatabaseCount('whatsapp_settings', 0);
});

it('lets saved profiles replace the main number on the card', function () {
    Storage::fake('public');
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->post(route('admin.whatsapp.update'), whatsappPayload([
            'show_on_all_pages' => '1',
            'message' => null,
            'popup_title' => 'Start a conversation',
            'popup_description' => 'Click a team member below to chat on WhatsApp.',
            'popup_note' => 'The team typically replies in a few minutes.',
        ]))
        ->assertRedirect(route('admin.whatsapp.edit'));

    $this->actingAs($admin)
        ->post(route('admin.whatsapp.contacts.store'), [
            'section' => 'Directors',
            'name' => 'Erick Aron',
            'title' => 'Tour Director',
            'phone_number' => '+255 700 111 222',
            'photo' => UploadedFile::fake()->image('erick.jpg', 80, 80),
        ])
        ->assertRedirect(route('admin.whatsapp.edit'))
        ->assertSessionHas('success', 'WhatsApp profile added.');

    $contact = WhatsappContact::query()->firstOrFail();

    expect($contact->photo_path)->not->toBeNull();
    Storage::disk('public')->assertExists($contact->photo_path);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('Start a conversation', false)
        ->assertSee('Click a team member below to chat on WhatsApp.', false)
        ->assertSee('Erick Aron', false)
        ->assertSee('Tour Director', false)
        ->assertSee('Directors', false)
        ->assertSee('https://wa.me/255700111222', false)
        ->assertSee('klt-wa-popup-close', false)
        ->assertDontSee('https://wa.me/255749719998', false);

    $this->actingAs($admin)
        ->get(route('admin.dashboard'))
        ->assertOk()
        ->assertSee('Erick Aron', false);

    $this->actingAs($admin)
        ->post(route('admin.whatsapp.contacts.destroy', $contact))
        ->assertRedirect(route('admin.whatsapp.edit'));

    Storage::disk('public')->assertMissing($contact->photo_path);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('https://wa.me/255749719998', false)
        ->assertDontSee('Erick Aron', false);
});

/**
 * @param  array<string, mixed>  $overrides
 * @return array<string, mixed>
 */
function whatsappPayload(array $overrides = []): array
{
    return array_merge([
        'phone_number' => '+255 749 719 998',
        'enabled' => '1',
        'message' => 'Hello team',
        'corner' => 'bottom-left',
        'offset_x' => 40,
        'offset_y' => 80,
        'icon_width' => 64,
        'icon_height' => 72,
        'icon_color' => '#112233',
        'show_on_all_pages' => '0',
        'pages' => ['home'],
    ], $overrides);
}
