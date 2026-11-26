<?php

use App\Models\MediaItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

it('lets an admin open the media library', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->get(route('admin.media.index'))
        ->assertOk()
        ->assertSee('Media Library', false)
        ->assertSee('Upload files', false);
});

it('indexes uploaded files into the media library catalog', function () {
    Storage::fake('public');

    $admin = User::factory()->admin()->create();
    $file = UploadedFile::fake()->image('safari.jpg', 640, 480);

    $this->actingAs($admin)
        ->postJson(route('admin.media.store'), [
            'files' => [$file],
        ])
        ->assertOk()
        ->assertJsonStructure(['uploaded' => [['id', 'url', 'path', 'title', 'thumb']]]);

    expect(MediaItem::query()->count())->toBe(1);
    expect(MediaItem::query()->first()->category)->toBe(MediaItem::CATEGORY_IMAGE);
});

it('registers single field uploads in the media library', function () {
    Storage::fake('public');

    $admin = User::factory()->admin()->create();
    $file = UploadedFile::fake()->image('hero.jpg', 800, 600);

    $response = $this->actingAs($admin)
        ->postJson(route('admin.media.upload'), [
            'file' => $file,
        ])
        ->assertOk()
        ->assertJsonStructure(['url', 'path', 'id']);

    Storage::disk('public')->assertExists($response->json('path'));
    expect(MediaItem::query()->where('path', $response->json('path'))->exists())->toBeTrue();
});

it('browses media library images for the picker', function () {
    $admin = User::factory()->admin()->create();
    MediaItem::factory()->create([
        'title' => 'Serengeti Sunrise',
        'filename' => 'serengeti-sunrise.jpg',
    ]);

    $this->actingAs($admin)
        ->getJson(route('admin.media.browse', ['category' => 'image']))
        ->assertOk()
        ->assertJsonPath('meta.total', 1)
        ->assertJsonFragment(['title' => 'Serengeti Sunrise']);
});

it('updates and deletes media library items', function () {
    Storage::fake('public');

    $admin = User::factory()->admin()->create();
    $path = 'uploads/test/keep.jpg';
    Storage::disk('public')->put($path, 'fake-image-bytes');

    $item = MediaItem::factory()->create([
        'path' => $path,
        'folder' => 'uploads/test',
        'filename' => 'keep.jpg',
    ]);

    $this->actingAs($admin)
        ->putJson(route('admin.media.update', $item), [
            'title' => 'Updated title',
            'alt_text' => 'Updated alt',
        ])
        ->assertOk()
        ->assertJsonPath('item.title', 'Updated title');

    $this->actingAs($admin)
        ->deleteJson(route('admin.media.destroy', $item))
        ->assertOk()
        ->assertJson(['deleted' => true]);

    expect(MediaItem::query()->find($item->id))->toBeNull();
    Storage::disk('public')->assertMissing($path);
});
