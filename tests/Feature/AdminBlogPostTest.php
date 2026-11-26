<?php

use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('lets an admin create a published blog post that appears on the public site', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->post(route('admin.blog-posts.store'), [
            'title' => 'Sunrise Over Serengeti',
            'slug' => 'sunrise-over-serengeti',
            'category' => 'Safari Tips',
            'author' => 'Admin',
            'intro' => 'A short intro for the public blog.',
            'body' => 'Full article body for guests landing on the website.',
            'is_published' => true,
            'published_at' => now()->toDateTimeString(),
        ])
        ->assertRedirect();

    $post = BlogPost::query()->where('slug', 'sunrise-over-serengeti')->first();

    expect($post)->not->toBeNull()
        ->and($post->is_published)->toBeTrue();

    $this->get(route('blog'))
        ->assertOk()
        ->assertSee('Sunrise Over Serengeti', false);

    $this->get(route('blog.show', 'sunrise-over-serengeti'))
        ->assertOk()
        ->assertSee('Full article body for guests landing on the website.', false);
});

it('lets an admin update a blog post', function () {
    $admin = User::factory()->admin()->create();
    $post = BlogPost::factory()->create([
        'title' => 'Old Title',
        'slug' => 'old-title',
    ]);

    $this->actingAs($admin)
        ->put(route('admin.blog-posts.update', $post), [
            'title' => 'Updated Title',
            'slug' => 'updated-title',
            'category' => 'Travel Planning',
            'author' => 'Admin',
            'intro' => 'Updated intro',
            'body' => 'Updated body',
            'is_published' => true,
            'published_at' => now()->toDateTimeString(),
        ])
        ->assertRedirect(route('admin.blog-posts.edit', 'updated-title'));

    expect($post->fresh()->title)->toBe('Updated Title');
});
