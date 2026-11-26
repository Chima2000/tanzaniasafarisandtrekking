@extends('layouts.admin')

@section('title', 'Edit Blog Post')

@section('content')
    <div class="page-title-box d-flex align-items-center justify-content-between">
        <div>
            <h4 class="mb-1">Edit Blog Post</h4>
            <p class="text-muted mb-0">{{ $post->title }}</p>
        </div>
        <a href="{{ route('blog.show', $post->slug) }}" class="btn btn-outline-primary" target="_blank" rel="noopener">Preview</a>
    </div>

    <form method="POST" action="{{ route('admin.blog-posts.update', $post) }}">
        @csrf
        @method('PUT')
        @include('admin.blog._form', ['submitLabel' => 'Save changes'])
    </form>
@endsection
