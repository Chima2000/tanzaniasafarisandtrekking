<?php

namespace Database\Factories;

use App\Models\NotificationRecipient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<NotificationRecipient>
 */
class NotificationRecipientFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'notification_types' => NotificationRecipient::TYPES,
            'is_active' => true,
            'sort_order' => 1,
        ];
    }

    public function bookingOnly(): static
    {
        return $this->state(fn (): array => [
            'notification_types' => [NotificationRecipient::TYPE_BOOKING],
        ]);
    }

    public function contactOnly(): static
    {
        return $this->state(fn (): array => [
            'notification_types' => [NotificationRecipient::TYPE_CONTACT],
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (): array => [
            'is_active' => false,
        ]);
    }
}
