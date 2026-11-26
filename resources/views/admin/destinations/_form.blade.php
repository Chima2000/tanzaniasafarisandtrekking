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
                    @include('admin.partials.rich-text-field', [
                        'id' => 'description',
                        'name' => 'description',
                        'value' => old('description', $destination->description),
                        'rows' => 4,
                        'oldKey' => 'description',
                    ])
                </div>
                <div class="mb-3">
                    <label class="form-label" for="story_intro">Story intro</label>
                    @include('admin.partials.rich-text-field', [
                        'id' => 'story_intro',
                        'name' => 'story_intro',
                        'value' => old('story_intro', $destination->story_intro),
                        'rows' => 5,
                        'oldKey' => 'story_intro',
                    ])
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
                    @include('admin.partials.rich-text-field', [
                        'id' => 'best_time',
                        'name' => 'best_time',
                        'value' => old('best_time', $destination->best_time),
                        'rows' => 3,
                        'oldKey' => 'best_time',
                    ])
                </div>
                <div class="mb-3">
                    <label class="form-label" for="getting_there">Getting there</label>
                    @include('admin.partials.rich-text-field', [
                        'id' => 'getting_there',
                        'name' => 'getting_there',
                        'value' => old('getting_there', $destination->getting_there),
                        'rows' => 4,
                        'oldKey' => 'getting_there',
                    ])
                </div>
                <div class="mb-0">
                    <label class="form-label" for="tips_text">Tips <span class="text-muted fw-normal">(one per line)</span></label>
                    <textarea class="form-control" id="tips_text" name="tips_text" rows="4">{{ $tipsText }}</textarea>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Story chapters</h4>
                <button type="button" class="btn btn-sm btn-outline-primary" data-chapter-add>Add chapter</button>
            </div>
            <div class="card-body pt-0">
                @php
                    $chapters = old('story_chapters', $destination->story_chapters ?? []);
                    if (! is_array($chapters) || $chapters === []) {
                        $chapters = [['label' => 'Chapter 01', 'title' => '', 'body' => '', 'points_text' => '']];
                    }
                @endphp
                <div class="vstack gap-3" data-chapter-list>
                    @foreach ($chapters as $index => $chapter)
                        @php
                            $pointsText = is_array($chapter['points'] ?? null)
                                ? implode("\n", $chapter['points'])
                                : (string) ($chapter['points_text'] ?? '');
                        @endphp
                        <div class="border rounded p-3" data-chapter-item>
                            <div class="d-flex justify-content-between mb-2">
                                <strong>Chapter {{ $index + 1 }}</strong>
                                <button type="button" class="btn btn-sm btn-outline-danger" data-chapter-remove>Remove</button>
                            </div>
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <label class="form-label">Label</label>
                                    <input type="text" class="form-control" name="story_chapters[{{ $index }}][label]" value="{{ $chapter['label'] ?? '' }}">
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label">Title</label>
                                    <input type="text" class="form-control" name="story_chapters[{{ $index }}][title]" value="{{ $chapter['title'] ?? '' }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Body</label>
                                    @include('admin.partials.rich-text-field', [
                                        'id' => 'story_chapter_'.$index.'_body',
                                        'name' => 'story_chapters['.$index.'][body]',
                                        'value' => $chapter['body'] ?? '',
                                        'rows' => 4,
                                        'oldKey' => 'story_chapters.'.$index.'.body',
                                    ])
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Points <span class="text-muted fw-normal">(one per line)</span></label>
                                    <textarea class="form-control" name="story_chapters[{{ $index }}][points_text]" rows="3">{{ $pointsText }}</textarea>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header"><h4 class="card-title mb-0">Facts & FAQs</h4></div>
            <div class="card-body pt-0">
                @php
                    $facts = old('facts', $destination->facts ?? []);
                    $factsText = is_array($facts)
                        ? collect($facts)->map(function ($fact) {
                            if (is_array($fact)) {
                                return trim(($fact['label'] ?? '').': '.($fact['value'] ?? ''));
                            }

                            return (string) $fact;
                        })->implode("\n")
                        : (string) $facts;
                    $faqs = old('faqs', $destination->faqs ?? []);
                    $faqsText = is_array($faqs)
                        ? collect($faqs)->map(function ($faq) {
                            if (! is_array($faq)) {
                                return (string) $faq;
                            }

                            return trim(($faq['question'] ?? '')."\n".($faq['answer'] ?? ''));
                        })->filter()->implode("\n\n")
                        : (string) $faqs;
                @endphp
                <div class="mb-3">
                    <label class="form-label" for="facts_text">Facts <span class="text-muted fw-normal">(Label: Value, one per line)</span></label>
                    <textarea class="form-control" id="facts_text" name="facts_text" rows="5">{{ old('facts_text', $factsText) }}</textarea>
                </div>
                <div class="mb-0">
                    <label class="form-label" for="faqs_text">FAQs <span class="text-muted fw-normal">(question, then answer; blank line between pairs)</span></label>
                    <textarea class="form-control" id="faqs_text" name="faqs_text" rows="8">{{ old('faqs_text', $faqsText) }}</textarea>
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
                    <label class="form-label" for="image">Image</label>
                    <div class="input-group">
                        <input type="text" class="form-control cms-image-url" id="image" name="image" value="{{ old('image', $destination->image) }}" placeholder="/photos/... or upload">
                        <button type="button" class="btn btn-outline-secondary" data-media-library-pick data-target="#image" data-title="Choose image">Library</button>
                        <label class="btn btn-outline-secondary mb-0">
                            Upload
                            <input type="file" class="d-none cms-image-upload" accept="image/*" data-target="#image">
                        </label>
                    </div>
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

@once
@push('scripts')
<script>
(() => {
    const list = document.querySelector('[data-chapter-list]');
    document.querySelector('[data-chapter-add]')?.addEventListener('click', () => {
        if (!list) return;
        const index = list.querySelectorAll('[data-chapter-item]').length;
        const html = `
            <div class="border rounded p-3" data-chapter-item>
                <div class="d-flex justify-content-between mb-2">
                    <strong>Chapter ${index + 1}</strong>
                    <button type="button" class="btn btn-sm btn-outline-danger" data-chapter-remove>Remove</button>
                </div>
                <div class="row g-2">
                    <div class="col-md-4">
                        <label class="form-label">Label</label>
                        <input type="text" class="form-control" name="story_chapters[${index}][label]" value="Chapter ${String(index + 1).padStart(2, '0')}">
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Title</label>
                        <input type="text" class="form-control" name="story_chapters[${index}][title]" value="">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Body</label>
                        <textarea class="form-control" name="story_chapters[${index}][body]" rows="4"></textarea>
                        <div class="form-text">Save and reopen to use the rich text toolbar on new chapters, or paste HTML/plain text now.</div>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Points <span class="text-muted fw-normal">(one per line)</span></label>
                        <textarea class="form-control" name="story_chapters[${index}][points_text]" rows="3"></textarea>
                    </div>
                </div>
            </div>`;
        list.insertAdjacentHTML('beforeend', html);
    });
    list?.addEventListener('click', (event) => {
        if (!event.target.closest('[data-chapter-remove]')) return;
        event.target.closest('[data-chapter-item]')?.remove();
    });
})();
</script>
@endpush
@endonce