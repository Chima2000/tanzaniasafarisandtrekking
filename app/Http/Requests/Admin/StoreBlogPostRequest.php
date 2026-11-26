<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreBlogPostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    protected function prepareForValidation(): void
    {
        if ($this->filled('title') && ! $this->filled('slug')) {
            $this->merge([
                'slug' => Str::slug((string) $this->input('title')),
            ]);
        }

        $this->merge([
            'is_published' => $this->boolean('is_published'),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('blog_posts', 'slug')],
            'category' => ['nullable', 'string', 'max:120'],
            'author' => ['nullable', 'string', 'max:120'],
            'image' => ['nullable', 'string', 'max:500'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:500'],
            'intro' => ['nullable', 'string'],
            'body' => ['nullable', 'string'],
            'tips_text' => ['nullable', 'string'],
            'tags_text' => ['nullable', 'string'],
            'quote_text' => ['nullable', 'string'],
            'quote_name' => ['nullable', 'string', 'max:120'],
            'sections_json' => ['nullable', 'string'],
            'cta_label' => ['nullable', 'string', 'max:120'],
            'cta_url_name' => ['nullable', 'string', 'max:120'],
            'cta_url_param' => ['nullable', 'string', 'max:120'],
            'is_published' => ['boolean'],
            'published_at' => ['nullable', 'date'],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function blogPayload(): array
    {
        $validated = $this->validated();
        $sections = [];

        if (! empty($validated['sections_json'])) {
            $decoded = json_decode($validated['sections_json'], true);
            if (is_array($decoded)) {
                $sections = $decoded;
            }
        }

        $quoteText = trim((string) ($validated['quote_text'] ?? ''));
        $tips = collect(preg_split('/\r\n|\r|\n/', (string) ($validated['tips_text'] ?? '')) ?: [])
            ->map(fn (string $line): string => trim($line))
            ->filter()
            ->values()
            ->all();
        $tags = collect(explode(',', (string) ($validated['tags_text'] ?? '')))
            ->map(fn (string $tag): string => trim($tag))
            ->filter()
            ->values()
            ->all();

        return [
            'title' => $validated['title'],
            'slug' => $validated['slug'],
            'category' => $validated['category'] ?? null,
            'author' => $validated['author'] ?? null,
            'image' => $validated['image'] ?? null,
            'seo_title' => $validated['seo_title'] ?? null,
            'seo_description' => $validated['seo_description'] ?? null,
            'intro' => $validated['intro'] ?? null,
            'body' => $validated['body'] ?? null,
            'quote' => $quoteText !== '' ? [
                'text' => $quoteText,
                'name' => $validated['quote_name'] ?? 'Admin',
            ] : null,
            'sections' => $sections,
            'tips' => $tips,
            'tags' => $tags,
            'cta_label' => $validated['cta_label'] ?? null,
            'cta_url_name' => $validated['cta_url_name'] ?? null,
            'cta_url_param' => $validated['cta_url_param'] ?? null,
            'is_published' => $validated['is_published'],
            'published_at' => $validated['published_at'] ?? null,
        ];
    }
}
