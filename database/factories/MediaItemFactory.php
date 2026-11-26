<?php

namespace Database\Factories;

use App\Models\MediaItem;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<MediaItem>
 */
class MediaItemFactory extends Factory
{
    protected $model = MediaItem::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $filename = Str::uuid()->toString().'.jpg';
        $folder = 'uploads/'.now()->format('Y/m');

        return [
            'disk' => 'public',
            'path' => $folder.'/'.$filename,
            'filename' => $filename,
            'extension' => 'jpg',
            'mime_type' => 'image/jpeg',
            'category' => MediaItem::CATEGORY_IMAGE,
            'folder' => $folder,
            'file_size' => fake()->numberBetween(10_000, 2_000_000),
            'width' => 1200,
            'height' => 800,
            'title' => fake()->sentence(3),
            'alt_text' => fake()->sentence(4),
            'caption' => null,
            'description' => null,
            'file_hash' => md5(fake()->uuid()),
            'uploaded_by' => null,
            'file_modified_at' => now(),
        ];
    }
}
