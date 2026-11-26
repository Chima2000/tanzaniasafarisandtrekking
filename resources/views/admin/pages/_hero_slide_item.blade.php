@php
    $slide = array_merge([
        'type' => 'image',
        'media' => '',
        'poster' => '',
        'position' => '50% 50%',
        'kicker' => '',
        'title' => '',
        'body' => '',
        'cta' => 'See Packages',
        'cta_url' => '/safari-packages',
        'badge_label' => 'Adventures start from',
        'badge_value' => '',
        'badge_text' => 'Packages',
    ], is_array($slide ?? null) ? $slide : []);
    $isVideo = ($slide['type'] ?? 'image') === 'video';
    $prefix = "content[hero_slides][{$index}]";
    $idPrefix = 'hero_slide_'.$index;
    $position = \App\Support\CmsMedia::positionParts($slide['position'] ?? null);
@endphp

<div class="card border mb-3 cms-hero-slide" data-hero-slide data-cms-section="{{ $sectionId }}">
    <div class="card-header d-flex align-items-center justify-content-between gap-2 py-2">
        <strong class="small mb-0">Slide <span data-hero-slide-number>{{ is_numeric($index) ? ((int) $index + 1) : '#' }}</span></strong>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-sm btn-outline-secondary" data-hero-slide-up title="Move up">↑</button>
            <button type="button" class="btn btn-sm btn-outline-secondary" data-hero-slide-down title="Move down">↓</button>
            <button type="button" class="btn btn-sm btn-outline-danger" data-hero-slide-remove>Remove</button>
        </div>
    </div>
    <div class="card-body pt-3">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Media type</label>
                <select class="form-select cms-live-input" name="{{ $prefix }}[type]" data-hero-slide-type>
                    <option value="image" @selected(! $isVideo)>Image</option>
                    <option value="video" @selected($isVideo)>Video</option>
                </select>
            </div>
            <div class="col-md-8">
                <label class="form-label" data-hero-media-label>{{ $isVideo ? 'Video file / URL' : 'Background image' }}</label>
                <div class="input-group">
                    <input
                        type="text"
                        class="form-control cms-live-input cms-image-url"
                        id="{{ $idPrefix }}_media"
                        name="{{ $prefix }}[media]"
                        value="{{ $slide['media'] }}"
                        placeholder="{{ $isVideo ? '/storage/...mp4 or upload' : '/photos/... or upload' }}"
                    >
                    <button
                        type="button"
                        class="btn btn-outline-secondary"
                        data-media-library-pick
                        data-target="#{{ $idPrefix }}_media"
                        data-category="{{ $isVideo ? 'video' : 'image' }}"
                        data-title="{{ $isVideo ? 'Choose video' : 'Choose image' }}"
                        data-hero-media-pick
                    >Library</button>
                    <label class="btn btn-outline-secondary mb-0">
                        Upload
                        <input
                            type="file"
                            class="d-none cms-media-upload"
                            accept="{{ $isVideo ? 'video/mp4,video/webm,video/quicktime' : 'image/*' }}"
                            data-target="#{{ $idPrefix }}_media"
                            data-hero-media-upload
                        >
                    </label>
                </div>
                <div class="cms-image-preview rounded border bg-light overflow-hidden mt-2 {{ $slide['media'] === '' || $isVideo ? 'd-none' : '' }}" style="height: 120px;" data-hero-image-preview>
                    <img src="{{ $slide['media'] }}" alt="" class="w-100 h-100" style="object-fit: cover; object-position: {{ $slide['position'] }};" data-preview-for="{{ $idPrefix }}_media">
                </div>
            </div>

            <div class="col-12 {{ $isVideo ? '' : 'd-none' }}" data-hero-poster-wrap>
                <label class="form-label">Video poster image</label>
                <div class="input-group">
                    <input
                        type="text"
                        class="form-control cms-live-input cms-image-url"
                        id="{{ $idPrefix }}_poster"
                        name="{{ $prefix }}[poster]"
                        value="{{ $slide['poster'] }}"
                        placeholder="/photos/... poster frame"
                    >
                    <button type="button" class="btn btn-outline-secondary" data-media-library-pick data-target="#{{ $idPrefix }}_poster" data-category="image" data-title="Choose poster">Library</button>
                    <label class="btn btn-outline-secondary mb-0">
                        Upload
                        <input type="file" class="d-none cms-media-upload" accept="image/*" data-target="#{{ $idPrefix }}_poster">
                    </label>
                </div>
            </div>

            <div class="col-12 {{ $isVideo ? 'd-none' : '' }}" data-hero-position-wrap>
                <label class="form-label">Image position</label>
                <input type="hidden" class="cms-live-input cms-position-value" id="{{ $idPrefix }}_position" name="{{ $prefix }}[position]" value="{{ $slide['position'] ?: '50% 50%' }}">
                <div class="row g-2 align-items-center">
                    <div class="col-6">
                        <label class="form-label small text-muted mb-1">Horizontal</label>
                        <input type="range" class="form-range cms-position-x" min="0" max="100" value="{{ $position['x'] }}" data-target="#{{ $idPrefix }}_position">
                    </div>
                    <div class="col-6">
                        <label class="form-label small text-muted mb-1">Vertical</label>
                        <input type="range" class="form-range cms-position-y" min="0" max="100" value="{{ $position['y'] }}" data-target="#{{ $idPrefix }}_position">
                    </div>
                </div>
                <div class="small text-muted mt-1 cms-position-label" data-label-for="{{ $idPrefix }}_position">{{ $slide['position'] ?: '50% 50%' }}</div>
            </div>

            <div class="col-md-6">
                <label class="form-label">Kicker</label>
                <input type="text" class="form-control cms-live-input" name="{{ $prefix }}[kicker]" value="{{ $slide['kicker'] }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Button label</label>
                <input type="text" class="form-control cms-live-input" name="{{ $prefix }}[cta]" value="{{ $slide['cta'] }}">
            </div>
            <div class="col-12">
                <label class="form-label">Title</label>
                <input type="text" class="form-control cms-live-input" name="{{ $prefix }}[title]" value="{{ $slide['title'] }}">
            </div>
            <div class="col-12">
                <label class="form-label">Body</label>
                <textarea class="form-control cms-live-input" name="{{ $prefix }}[body]" rows="3">{{ is_string($slide['body'] ?? null) ? $slide['body'] : '' }}</textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label">Button URL</label>
                <input type="text" class="form-control cms-live-input" name="{{ $prefix }}[cta_url]" value="{{ $slide['cta_url'] }}" placeholder="/safari-packages">
            </div>
            <div class="col-md-6">
                <label class="form-label">Badge label</label>
                <input type="text" class="form-control cms-live-input" name="{{ $prefix }}[badge_label]" value="{{ $slide['badge_label'] }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Badge value</label>
                <input type="text" class="form-control cms-live-input" name="{{ $prefix }}[badge_value]" value="{{ $slide['badge_value'] }}" placeholder="Safari / Trek / 12+">
            </div>
            <div class="col-md-6">
                <label class="form-label">Badge text</label>
                <input type="text" class="form-control cms-live-input" name="{{ $prefix }}[badge_text]" value="{{ $slide['badge_text'] }}">
            </div>
        </div>
    </div>
</div>
