<?php

namespace Database\Factories;

use App\Models\ContactSubmission;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ContactSubmission>
 */
class ContactSubmissionFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'full_name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'subject' => 'Tour inquiry',
            'interest' => 'Safari',
            'package_slug' => null,
            'package_name' => null,
            'message' => fake()->paragraph(),
            'source' => 'contact',
            'status' => 'new',
            'payload' => [],
        ];
    }
}
