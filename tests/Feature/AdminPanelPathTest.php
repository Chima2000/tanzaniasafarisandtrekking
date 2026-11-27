<?php

use App\Models\User;
use App\Support\AdminPanelPath;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;

uses(RefreshDatabase::class);

beforeEach(function () {
    File::delete(storage_path('app/'.AdminPanelPath::STORAGE_FILE));
});

afterEach(function () {
    File::delete(storage_path('app/'.AdminPanelPath::STORAGE_FILE));
});

it('shows the admin login path controls on site settings', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->get(route('admin.settings.edit'))
        ->assertOk()
        ->assertSee('Admin login path', false)
        ->assertSee('name="admin_panel_path"', false)
        ->assertSee(app(AdminPanelPath::class)->loginUrl(), false);
});

it('lets an admin change the login path and redirects to the new settings url', function () {
    $admin = User::factory()->admin()->create();
    $newPath = 'ops-desk-9f2a';

    $this->actingAs($admin)
        ->put(route('admin.settings.admin-path.update'), [
            'admin_panel_path' => $newPath,
        ])
        ->assertRedirect(url('/'.$newPath.'/settings'))
        ->assertSessionHas('success')
        ->assertSessionHas('admin_login_url', url('/'.$newPath.'/login'));

    expect(app(AdminPanelPath::class)->current())->toBe($newPath);
});

it('rejects reserved or guessed admin path names', function (string $path) {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->from(route('admin.settings.edit'))
        ->put(route('admin.settings.admin-path.update'), [
            'admin_panel_path' => $path,
        ])
        ->assertRedirect(route('admin.settings.edit'))
        ->assertSessionHasErrors('admin_panel_path');
})->with([
    'admin',
    'login',
    'wp-admin',
    'dashboard',
    'panel',
    'ab',
    'Bad_Path',
    'has/slash',
]);

it('serves the login page on a stored path after the application reboots', function () {
    app(AdminPanelPath::class)->update('reboot-path-7c1d');

    $this->refreshApplication();

    $this->get('/reboot-path-7c1d/login')
        ->assertOk()
        ->assertSee('Admin Panel', false);

    $this->get('/admin/login')->assertNotFound();
    $this->get('/'.trim((string) config('app.admin_path'), '/').'/login')->assertNotFound();
});
