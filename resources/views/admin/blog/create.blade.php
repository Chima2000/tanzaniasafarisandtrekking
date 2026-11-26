@extends('layouts.admin')

@section('title', 'Create Blog Post')

@section('content')
    <div class="page-title-box">
        <h4 class="mb-1">Create Blog Post</h4>
        <p class="text-muted mb-0">Add a new article for the landing website blog.</p>
    </div>

    <form method="POST" action="{{ route('admin.blog-posts.store') }}" data-upload-url="{{ route('admin.media.upload') }}">
        @csrf
        @include('admin.blog._form', ['submitLabel' => 'Create post'])
    </form>
@endsection

@push('scripts')
@include('admin.partials.media-upload-script')
@endpush
