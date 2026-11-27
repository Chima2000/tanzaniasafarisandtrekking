<?php

namespace App\Http\Requests\Admin;

use App\Support\AdminPanelPath;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class UpdateAdminPanelPathRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    protected function prepareForValidation(): void
    {
        $path = $this->input('admin_panel_path');

        if (is_string($path)) {
            $this->merge([
                'admin_panel_path' => app(AdminPanelPath::class)->normalize($path),
            ]);
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'admin_panel_path' => [
                'required',
                'string',
                'min:6',
                'max:64',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::notIn(AdminPanelPath::reserved()),
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'admin_panel_path.regex' => 'Use only lowercase letters, numbers, and hyphens.',
            'admin_panel_path.not_in' => 'That path is reserved or too easy to guess. Choose another.',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $path = (string) $this->input('admin_panel_path');

            if (! app(AdminPanelPath::class)->isAllowed($path)) {
                $validator->errors()->add(
                    'admin_panel_path',
                    'Choose a different login path. Use 6–64 lowercase letters, numbers, and hyphens only, and avoid common admin names.'
                );
            }
        });
    }
}
