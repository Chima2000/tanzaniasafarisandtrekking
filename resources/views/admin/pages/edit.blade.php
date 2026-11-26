@extends('layouts.admin')

@section('title', 'Edit Page')

@section('content')
    <div class="page-title-box d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <h4 class="mb-1">Edit Page</h4>
            <p class="text-muted mb-0">{{ $page->title }} · <code>{{ $page->slug }}</code></p>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.pages.update', $page) }}">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Page content</h4>
                    </div>
                    <div class="card-body pt-0">
                        <div class="mb-3">
                            <label class="form-label" for="title">Title</label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $page->title) }}" required>
                        </div>

                        @foreach ($content as $key => $value)
                            <div class="mb-3">
                                <label class="form-label" for="content_{{ $key }}">{{ \Illuminate\Support\Str::headline($key) }}</label>
                                @if (is_string($value) && strlen($value) > 120)
                                    <textarea class="form-control" id="content_{{ $key }}" name="content[{{ $key }}]" rows="4">{{ old("content.{$key}", $value) }}</textarea>
                                @else
                                    <input type="text" class="form-control" id="content_{{ $key }}" name="content[{{ $key }}]" value="{{ old("content.{$key}", is_scalar($value) ? $value : json_encode($value)) }}">
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Publishing & SEO</h4>
                    </div>
                    <div class="card-body pt-0">
                        <div class="mb-3">
                            <label class="form-label" for="hero_image">Hero image URL</label>
                            <input type="text" class="form-control" id="hero_image" name="hero_image" value="{{ old('hero_image', $page->hero_image) }}" placeholder="/photos/... or full URL">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="seo_title">SEO title</label>
                            <input type="text" class="form-control" id="seo_title" name="seo_title" value="{{ old('seo_title', $page->seo_title) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="seo_description">SEO description</label>
                            <textarea class="form-control" id="seo_description" name="seo_description" rows="3">{{ old('seo_description', $page->seo_description) }}</textarea>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" role="switch" id="is_published" name="is_published" value="1" @checked(old('is_published', $page->is_published))>
                            <label class="form-check-label" for="is_published">Published</label>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection