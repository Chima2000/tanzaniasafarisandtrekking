@php
    $imagesText = old('images_text', implode("\n", array_values(array_filter((array) $package->images))));
    $includesText = old('includes_text', implode("\n", array_values(array_filter((array) $package->includes))));
    $excludesText = old('excludes_text', implode("\n", array_values(array_filter((array) $package->excludes))));
@endphp

<div class="row">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header"><h4 class="card-title mb-0">Package details</h4></div>
            <div class="card-body pt-0">
                <div class="mb-3">
                    <label class="form-label" for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $package->name) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="slug">Slug</label>
                    <input type="text" class="form-control" id="slug" name="slug" value="{{ old('slug', $package->slug) }}" placeholder="auto-from-name">
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="location">Location</label>
                        <input type="text" class="form-control" id="location" name="location" value="{{ old('location', $package->location) }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="duration">Duration</label>
                        <input type="text" class="form-control" id="duration" name="duration" value="{{ old('duration', $package->duration) }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="category">Category</label>
                        <input type="text" class="form-control" id="category" name="category" value="{{ old('category', $package->category) }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="rating">Rating</label>
                        <input type="text" class="form-control" id="rating" name="rating" value="{{ old('rating', $package->rating ?? '5.0') }}">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="overview">Overview</label>
                    <textarea class="form-control" id="overview" name="overview" rows="5">{{ old('overview', $package->overview) }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="includes_text">Includes <span class="text-muted fw-normal">(one item per line)</span></label>
                    <textarea class="form-control" id="includes_text" name="includes_text" rows="4">{{ $includesText }}</textarea>
                </div>
                <div class="mb-0">
                    <label class="form-label" for="excludes_text">Excludes <span class="text-muted fw-normal">(one item per line)</span></label>
                    <textarea class="form-control" id="excludes_text" name="excludes_text" rows="4">{{ $excludesText }}</textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card">
            <div class="card-header"><h4 class="card-title mb-0">Media & publishing</h4></div>
            <div class="card-body pt-0">
                <div class="mb-3">
                    <label class="form-label" for="image">Primary image URL</label>
                    <input type="text" class="form-control" id="image" name="image" value="{{ old('image', $package->image) }}" placeholder="/photos/... or full URL">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="images_text">Gallery URLs <span class="text-muted fw-normal">(one per line)</span></label>
                    <textarea class="form-control" id="images_text" name="images_text" rows="5">{{ $imagesText }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="sort_order">Sort order</label>
                    <input type="number" class="form-control" id="sort_order" name="sort_order" min="0" value="{{ old('sort_order', $package->sort_order ?? 0) }}">
                </div>
                <div class="form-check form-switch mb-2">
                    <input class="form-check-input" type="checkbox" role="switch" id="is_published" name="is_published" value="1" @checked(old('is_published', $package->is_published ?? true))>
                    <label class="form-check-label" for="is_published">Published</label>
                </div>
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" role="switch" id="is_featured" name="is_featured" value="1" @checked(old('is_featured', $package->is_featured ?? false))>
                    <label class="form-check-label" for="is_featured">Featured</label>
                </div>
                <button type="submit" class="btn btn-primary w-100">{{ $submitLabel }}</button>
            </div>
        </div>
    </div>
</div>