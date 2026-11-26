<?php

use App\Models\Page;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('lets an admin update a managed website page', function () {
    $admin = User::factory()->admin()->create();
    $page = Page::query()->create([
        'slug' => 'about',
        'title' => 'About Us',
        'content' => [
            'heading' => 'Old heading',
            'body' => 'Old body',
        ],
        'is_published' => true,
    ]);

    $this->actingAs($admin)
        ->put(route('admin.pages.update', $page), [
            'title' => 'About Us',
            'seo_title' => 'About | Tanzania Safaris',
            'seo_description' => 'About our team',
            'is_published' => true,
            'content' => [
                'heading' => 'Our Tanzania Story',
                'body' => 'We guide safaris and Kilimanjaro climbs.',
                'subtitle' => 'Who We Are',
            ],
        ])
        ->assertRedirect(route('admin.pages.edit', $page));

    expect($page->fresh()->content['heading'])->toBe('Our Tanzania Story');

    $this->get(route('about'))
        ->assertOk()
        ->assertSee('Our Tanzania Story', false)
        ->assertSee('We guide safaris and Kilimanjaro climbs.', false);
});
