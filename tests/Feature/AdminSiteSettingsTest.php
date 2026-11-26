<?php

use App\Models\User;
use App\Support\SiteSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows the site settings editor including default seo fields', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->get(route('admin.settings.edit'))
        ->assertOk()
        ->assertSee('Default meta title', false)
        ->assertSee('name="default_meta_title"', false)
        ->assertSee('name="default_meta_description"', false);
});

it('lets an admin update website contact settings used on the public site', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->put(route('admin.settings.update'), [
            'company_name' => 'Tanzania Safaris and Trekking',
            'phone' => '+255 700 111 222',
            'email' => 'hello@example.com',
            'address' => 'Moshi, Tanzania',
            'footer_about' => 'Safari experts in Northern Tanzania',
            'tagline' => 'Discover Tanzania',
            'hero_kicker' => 'Safaris',
            'hero_title' => 'Explore Tanzania',
            'hero_subtitle' => 'Plan with local guides',
            'facebook_url' => 'https://facebook.com/example',
            'instagram_url' => 'https://instagram.com/example',
            'twitter_url' => '',
            'whatsapp_url' => '',
        ])
        ->assertRedirect(route('admin.settings.edit'))
        ->assertSessionHas('success');

    expect(app(SiteSettings::class)->get('phone'))->toBe('+255 700 111 222')
        ->and(app(SiteSettings::class)->get('email'))->toBe('hello@example.com');

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('+255 700 111 222', false)
        ->assertSee('hello@example.com', false)
        ->assertSeeText('Safari experts in Northern Tanzania');
});
