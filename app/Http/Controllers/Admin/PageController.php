<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
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

    public function edit(Page $page): View
    {
        return view('admin.pages.edit', [
            'page' => $page,
            'content' => (array) $page->content,
        ]);
    }

    public function update(Request $request, Page $page): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:500'],
            'hero_image' => ['nullable', 'string', 'max:500'],
            'is_published' => ['sometimes', 'boolean'],
            'content' => ['nullable', 'array'],
            'content.*' => ['nullable', 'string', 'max:5000'],
        ]);

        $page->update([
            'title' => $validated['title'],
            'seo_title' => $validated['seo_title'] ?? null,
            'seo_description' => $validated['seo_description'] ?? null,
            'hero_image' => $validated['hero_image'] ?? null,
            'is_published' => $request->boolean('is_published'),
            'content' => $validated['content'] ?? [],
        ]);

        return redirect()
            ->route('admin.pages.edit', $page)
            ->with('success', 'Page updated successfully.');
    }
}
