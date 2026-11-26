<?php

namespace Database\Factories;

use App\Models\WhatsappSetting;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WhatsappSetting>
 */
class WhatsappSettingFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'phone_number' => '+255700000111',
            'enabled' => true,
            'message' => 'Hello, I would like to plan a trip.',
            'popup_title' => null,
            'popup_description' => null,
            'popup_note' => null,
            'corner' => 'bottom-right',
            'offset_x' => 24,
            'offset_y' => 24,
            'icon_width' => 56,
            'icon_height' => 56,
            'icon_color' => WhatsappSetting::BRAND_COLOR,
            'show_on_all_pages' => true,
            'pages' => array_keys(WhatsappSetting::PAGE_LABELS),
        ];
    }
}
