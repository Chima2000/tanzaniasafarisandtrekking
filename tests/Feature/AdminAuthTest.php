<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows the admin login page', function () {
    $this->get(route('admin.login'))
        ->assertOk()
        ->assertSee('Admin Panel', false)
        ->assertSee('Sign in to manage the website', false);
});

it('returns 404 for guessed admin urls', function (string $path) {
    $this->get($path)->assertNotFound();
})->with([
    '/admin',
    '/admin/login',
    '/administrator',
    '/administrator/login',
    '/wp-admin',
    '/wp-login.php',
    '/backend',
    '/backend/login',
    '/panel',
    '/panel/login',
    '/dashboard',
    '/dashboard/login',
    '/manage',
    '/cms',
    '/controlpanel',
    '/admin.php',
    '/login',
    '/user/login',
    '/auth/login',
]);

it('serves the login page only on the configured admin path', function () {
    $path = '/'.trim((string) config('app.admin_path'), '/').'/login';

    expect($path)->not->toBe('/admin/login');

    $this->get($path)
        ->assertOk()
        ->assertSee('Admin Panel', false);
});

it('allows an admin to log in and reach the dashboard', function () {
    $admin = User::factory()->admin()->create([
        'email' => 'admin@example.com',
        'password' => 'password',
    ]);

    $this->post(route('admin.login.store'), [
        'email' => $admin->email,
        'password' => 'password',
    ])->assertRedirect(route('admin.dashboard'));

    $this->assertAuthenticatedAs($admin);

    $this->get(route('admin.dashboard'))
        ->assertOk()
        ->assertSee('Dashboard', false);
});

it('rejects non-admin users from the admin panel', function () {
    $user = User::factory()->create([
        'email' => 'guest@example.com',
        'password' => 'password',
        'is_admin' => false,
    ]);

    $this->post(route('admin.login.store'), [
        'email' => $user->email,
        'password' => 'password',
    ])->assertRedirect()
        ->assertSessionHasErrors('email');

    $this->assertGuest();
});

it('blocks guests from the dashboard', function () {
    $this->get(route('admin.dashboard'))
        ->assertRedirect(route('admin.login'));
});

it('logs an admin out', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->post(route('admin.logout'))
        ->assertRedirect(route('admin.login'));

    $this->assertGuest();
});
