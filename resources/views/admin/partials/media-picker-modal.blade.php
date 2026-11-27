<link rel="stylesheet" href="{{ admin_asset('css/media-library.css') }}?v={{ filemtime(public_path('panel-assets/css/media-library.css')) }}">
<div
    class="klt-media-picker-overlay"
    data-media-picker
    data-browse-url="{{ route('admin.media.browse') }}"
    data-upload-url="{{ route('admin.media.store') }}"
    data-as-gallery-url="{{ rtrim(route('admin.media.index'), '/') }}"
>
    <div class="klt-media-picker-backdrop" data-picker-backdrop></div>
    <div class="klt-media-picker-panel card border-0 mb-0" role="dialog" aria-modal="true" aria-labelledby="kltMediaPickerTitle">
        <div class="d-flex align-items-center justify-content-between px-3 px-sm-4 py-3 border-bottom">
            <h2 class="h5 mb-0 text-truncate" id="kltMediaPickerTitle" data-picker-title>Media library</h2>
            <button type="button" class="btn-close" data-picker-close aria-label="Close"></button>
        </div>
        <div class="d-flex flex-column flex-sm-row flex-wrap align-items-sm-center gap-2 px-3 px-sm-4 py-3 border-bottom">
            <input type="search" class="form-control" style="max-width:18rem;" placeholder="Search media…" data-picker-query>
            <label class="btn btn-primary mb-0">
                Upload
                <input type="file" accept="image/*" multiple class="d-none" data-picker-upload>
            </label>
            <div class="flex-grow-1" data-picker-progress hidden>
                <div class="klt-media-progress" style="min-width:10rem;">
                    <span style="width:35%;"></span>
                </div>
            </div>
            <p class="mb-0 small text-muted" data-picker-hint-multiple>Select multiple images, then click Add selected.</p>
            <p class="mb-0 small text-muted" data-picker-hint-single>Select an image, double-click to insert, or click Insert image.</p>
        </div>
        <div class="flex-grow-1 overflow-auto p-3 p-sm-4">
            <div class="klt-media-picker-grid" data-picker-grid></div>
            <p class="text-center text-muted py-5 mb-0" data-picker-empty hidden>No images found. Upload one above.</p>
            <p class="text-center text-muted py-5 mb-0" data-picker-loading hidden>Loading…</p>
        </div>
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 px-3 px-sm-4 py-3 border-top">
            <div class="d-flex align-items-center justify-content-center justify-content-sm-start gap-2">
                <button type="button" class="btn btn-light btn-sm" data-picker-prev>Previous</button>
                <span class="small text-muted" data-picker-page>Page 1 of 1</span>
                <button type="button" class="btn btn-light btn-sm" data-picker-next>Next</button>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-light" data-picker-close>Cancel</button>
                <button type="button" class="btn btn-primary" data-picker-confirm disabled>Insert image</button>
            </div>
        </div>
    </div>
</div>
