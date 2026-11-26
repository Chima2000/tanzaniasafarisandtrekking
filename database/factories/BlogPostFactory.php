<?php

namespace Database\Factories;

use App\Models\BlogPost;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<BlogPost>
 */
class BlogPostFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->sentence(6);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'category' => fake()->randomElement(['Safari Tips', 'Kilimanjaro', 'Travel Planning']),
            'author' => 'Admin',
            'image' => '/aventour/assets/images/news/news-1.jpg',
            'seo_title' => $title,
            'seo_description' => fake()->sentence(18),
            'intro' => fake()->paragraph(),
            'body' => fake()->paragraphs(3, true),
            'is_published' => true,
            'published_at' => now()->subDay(),
        ];
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_published' => false,
            'published_at' => null,
        ]);
    }
}
