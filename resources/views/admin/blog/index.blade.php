@extends('layouts.admin')

@section('title', 'Blog Posts')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="mb-1">Blog Posts</h4>
                    <p class="text-muted mb-0">Create and manage articles for the public blog.</p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <form action="{{ route('admin.blog-posts.sync') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-primary">Sync from catalog</button>
                    </form>
                    <a href="{{ route('admin.blog-posts.create') }}" class="btn btn-primary">Add post</a>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Updated</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($posts as $post)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $post->title }}</div>
                                    <div class="text-muted small">{{ $post->slug }}</div>
                                </td>
                                <td>{{ $post->category ?: '—' }}</td>
                                <td>
                                    @if ($post->is_published)
                                        <span class="badge bg-primary-subtle text-primary">Published</span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary">Draft</span>
                                    @endif
                                </td>
                                <td>{{ $post->updated_at?->diffForHumans() }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.blog-posts.edit', $post) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <form action="{{ route('admin.blog-posts.destroy', $post) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this post?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-muted">No managed posts yet. The public site still shows catalog articles until you publish here.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $posts->links() }}</div>
        </div>
    </div>
@endsection
