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
                    @include('admin.partials.rich-text-field', [
                        'id' => 'overview',
                        'name' => 'overview',
                        'value' => old('overview', $package->overview),
                        'rows' => 6,
                        'oldKey' => 'overview',
                    ])
                </div>
                <div class="mb-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <label class="form-label mb-0">Day-by-day itinerary</label>
                        <button type="button" class="btn btn-sm btn-outline-primary" data-itinerary-add>Add day</button>
                    </div>
                    <div class="vstack gap-3" data-itinerary-list>
                        @php
                            $itinerary = old('itinerary', $package->itinerary ?? []);
                            if (! is_array($itinerary) || $itinerary === []) {
                                $itinerary = [['day' => 'Day 1', 'title' => '', 'description' => '', 'meals' => '', 'destinations' => '']];
                            }
                        @endphp
                        @foreach ($itinerary as $index => $day)
                            <div class="border rounded p-3 bg-light-subtle" data-itinerary-day>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <strong>Day {{ $index + 1 }}</strong>
                                    <button type="button" class="btn btn-sm btn-outline-danger" data-itinerary-remove>Remove</button>
                                </div>
                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <label class="form-label">Label</label>
                                        <input type="text" class="form-control" name="itinerary[{{ $index }}][day]" value="{{ $day['day'] ?? ('Day '.($index + 1)) }}">
                                    </div>
                                    <div class="col-md-8">
                                        <label class="form-label">Title</label>
                                        <input type="text" class="form-control" name="itinerary[{{ $index }}][title]" value="{{ $day['title'] ?? '' }}">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Description</label>
                                        @include('admin.partials.rich-text-field', [
                                            'id' => 'itinerary_'.$index.'_description',
                                            'name' => 'itinerary['.$index.'][description]',
                                            'value' => $day['description'] ?? '',
                                            'rows' => 4,
                                            'oldKey' => 'itinerary.'.$index.'.description',
                                        ])
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Meals</label>
                                        <input type="text" class="form-control" name="itinerary[{{ $index }}][meals]" value="{{ $day['meals'] ?? '' }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Destinations</label>
                                        <input type="text" class="form-control" name="itinerary[{{ $index }}][destinations]" value="{{ $day['destinations'] ?? '' }}">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <template data-itinerary-template>
                        <div class="border rounded p-3 bg-light-subtle" data-itinerary-day>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <strong>Day __INDEX_DISPLAY__</strong>
                                <button type="button" class="btn btn-sm btn-outline-danger" data-itinerary-remove>Remove</button>
                            </div>
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <label class="form-label">Label</label>
                                    <input type="text" class="form-control" name="itinerary[__INDEX__][day]" value="Day __INDEX_DISPLAY__">
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label">Title</label>
                                    <input type="text" class="form-control" name="itinerary[__INDEX__][title]" value="">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" name="itinerary[__INDEX__][description]" rows="4" data-rich-pending></textarea>
                                    <div class="form-text">Save the package, then reopen to edit this day with the full rich text toolbar — or fill plain text now.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Meals</label>
                                    <input type="text" class="form-control" name="itinerary[__INDEX__][meals]" value="">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Destinations</label>
                                    <input type="text" class="form-control" name="itinerary[__INDEX__][destinations]" value="">
                                </div>
                            </div>
                        </div>
                    </template>
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
        <div class="card mt-3">
            <div class="card-header"><h4 class="card-title mb-0">Pricing (per person)</h4></div>
            <div class="card-body pt-0">
                @php
                    $cost = (float) old('cost_per_person', $package->cost_per_person ?? 0);
                    $profit = (float) old('profit_per_person', $package->profit_per_person ?? 0);
                    $sell = $cost + $profit;
                    $blockedDatesText = old('blocked_dates_text', implode("\n", array_values(array_filter((array) $package->blocked_dates))));
                @endphp
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label" for="cost_per_person">Cost / person (USD)</label>
                        <input type="number" step="0.01" min="0" class="form-control" id="cost_per_person" name="cost_per_person" value="{{ old('cost_per_person', $package->cost_per_person) }}" placeholder="e.g. 1800">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label" for="profit_per_person">Profit / person (USD)</label>
                        <input type="number" step="0.01" min="0" class="form-control" id="profit_per_person" name="profit_per_person" value="{{ old('profit_per_person', $package->profit_per_person) }}" placeholder="e.g. 625">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Sell price / person</label>
                        <div class="form-control-plaintext fw-semibold" id="sell_price_preview">${{ number_format($sell, 2) }}</div>
                        <div class="form-text">Shown publicly as price per traveler.</div>
                    </div>
                </div>
                <div class="mb-0">
                    <label class="form-label" for="blocked_dates_text">Blocked dates <span class="text-muted fw-normal">(YYYY-MM-DD, one per line)</span></label>
                    <textarea class="form-control" id="blocked_dates_text" name="blocked_dates_text" rows="4" placeholder="2026-12-25">{{ $blockedDatesText }}</textarea>
                    <div class="form-text">These dates are unavailable in the package calendar.</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card">
            <div class="card-header"><h4 class="card-title mb-0">Media & publishing</h4></div>
            <div class="card-body pt-0">
                <div class="mb-3">
                    <label class="form-label" for="image">Primary image</label>
                    <div class="input-group">
                        <input type="text" class="form-control cms-image-url" id="image" name="image" value="{{ old('image', $package->image) }}" placeholder="/photos/... or upload">
                        <button type="button" class="btn btn-outline-secondary" data-media-library-pick data-target="#image" data-title="Choose image">Library</button>
                        <label class="btn btn-outline-secondary mb-0">
                            Upload
                            <input type="file" class="d-none cms-image-upload" accept="image/*" data-target="#image">
                        </label>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="images_text">Gallery URLs <span class="text-muted fw-normal">(one per line, or pick from library)</span></label>
                    <textarea class="form-control" id="images_text" name="images_text" rows="5">{{ $imagesText }}</textarea>
                    <div class="d-flex flex-wrap gap-2 mt-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary" data-media-library-pick data-target="#images_text" data-mode="multiple" data-title="Add gallery images">Library</button>
                        <label class="btn btn-sm btn-outline-secondary mb-0">
                            Upload gallery image
                            <input type="file" class="d-none cms-gallery-upload" accept="image/*" data-target="#images_text">
                        </label>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="sort_order">Sort order</label>
                    <input type="number" class="form-control" id="sort_order" name="sort_order" min="0" value="{{ old('sort_order', $package->sort_order ?? 0) }}">
                </div>
                <div class="card border mb-3">
                    <div class="card-header py-2"><h5 class="card-title mb-0 fs-6">SEO</h5></div>
                    <div class="card-body pt-0">
                        <div class="mb-3">
                            <label class="form-label" for="seo_title">SEO title</label>
                            <input type="text" class="form-control" id="seo_title" name="seo_title" value="{{ old('seo_title', $package->seo_title) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="seo_description">SEO description</label>
                            <textarea class="form-control" id="seo_description" name="seo_description" rows="3">{{ old('seo_description', $package->seo_description) }}</textarea>
                        </div>
                        <div class="mb-0">
                            <label class="form-label" for="seo_keywords">SEO keywords</label>
                            <input type="text" class="form-control" id="seo_keywords" name="seo_keywords" value="{{ old('seo_keywords', $package->seo_keywords) }}" placeholder="Comma-separated keywords">
                        </div>
                    </div>
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

@once
@push('scripts')
<script>
(() => {
    const cost = document.getElementById('cost_per_person');
    const profit = document.getElementById('profit_per_person');
    const preview = document.getElementById('sell_price_preview');
    if (cost && profit && preview) {
        const refresh = () => {
            const total = (Number(cost.value) || 0) + (Number(profit.value) || 0);
            preview.textContent = `$${total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
        };

        cost.addEventListener('input', refresh);
        profit.addEventListener('input', refresh);
    }

    const list = document.querySelector('[data-itinerary-list]');
    const template = document.querySelector('[data-itinerary-template]');
    document.querySelector('[data-itinerary-add]')?.addEventListener('click', () => {
        if (!list || !template) return;
        const index = list.querySelectorAll('[data-itinerary-day]').length;
        const html = template.innerHTML
            .replaceAll('__INDEX__', String(index))
            .replaceAll('__INDEX_DISPLAY__', String(index + 1));
        list.insertAdjacentHTML('beforeend', html);
    });
    list?.addEventListener('click', (event) => {
        if (!event.target.closest('[data-itinerary-remove]')) return;
        const day = event.target.closest('[data-itinerary-day]');
        if (!day || list.querySelectorAll('[data-itinerary-day]').length <= 1) return;
        day.remove();
    });
})();
</script>
@endpush
@endonce