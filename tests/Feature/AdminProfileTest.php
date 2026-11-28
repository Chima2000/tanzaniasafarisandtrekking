<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

it('redirects guests away from the profile page', function () {
    $this->get(route('admin.profile.edit'))
        ->assertRedirect(route('admin.login'));

    $this->put(route('admin.profile.update'), [
        'name' => 'Admin User',
        'email' => 'admin@example.com',
    ])->assertRedirect(route('admin.login'));
});

it('forbids non-admin users from opening the profile page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('admin.profile.edit'))
        ->assertForbidden();
});

it('lets an admin update name, email, and profile photo', function () {
    Storage::fake('public');

    $admin = User::factory()->admin()->create([
        'name' => 'Old Name',
        'email' => 'old@example.com',
    ]);

    $this->actingAs($admin)
        ->put(route('admin.profile.update'), [
            'name' => 'New Admin',
            'email' => 'new-admin@example.com',
            'avatar' => UploadedFile::fake()->image('avatar.jpg', 120, 120),
        ])
        ->assertRedirect(route('admin.profile.edit'))
        ->assertSessionHas('success', 'Profile updated successfully.');

    $admin->refresh();

    expect($admin->name)->toBe('New Admin')
        ->and($admin->email)->toBe('new-admin@example.com')
        ->and($admin->avatar_path)->not->toBeNull();

    Storage::disk('public')->assertExists($admin->avatar_path);

    $this->actingAs($admin)
        ->get(route('admin.profile.edit'))
        ->assertOk()
        ->assertSee('New Admin', false)
        ->assertSee('new-admin@example.com', false)
        ->assertSee(Storage::disk('public')->url($admin->avatar_path), false);
});

it('lets an admin change password with the current password', function () {
    $admin = User::factory()->admin()->create([
        'password' => Hash::make('password'),
    ]);

    $this->actingAs($admin)
        ->put(route('admin.profile.update'), [
            'name' => $admin->name,
            'email' => $admin->email,
            'current_password' => 'password',
            'password' => 'new-password-123',
            'password_confirmation' => 'new-password-123',
        ])
        ->assertRedirect(route('admin.profile.edit'))
        ->assertSessionHas('success', 'Profile updated successfully.');

    expect(Hash::check('new-password-123', $admin->fresh()->password))->toBeTrue();
});

it('rejects a password change without the current password', function () {
    $admin = User::factory()->admin()->create([
        'password' => Hash::make('password'),
    ]);

    $this->actingAs($admin)
        ->from(route('admin.profile.edit'))
        ->put(route('admin.profile.update'), [
            'name' => $admin->name,
            'email' => $admin->email,
            'password' => 'new-password-123',
            'password_confirmation' => 'new-password-123',
        ])
        ->assertRedirect(route('admin.profile.edit'))
        ->assertSessionHasErrors('current_password');

    expect(Hash::check('password', $admin->fresh()->password))->toBeTrue();
});

it('lets an admin remove their profile photo', function () {
    Storage::fake('public');

    $path = UploadedFile::fake()->image('avatar.jpg')->store('admin-avatars', 'public');

    $admin = User::factory()->admin()->create([
        'avatar_path' => $path,
    ]);

    $this->actingAs($admin)
        ->put(route('admin.profile.update'), [
            'name' => $admin->name,
            'email' => $admin->email,
            'remove_avatar' => '1',
        ])
        ->assertRedirect(route('admin.profile.edit'));

    $admin->refresh();

    expect($admin->avatar_path)->toBeNull();
    Storage::disk('public')->assertMissing($path);
});
