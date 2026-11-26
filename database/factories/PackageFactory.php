<?php

namespace Database\Factories;

use App\Models\Package;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Package>
 */
class PackageFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);

        return [
            'name' => Str::title($name),
            'slug' => Str::slug($name),
            'location' => 'Northern Tanzania',
            'duration' => '5 Days',
            'category' => 'Safari',
            'rating' => '5.0',
            'image' => '/aventour/assets/images/resource/popular-1.jpg',
            'images' => ['/aventour/assets/images/resource/popular-1.jpg'],
            'photo_count' => 1,
            'overview' => fake()->paragraph(),
            'itinerary' => [],
            'includes' => ['Park fees', 'Guided game drives'],
            'excludes' => ['Flights'],
            'is_published' => true,
            'is_featured' => false,
            'sort_order' => 1,
        ];
    }
}
