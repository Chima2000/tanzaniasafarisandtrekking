@php
    $inputId = $id ?? ('rich_'.\Illuminate\Support\Str::random(8));
    $inputName = $name;
    $rawValue = isset($oldKey) ? old($oldKey, $value ?? '') : ($value ?? '');
    $inputValue = \App\Support\RichText::prepareForEditor(is_string($rawValue) ? $rawValue : '');
    $rows = $rows ?? 6;
    $cmsField = $cmsField ?? null;
    $live = $live ?? false;
@endphp

<div class="rich-text-field" data-rich-text-field>
    <textarea
        class="d-none {{ $live ? 'cms-live-input' : '' }}"
        id="{{ $inputId }}"
        name="{{ $inputName }}"
        rows="{{ $rows }}"
        @if ($cmsField) data-cms-field="{{ $cmsField }}" @endif
        data-rich-text-input
    >{{ $inputValue }}</textarea>
    <div class="rich-text-toolbar-actions mb-1 d-flex flex-wrap gap-2">
        <button type="button" class="btn btn-sm btn-outline-secondary" data-rich-insert-image data-target="#{{ $inputId }}">
            Insert image
        </button>
        <button type="button" class="btn btn-sm btn-outline-secondary" data-rich-insert-video data-target="#{{ $inputId }}">
            Insert video
        </button>
    </div>
    <div class="rich-text-editor border rounded bg-white" data-rich-text-editor style="min-height: {{ max(120, ($rows ?? 6) * 24) }}px;"></div>
    <div class="form-text">Use the toolbar for bold, lists, links, images, and videos. Content is saved as rich HTML.</div>
</div>
