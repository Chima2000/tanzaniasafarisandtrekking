<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Support\HomeHeroSlides;
use App\Support\PageBlueprint;
use App\Support\RichText;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController extends Controller
{
    public function index(): View
    {
        return view('admin.pages.index', [
            'pages' => Page::query()->orderBy('title')->get(),
        ]);
    }

    public function edit(Page $page, PageBlueprint $blueprint): View
    {
        $definition = $blueprint->for($page->slug) ?? [
            'title' => $page->title,
            'public_route' => null,
            'sections' => [[
                'id' => 'content',
                'label' => 'Content',
                'fields' => collect((array) $page->content)
                    ->keys()
                    ->map(fn (string $key): array => [
                        'key' => $key,
                        'label' => str($key)->headline()->toString(),
                        'type' => str_ends_with($key, '_position') ? 'position' : (str_contains($key, 'image') || str_ends_with($key, '_bg') ? 'image' : 'textarea'),
                        'rows' => 3,
                    ])
                    ->values()
                    ->all(),
            ]],
        ];

        $pageContent = (array) $page->content;
        $content = array_merge(
            $blueprint->defaultContent($page->slug),
            $pageContent
        );

        if ($page->slug === 'home') {
            if (! array_key_exists('hero_slides', $pageContent)) {
                unset($content['hero_slides']);
            }

            $content['hero_slides'] = app(HomeHeroSlides::class)->resolve($content);
        }

        $previewUrl = $blueprint->publicUrl($page->slug);

        return view('admin.pages.edit', [
            'page' => $page,
            'content' => $content,
            'blueprint' => $definition,
            'previewUrl' => $previewUrl
                ? $previewUrl.(str_contains($previewUrl, '?') ? '&' : '?').'cms_preview=1'
                : null,
        ]);
    }

    public function update(Request $request, Page $page, PageBlueprint $blueprint, HomeHeroSlides $heroSlides): RedirectResponse|JsonResponse
    {
        $validated = $this->validatedPayload($request, $page, $blueprint, $heroSlides);

        $page->update([
            'title' => $validated['title'],
            'seo_title' => $validated['seo_title'] ?? null,
            'seo_description' => $validated['seo_description'] ?? null,
            'seo_keywords' => $validated['seo_keywords'] ?? null,
            'hero_image' => $validated['hero_image'] ?? null,
            'is_published' => $request->boolean('is_published'),
            'content' => $validated['content'] ?? [],
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'ok' => true,
                'updated_at' => $page->fresh()->updated_at?->toIso8601String(),
                'message' => 'Page saved. Preview refreshed.',
            ]);
        }

        return redirect()
            ->route('admin.pages.edit', $page)
            ->with('success', 'Page updated successfully.');
    }

    /**
     * @return array{title: string, seo_title?: string|null, seo_description?: string|null, seo_keywords?: string|null, hero_image?: string|null, content?: array<string, mixed>}
     */
    private function validatedPayload(Request $request, Page $page, PageBlueprint $blueprint, HomeHeroSlides $heroSlides): array
    {
        $defaults = $blueprint->defaultContent($page->slug);
        $allowedKeys = array_keys($defaults);

        if ($allowedKeys === []) {
            $allowedKeys = array_keys((array) $page->content);
        }

        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:500'],
            'seo_keywords' => ['nullable', 'string', 'max:500'],
            'hero_image' => ['nullable', 'string', 'max:500'],
            'is_published' => ['sometimes', 'boolean'],
            'content' => ['nullable', 'array'],
        ];

        $fieldTypes = $this->fieldTypesFor($page->slug, $blueprint);

        foreach ($allowedKeys as $key) {
            if ($key === 'hero_slides') {
                $rules['content.hero_slides'] = ['nullable', 'array'];
                $rules['content.hero_slides.*'] = ['nullable', 'array'];
                $rules['content.hero_slides.*.type'] = ['nullable', 'in:image,video'];
                $rules['content.hero_slides.*.media'] = ['nullable', 'string', 'max:1000'];
                $rules['content.hero_slides.*.poster'] = ['nullable', 'string', 'max:1000'];
                $rules['content.hero_slides.*.position'] = ['nullable', 'string', 'max:40'];
                $rules['content.hero_slides.*.kicker'] = ['nullable', 'string', 'max:255'];
                $rules['content.hero_slides.*.title'] = ['nullable', 'string', 'max:255'];
                $rules['content.hero_slides.*.body'] = ['nullable', 'string', 'max:50000'];
                $rules['content.hero_slides.*.cta'] = ['nullable', 'string', 'max:120'];
                $rules['content.hero_slides.*.cta_url'] = ['nullable', 'string', 'max:500'];
                $rules['content.hero_slides.*.badge_label'] = ['nullable', 'string', 'max:120'];
                $rules['content.hero_slides.*.badge_value'] = ['nullable', 'string', 'max:80'];
                $rules['content.hero_slides.*.badge_text'] = ['nullable', 'string', 'max:80'];

                continue;
            }

            $max = ($fieldTypes[$key] ?? 'text') === 'richtext' || str_ends_with($key, '_json')
                ? 100000
                : 8000;
            $rules["content.{$key}"] = ['nullable', 'string', 'max:'.$max];
        }

        $validated = $request->validate($rules);
        $incoming = (array) ($validated['content'] ?? []);
        $content = [];

        foreach ($allowedKeys as $key) {
            if ($key === 'hero_slides') {
                $content[$key] = $heroSlides->sanitizeIncoming($incoming[$key] ?? []);

                continue;
            }

            $value = (string) ($incoming[$key] ?? '');

            if (($fieldTypes[$key] ?? 'text') === 'richtext') {
                $value = RichText::sanitize($value);
            }

            $content[$key] = $value;
        }

        $validated['content'] = $content;

        return $validated;
    }

    /**
     * @return array<string, string>
     */
    private function fieldTypesFor(string $slug, PageBlueprint $blueprint): array
    {
        $definition = $blueprint->for($slug);

        if ($definition === null) {
            return [];
        }

        $types = [];

        foreach ($definition['sections'] as $section) {
            foreach ($section['fields'] as $field) {
                $types[$field['key']] = $field['type'] ?? 'text';
            }
        }

        return $types;
    }
}
