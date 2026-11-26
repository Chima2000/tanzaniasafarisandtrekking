<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Validation\Validator;

class FormSpamGuard
{
    public const HONEYPOT_FIELD = 'company_website';

    public const STARTED_AT_FIELD = 'form_started_at';

    public const TOKEN_FIELD = 'form_guard';

    /**
     * @return array{started_at: int, token: string}
     */
    public static function issue(?int $startedAt = null): array
    {
        $startedAt ??= now()->getTimestamp();

        return [
            'started_at' => $startedAt,
            'token' => self::sign($startedAt),
        ];
    }

    public static function sign(int $startedAt): string
    {
        return hash_hmac('sha256', (string) $startedAt, (string) config('app.key'));
    }

    public static function minimumSeconds(): int
    {
        return app()->environment('testing') ? 0 : 2;
    }

    public static function maximumSeconds(): int
    {
        return 60 * 60 * 6;
    }

    /**
     * @return array<string, list<string>>
     */
    public static function rules(): array
    {
        return [
            self::HONEYPOT_FIELD => ['nullable', 'string', 'max:0'],
            self::STARTED_AT_FIELD => ['required', 'integer'],
            self::TOKEN_FIELD => ['required', 'string', 'size:64'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function messages(): array
    {
        return [
            self::HONEYPOT_FIELD.'.max' => 'Unable to submit this form. Please try again.',
            self::STARTED_AT_FIELD.'.required' => 'Unable to submit this form. Please refresh and try again.',
            self::TOKEN_FIELD.'.required' => 'Unable to submit this form. Please refresh and try again.',
            self::TOKEN_FIELD.'.size' => 'Unable to submit this form. Please refresh and try again.',
        ];
    }

    public static function validate(Request $request, Validator $validator): void
    {
        if ($validator->errors()->isNotEmpty()) {
            return;
        }

        $honeypot = trim((string) $request->input(self::HONEYPOT_FIELD, ''));
        if ($honeypot !== '') {
            $validator->errors()->add(self::HONEYPOT_FIELD, 'Unable to submit this form. Please try again.');

            return;
        }

        $startedAt = (int) $request->input(self::STARTED_AT_FIELD, 0);
        $token = (string) $request->input(self::TOKEN_FIELD, '');
        $expected = self::sign($startedAt);

        if ($startedAt <= 0 || ! hash_equals($expected, $token)) {
            $validator->errors()->add(self::TOKEN_FIELD, 'Unable to submit this form. Please refresh and try again.');

            return;
        }

        $elapsed = now()->getTimestamp() - $startedAt;

        if ($elapsed < self::minimumSeconds() || $elapsed > self::maximumSeconds()) {
            $validator->errors()->add(self::STARTED_AT_FIELD, 'Unable to submit this form. Please refresh and try again.');
        }
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    public static function mergeInto(array $payload): array
    {
        $issue = self::issue();

        return [
            ...$payload,
            self::HONEYPOT_FIELD => '',
            self::STARTED_AT_FIELD => $issue['started_at'],
            self::TOKEN_FIELD => $issue['token'],
        ];
    }
}
