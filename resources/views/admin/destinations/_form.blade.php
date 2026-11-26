@php
    $needlesText = old('needles_text', implode(', ', array_values(array_filter((array) $destination->needles))));
    $highlightsText = old('highlights_text', implode("\n", array_values(array_filter((array) $destination->highlights))));
    $wildlifeText = old('wildlife_text', implode(', ', array_values(array_filter((array) $destination->wildlife))));
    $tipsText = old('tips_text', implode("\n", array_values(array_filter((array) $destination->tips))));
@endphp

<div class="row">
    <div class="col-xl-8">
        <div class="card mb-3">
            <div class="card-header"><h4 class="card-title mb-0">Basics</h4></div>
            <div class="card-body pt-0">
                <div class="mb-3">
                    <label class="form-label" for="title">Title</label>
                    <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $destination->title) }}" required>
                </div>
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label class="form-label" for="slug">Slug</label>
                        <input type="text" class="form-control" id="slug" name="slug" value="{{ old('slug', $destination->slug) }}" placeholder="auto-from-title">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label" for="short">Short code</label>
                        <input type="text" class="form-control" id="short" name="short" maxlength="10" value="{{ old('short', $destination->short) }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="full_name">Full name</label>
                        <input type="text" class="form-control" id="full_name" name="full_name" value="{{ old('full_name', $destination->full_name) }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="region">Region</label>
                        <input type="text" class="form-control" id="region" name="region" value="{{ old('region', $destination->region) }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="needles_text">Search needles <span class="text-muted fw-normal">(comma separated)</span></label>
                        <input type="text" class="form-control" id="needles_text" name="needles_text" value="{{ $needlesText }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="starts">Starts / tours label</label>
                        <input type="text" class="form-control" id="starts" name="starts" value="{{ old('starts', $destination->starts) }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header"><h4 class="card-title mb-0">Story & content</h4></div>
            <div class="card-body pt-0">
                <div class="mb-3">
                    <label class="form-label" for="headline">Headline</label>
                    <input type="text" class="form-control" id="headline" name="headline" value="{{ old('headline', $destination->headline) }}">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="4">{{ old('description', $destination->description) }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="story_intro">Story intro</label>
                    <textarea class="form-control" id="story_intro" name="story_intro" rows="4">{{ old('story_intro', $destination->story_intro) }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="highlights_text">Highlights <span class="text-muted fw-normal">(one per line)</span></label>
                    <textarea class="form-control" id="highlights_text" name="highlights_text" rows="4">{{ $highlightsText }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="wildlife_text">Wildlife <span class="text-muted fw-normal">(comma separated)</span></label>
                    <input type="text" class="form-control" id="wildlife_text" name="wildlife_text" value="{{ $wildlifeText }}">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="best_time">Best time to visit</label>
                    <textarea class="form-control" id="best_time" name="best_time" rows="2">{{ old('best_time', $destination->best_time) }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="getting_there">Getting there</label>
                    <textarea class="form-control" id="getting_there" name="getting_there" rows="3">{{ old('getting_there', $destination->getting_there) }}</textarea>
                </div>
                <div class="mb-0">
                    <label class="form-label" for="tips_text">Tips <span class="text-muted fw-normal">(one per line)</span></label>
                    <textarea class="form-control" id="tips_text" name="tips_text" rows="4">{{ $tipsText }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card mb-3">
            <div class="card-header"><h4 class="card-title mb-0">SEO</h4></div>
            <div class="card-body pt-0">
                <div class="mb-3">
                    <label class="form-label" for="seo_title">SEO title</label>
                    <input type="text" class="form-control" id="seo_title" name="seo_title" value="{{ old('seo_title', $destination->seo_title) }}">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="seo_description">SEO description</label>
                    <textarea class="form-control" id="seo_description" name="seo_description" rows="3">{{ old('seo_description', $destination->seo_description) }}</textarea>
                </div>
                <div class="mb-0">
                    <label class="form-label" for="seo_keywords">SEO keywords</label>
                    <input type="text" class="form-control" id="seo_keywords" name="seo_keywords" value="{{ old('seo_keywords', $destination->seo_keywords) }}">
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h4 class="card-title mb-0">Publishing</h4></div>
            <div class="card-body pt-0">
                <div class="mb-3">
                    <label class="form-label" for="image">Image URL</label>
                    <input type="text" class="form-control" id="image" name="image" value="{{ old('image', $destination->image) }}" placeholder="/photos/... or full URL">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="sort_order">Sort order</label>
                    <input type="number" class="form-control" id="sort_order" name="sort_order" min="0" value="{{ old('sort_order', $destination->sort_order ?? 0) }}">
                </div>
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" role="switch" id="is_published" name="is_published" value="1" @checked(old('is_published', $destination->is_published ?? true))>
                    <label class="form-check-label" for="is_published">Published</label>
                </div>
                <button type="submit" class="btn btn-primary w-100">{{ $submitLabel }}</button>
            </div>
        </div>
    </div>
</div>