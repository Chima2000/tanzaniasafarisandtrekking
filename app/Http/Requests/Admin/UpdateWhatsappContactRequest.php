<?php

namespace App\Http\Requests\Admin;

use App\Models\WhatsappContact;
use App\Models\WhatsappSetting;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UpdateWhatsappContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'section' => $this->blankToNull('section'),
            'name' => trim((string) $this->input('name')),
            'title' => trim((string) $this->input('title')),
        ]);
    }

    protected function failedValidation(Validator $validator): void
    {
        $contact = $this->route('contact');

        session()->flash(
            'whatsapp_contact_edit_id',
            $contact instanceof WhatsappContact ? $contact->id : null,
        );

        parent::failedValidation($validator);
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'section' => ['nullable', 'string', 'max:40'],
            'name' => ['required', 'string', 'max:80'],
            'title' => ['required', 'string', 'max:80'],
            'phone_number' => [
                'required',
                'string',
                'max:32',
                function (string $attribute, mixed $value, Closure $fail): void {
                    WhatsappSetting::rejectShortPhone($value, $fail);
                },
            ],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'remove_photo' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'section' => 'section',
            'name' => 'name',
            'title' => 'title / position',
            'phone_number' => 'WhatsApp number',
            'photo' => 'profile photo',
        ];
    }

    private function blankToNull(string $key): ?string
    {
        $value = trim((string) $this->input($key));

        return $value === '' ? null : $value;
    }
}
