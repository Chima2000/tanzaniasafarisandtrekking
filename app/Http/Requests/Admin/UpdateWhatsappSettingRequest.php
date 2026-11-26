<?php

namespace App\Http\Requests\Admin;

use App\Models\WhatsappSetting;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateWhatsappSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    protected function prepareForValidation(): void
    {
        $pages = collect($this->input('pages', []))
            ->filter(fn (mixed $page): bool => is_string($page) && $page !== '')
            ->values()
            ->all();

        $this->merge([
            'pages' => $pages,
            'icon_color' => strtolower(trim((string) $this->input('icon_color'))),
            'message' => $this->blankToNull('message'),
            'popup_title' => $this->blankToNull('popup_title'),
            'popup_description' => $this->blankToNull('popup_description'),
            'popup_note' => $this->blankToNull('popup_note'),
            'phone_number' => $this->blankToNull('phone_number'),
        ]);
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'phone_number' => [
                'nullable',
                'string',
                'max:32',
                function (string $attribute, mixed $value, Closure $fail): void {
                    if (trim((string) $value) === '') {
                        return;
                    }

                    WhatsappSetting::rejectShortPhone($value, $fail);
                },
            ],
            'enabled' => ['required', 'boolean'],
            'message' => ['nullable', 'string', 'max:300'],
            'popup_title' => ['nullable', 'string', 'max:120'],
            'popup_description' => ['nullable', 'string', 'max:300'],
            'popup_note' => ['nullable', 'string', 'max:180'],
            'corner' => ['required', 'string', Rule::in(WhatsappSetting::CORNERS)],
            'offset_x' => ['required', 'integer', 'min:0', 'max:240'],
            'offset_y' => ['required', 'integer', 'min:0', 'max:400'],
            'icon_width' => ['required', 'integer', 'min:40', 'max:96'],
            'icon_height' => ['required', 'integer', 'min:40', 'max:96'],
            'icon_color' => ['required', 'regex:/^#[0-9a-f]{6}$/'],
            'show_on_all_pages' => ['required', 'boolean'],
            'pages' => ['present', 'array'],
            'pages.*' => ['string', Rule::in(array_keys(WhatsappSetting::PAGE_LABELS))],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'phone_number' => 'WhatsApp number',
            'message' => 'opening message',
            'popup_title' => 'card title',
            'popup_description' => 'card description',
            'popup_note' => 'reply note',
            'corner' => 'corner',
            'offset_x' => 'horizontal offset',
            'offset_y' => 'vertical offset',
            'icon_width' => 'icon width',
            'icon_height' => 'icon height',
            'icon_color' => 'icon color',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'icon_color.regex' => 'Choose a color in the form #25d366.',
        ];
    }

    private function blankToNull(string $key): ?string
    {
        $value = trim((string) $this->input($key));

        return $value === '' ? null : $value;
    }
}
