<?php

namespace Database\Factories;

use App\Models\BookingRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BookingRequest>
 */
class BookingRequestFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fullName = fake()->name();
        $email = fake()->safeEmail();

        return [
            'full_name' => $fullName,
            'email' => $email,
            'phone' => fake()->phoneNumber(),
            'package_slug' => null,
            'package_name' => null,
            'interest' => 'Wildlife Safari',
            'budget' => '$3,000 – $5,000',
            'duration_days' => 8,
            'destinations' => ['serengeti', 'ngorongoro'],
            'adults' => 2,
            'children' => 0,
            'accommodation' => 'Mid-range lodges',
            'travel_date' => now()->addMonth()->toDateString(),
            'notes' => fake()->sentence(),
            'payload' => [
                'full_name' => $fullName,
                'email' => $email,
                'lines' => ['Sample booking request'],
            ],
            'status' => 'new',
        ];
    }
}
