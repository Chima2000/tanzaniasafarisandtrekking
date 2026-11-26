<?php

namespace Database\Factories;

use App\Models\WhatsappContact;
use App\Models\WhatsappSetting;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WhatsappContact>
 */
class WhatsappContactFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'whatsapp_setting_id' => WhatsappSetting::factory(),
            'section' => 'Guides',
            'name' => fake()->name(),
            'title' => 'Safari guide',
            'phone_number' => '+255700111222',
            'photo_path' => null,
            'sort_order' => 1,
        ];
    }
}
