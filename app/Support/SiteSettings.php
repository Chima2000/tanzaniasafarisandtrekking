<?php

namespace App\Support;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class SiteSettings
{
    /**
     * @return array<string, string>
     */
    public static function defaults(): array
    {
        return [
            'company_name' => 'Tanzania Safaris and Trekking',
            'tagline' => 'Expert-led safaris and Kilimanjaro treks across Tanzania.',
            'phone' => '+255 769 079 298',
            'email' => 'info@tanzaniasafarisandtrekking.com',
            'address' => 'Arusha, Tanzania',
            'hero_kicker' => 'Tanzania Safaris',
            'hero_title' => 'Wildlife, Kilimanjaro & Day Adventures',
            'hero_subtitle' => 'Plan unforgettable safaris and treks across Tanzania with local experts.',
            'facebook_url' => '',
            'instagram_url' => '',
            'twitter_url' => '',
            'whatsapp_url' => '',
            'footer_about' => 'Expert-led safaris and Kilimanjaro treks across Tanzania.',
        ];
    }

    public function get(string $key, ?string $default = null): string
    {
        $all = $this->all();

        if (array_key_exists($key, $all) && $all[$key] !== '') {
            return $all[$key];
        }

        return $default ?? (self::defaults()[$key] ?? '');
    }

    /**
     * @return array<string, string>
     */
    public function all(): array
    {
        if (! Schema::hasTable('site_settings')) {
            return self::defaults();
        }

        return Cache::rememberForever('site_settings', function (): array {
            $stored = SiteSetting::query()->pluck('value', 'key')->all();

            return array_merge(self::defaults(), array_map(
                static fn (?string $value): string => (string) ($value ?? ''),
                $stored
            ));
        });
    }

    /**
     * @param  array<string, string|null>  $values
     */
    public function putMany(array $values): void
    {
        foreach ($values as $key => $value) {
            if (! array_key_exists($key, self::defaults())) {
                continue;
            }

            SiteSetting::query()->updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        Cache::forget('site_settings');
    }
}
