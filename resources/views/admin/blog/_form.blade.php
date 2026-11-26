@php
    $tipsText = old('tips_text', implode("\n", array_values(array_filter((array) $post->tips))));
    $tagsText = old('tags_text', implode(', ', array_values(array_filter((array) $post->tags))));
    $quote = (array) ($post->quote ?? []);
    $sectionsDefault = json_encode($post->sections ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
@endphp

<div class="row">
    <div class="col-xl-8">
        <div class="card mb-3">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label" for="title">Title</label>
                    <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $post->title) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="slug">Slug</label>
                    <input type="text" class="form-control" id="slug" name="slug" value="{{ old('slug', $post->slug) }}" placeholder="auto-from-title">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="intro">Intro</label>
                    <textarea class="form-control" id="intro" name="intro" rows="4">{{ old('intro', $post->intro) }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="body">Body</label>
                    <textarea class="form-control" id="body" name="body" rows="12">{{ old('body', $post->body) }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="tips_text">Tips <span class="text-muted fw-normal">(one per line)</span></label>
                    <textarea class="form-control" id="tips_text" name="tips_text" rows="4">{{ $tipsText }}</textarea>
                </div>
                <div class="mb-0">
                    <label class="form-label" for="sections_json">Sections JSON <span class="text-muted fw-normal">(advanced)</span></label>
                    <textarea class="form-control font-monospace small" id="sections_json" name="sections_json" rows="10">{{ old('sections_json', $sectionsDefault) }}</textarea>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h4 class="card-title mb-0">Quote & CTA</h4></div>
            <div class="card-body pt-0">
                <div class="mb-3">
                    <label class="form-label" for="quote_text">Quote text</label>
                    <textarea class="form-control" id="quote_text" name="quote_text" rows="3">{{ old('quote_text', $quote['text'] ?? '') }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="quote_name">Quote attribution</label>
                    <input type="text" class="form-control" id="quote_name" name="quote_name" value="{{ old('quote_name', $quote['name'] ?? 'Admin') }}">
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label" for="cta_label">CTA label</label>
                        <input type="text" class="form-control" id="cta_label" name="cta_label" value="{{ old('cta_label', $post->cta_label) }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label" for="cta_url_name">CTA route name</label>
                        <input type="text" class="form-control" id="cta_url_name" name="cta_url_name" value="{{ old('cta_url_name', $post->cta_url_name) }}" placeholder="packages.index">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label" for="cta_url_param">CTA route param</label>
                        <input type="text" class="form-control" id="cta_url_param" name="cta_url_param" value="{{ old('cta_url_param', $post->cta_url_param) }}">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label" for="category">Category</label>
                    <input type="text" class="form-control" id="category" name="category" value="{{ old('category', $post->category) }}">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="author">Author</label>
                    <input type="text" class="form-control" id="author" name="author" value="{{ old('author', $post->author) }}">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="tags_text">Tags <span class="text-muted fw-normal">(comma separated)</span></label>
                    <input type="text" class="form-control" id="tags_text" name="tags_text" value="{{ $tagsText }}">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="image">Image URL</label>
                    <input type="text" class="form-control" id="image" name="image" value="{{ old('image', $post->image) }}" placeholder="/photos/... or full URL">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="seo_title">SEO title</label>
                    <input type="text" class="form-control" id="seo_title" name="seo_title" value="{{ old('seo_title', $post->seo_title) }}">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="seo_description">SEO description</label>
                    <textarea class="form-control" id="seo_description" name="seo_description" rows="3">{{ old('seo_description', $post->seo_description) }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="published_at">Publish date</label>
                    <input type="datetime-local" class="form-control" id="published_at" name="published_at" value="{{ old('published_at', optional($post->published_at)->format('Y-m-d\TH:i')) }}">
                </div>
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" role="switch" id="is_published" name="is_published" value="1" @checked(old('is_published', $post->is_published))>
                    <label class="form-check-label" for="is_published">Published</label>
                </div>
                <button type="submit" class="btn btn-primary w-100">{{ $submitLabel }}</button>
            </div>
        </div>
    </div>
</div>