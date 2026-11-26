<?php

namespace App\Http\Requests;

use App\Support\FormSpamGuard;
use App\Support\TourCatalog;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreContactSubmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $packageSlugs = app(TourCatalog::class)->packages()->pluck('slug')->all();

        return [
            'full_name' => ['required', 'string', 'max:200'],
            'email' => ['required', 'email', 'max:254'],
            'phone' => ['nullable', 'string', 'max:40'],
            'subject' => ['nullable', 'string', 'max:255'],
            'interest' => ['nullable', 'string', 'max:255'],
            'package' => ['nullable', 'string', Rule::in($packageSlugs)],
            'message' => ['nullable', 'string', 'max:5000'],
            'source' => ['nullable', 'string', Rule::in(['contact', 'package_enquiry'])],
            ...FormSpamGuard::rules(),
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            FormSpamGuard::validate($this, $validator);
        });
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'full_name.required' => 'Please enter your name.',
            'email.required' => 'Please enter your email address.',
            ...FormSpamGuard::messages(),
        ];
    }

    protected function prepareForValidation(): void
    {
        $first = trim((string) $this->input('fname', ''));
        $last = trim((string) $this->input('lname', ''));
        $username = trim((string) $this->input('username', ''));
        $fullName = trim((string) $this->input('full_name', ''));

        if ($fullName === '') {
            $fullName = trim($first.' '.$last);
        }

        if ($fullName === '') {
            $fullName = $username;
        }

        $interest = $this->input('interest');
        $package = $this->filled('package') ? (string) $this->input('package') : null;

        if ($package === null && is_string($interest) && $interest !== '' && $interest !== 'Tour inquiry') {
            $match = app(TourCatalog::class)->packages()->firstWhere('slug', $interest);
            if ($match !== null) {
                $package = $match['slug'];
            }
        }

        $this->merge([
            'full_name' => $fullName !== '' ? $fullName : null,
            'phone' => trim((string) $this->input('phone', '')) ?: null,
            'subject' => trim((string) $this->input('subject', '')) ?: null,
            'interest' => is_string($interest) && $interest !== '' ? $interest : null,
            'package' => $package,
            'message' => trim((string) $this->input('message', '')) ?: null,
            'source' => $this->filled('source') ? (string) $this->input('source') : 'contact',
        ]);
    }
}
