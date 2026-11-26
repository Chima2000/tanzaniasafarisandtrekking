@if (! empty($previewUrl))
<div class="card mb-3">
    <div class="card-header d-flex align-items-center justify-content-between">
        <h4 class="card-title mb-0">Live preview</h4>
        <a href="{{ $previewUrl }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-secondary">Open page</a>
    </div>
    <div class="card-body p-0">
        <div style="height: 520px; border-top: 1px solid var(--bs-border-color);">
            <iframe src="{{ $previewUrl }}{{ str_contains($previewUrl, '?') ? '&' : '?' }}cms_preview=1" title="Live preview" style="width:100%;height:100%;border:0;"></iframe>
        </div>
    </div>
</div>
@endif
