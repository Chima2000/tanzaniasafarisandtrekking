<?php

namespace Database\Factories;

use App\Models\Destination;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Destination>
 */
class DestinationFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->city();

        return [
            'slug' => Str::slug($title),
            'short' => strtoupper(substr($title, 0, 2)),
            'title' => $title,
            'full_name' => $title.' National Park',
            'region' => 'Northern Tanzania',
            'needles' => [$title],
            'starts' => 'From 2 Days',
            'seo_title' => $title.' Safari',
            'seo_description' => fake()->sentence(12),
            'headline' => fake()->sentence(6),
            'description' => fake()->paragraph(),
            'story_intro' => fake()->paragraph(),
            'story_chapters' => [],
            'highlights' => ['Wildlife', 'Scenery'],
            'wildlife' => ['Lion', 'Elephant'],
            'best_time' => 'June to October',
            'getting_there' => 'Fly into Kilimanjaro International Airport.',
            'tips' => ['Bring binoculars'],
            'facts' => [],
            'faqs' => [],
            'image' => '/aventour/assets/images/resource/popular-1.jpg',
            'is_published' => true,
            'sort_order' => 1,
        ];
    }
}
