<?php

namespace App\Http\Requests\Admin;

use App\Models\NotificationRecipient;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreNotificationRecipientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => $this->blankToNull('name'),
            'email' => strtolower(trim((string) $this->input('email'))),
            'notification_types' => array_values(array_filter((array) $this->input('notification_types', []))),
            'is_active' => $this->boolean('is_active'),
        ]);
    }

    protected function failedValidation(Validator $validator): void
    {
        session()->flash('notification_recipient_form', 'create');

        parent::failedValidation($validator);
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['nullable', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:254', Rule::unique('notification_recipients', 'email')],
            'notification_types' => ['required', 'array', 'min:1'],
            'notification_types.*' => ['string', Rule::in(NotificationRecipient::TYPES)],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'name',
            'email' => 'email address',
            'notification_types' => 'notification types',
            'notification_types.*' => 'notification type',
            'is_active' => 'active status',
        ];
    }

    private function blankToNull(string $key): ?string
    {
        $value = trim((string) $this->input($key));

        return $value === '' ? null : $value;
    }
}
