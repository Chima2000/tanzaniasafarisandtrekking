<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBlogPostRequest;
use App\Http\Requests\Admin\UpdateBlogPostRequest;
use App\Models\BlogPost;
use App\Support\CatalogSync;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BlogPostController extends Controller
{
    public function index(): View
    {
        return view('admin.blog.index', [
            'posts' => BlogPost::query()->latest()->paginate(12),
        ]);
    }

    public function create(): View
    {
        return view('admin.blog.create', [
            'post' => new BlogPost([
                'author' => 'Admin',
                'is_published' => false,
            ]),
        ]);
    }

    public function store(StoreBlogPostRequest $request): RedirectResponse
    {
        $post = BlogPost::query()->create($request->blogPayload());

        return redirect()
            ->route('admin.blog-posts.edit', $post)
            ->with('success', 'Blog post created successfully.');
    }

    public function edit(BlogPost $blogPost): View
    {
        return view('admin.blog.edit', [
            'post' => $blogPost,
        ]);
    }

    public function update(UpdateBlogPostRequest $request, BlogPost $blogPost): RedirectResponse
    {
        $blogPost->update($request->blogPayload());

        return redirect()
            ->route('admin.blog-posts.edit', $blogPost)
            ->with('success', 'Blog post updated successfully.');
    }

    public function destroy(BlogPost $blogPost): RedirectResponse
    {
        $blogPost->delete();

        return redirect()
            ->route('admin.blog-posts.index')
            ->with('success', 'Blog post deleted successfully.');
    }

    public function sync(CatalogSync $sync): RedirectResponse
    {
        $count = $sync->syncBlogPosts();

        return redirect()
            ->route('admin.blog-posts.index')
            ->with('success', "Synced {$count} blog posts from the website catalog.");
    }
}
