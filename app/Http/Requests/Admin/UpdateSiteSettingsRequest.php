<?php

namespace App\Http\Requests\Admin;

use App\Support\RichText;
use App\Support\SiteSettings;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSiteSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    protected function prepareForValidation(): void
    {
        $normalized = [];

        foreach (['facebook_url', 'instagram_url', 'twitter_url', 'whatsapp_url', 'email', 'phone'] as $field) {
            $value = $this->input($field);
            $normalized[$field] = is_string($value) && trim($value) === '' ? null : $value;
        }

        $this->merge($normalized);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $rules = [];

        foreach (array_keys(SiteSettings::defaults()) as $key) {
            $rules[$key] = ['nullable', 'string', 'max:2000'];
        }

        $rules['hero_subtitle'] = ['nullable', 'string', 'max:20000'];
        $rules['footer_about'] = ['nullable', 'string', 'max:20000'];
        $rules['email'] = ['nullable', 'email', 'max:254'];
        $rules['facebook_url'] = ['nullable', 'url', 'max:500'];
        $rules['instagram_url'] = ['nullable', 'url', 'max:500'];
        $rules['twitter_url'] = ['nullable', 'url', 'max:500'];
        $rules['whatsapp_url'] = ['nullable', 'url', 'max:500'];

        return $rules;
    }

    /**
     * @return array<string, mixed>
     */
    public function settingsPayload(): array
    {
        $validated = $this->validated();

        foreach (['hero_subtitle', 'footer_about'] as $key) {
            if (array_key_exists($key, $validated)) {
                $validated[$key] = RichText::sanitize($validated[$key] ?? null);
            }
        }

        return $validated;
    }
}
