@php
    $fieldKey = $field['key'];
    $fieldType = $field['type'] ?? 'text';
    $fieldId = 'content_'.$fieldKey;
    $value = old("content.{$fieldKey}", $content[$fieldKey] ?? '');
    $position = \App\Support\CmsMedia::positionParts(is_string($value) ? $value : null);
@endphp

<div class="mb-3 cms-field" data-cms-field-key="{{ $fieldKey }}" data-cms-section="{{ $sectionId }}">
    @if ($fieldType !== 'hero_slides')
        <label class="form-label" for="{{ $fieldId }}">{{ $field['label'] }}</label>
    @endif

    @if ($fieldType === 'hero_slides')
        @include('admin.pages._hero_slides_field', [
            'field' => $field,
            'content' => $content,
            'sectionId' => $sectionId,
            'value' => is_array($value) ? $value : [],
        ])
    @elseif ($fieldType === 'textarea')
        <textarea
            class="form-control cms-live-input"
            id="{{ $fieldId }}"
            name="content[{{ $fieldKey }}]"
            rows="{{ $field['rows'] ?? 3 }}"
            data-cms-field="{{ $fieldKey }}"
        >{{ $value }}</textarea>
    @elseif ($fieldType === 'richtext')
        @include('admin.partials.rich-text-field', [
            'id' => $fieldId,
            'name' => 'content['.$fieldKey.']',
            'value' => is_string($value) ? $value : '',
            'rows' => $field['rows'] ?? 5,
            'cmsField' => $fieldKey,
            'live' => true,
            'oldKey' => 'content.'.$fieldKey,
        ])
    @elseif ($fieldType === 'image')
        <div class="cms-image-field">
            <div class="input-group mb-2">
                <input
                    type="text"
                    class="form-control cms-live-input cms-image-url"
                    id="{{ $fieldId }}"
                    name="content[{{ $fieldKey }}]"
                    value="{{ $value }}"
                    data-cms-field="{{ $fieldKey }}"
                    placeholder="/photos/... or upload"
                >
                <button type="button" class="btn btn-outline-secondary" data-media-library-pick data-target="#{{ $fieldId }}" data-title="Choose image">
                    Library
                </button>
                <label class="btn btn-outline-secondary mb-0">
                    Upload
                    <input type="file" class="d-none cms-image-upload" accept="image/*" data-target="#{{ $fieldId }}">
                </label>
            </div>
            <div class="cms-image-preview rounded border bg-light overflow-hidden @if ($value === '') d-none @endif" style="height: 140px;">
                <img src="{{ $value }}" alt="" class="w-100 h-100" style="object-fit: cover; object-position: 50% 50%;" data-preview-for="{{ $fieldId }}">
            </div>
        </div>
    @elseif ($fieldType === 'position')
        <input type="hidden" class="cms-live-input cms-position-value" id="{{ $fieldId }}" name="content[{{ $fieldKey }}]" value="{{ $value ?: '50% 50%' }}" data-cms-field="{{ $fieldKey }}">
        <div class="row g-2 align-items-center">
            <div class="col-6">
                <label class="form-label small text-muted mb-1" for="{{ $fieldId }}_x">Horizontal</label>
                <input type="range" class="form-range cms-position-x" id="{{ $fieldId }}_x" min="0" max="100" value="{{ $position['x'] }}" data-target="#{{ $fieldId }}">
            </div>
            <div class="col-6">
                <label class="form-label small text-muted mb-1" for="{{ $fieldId }}_y">Vertical</label>
                <input type="range" class="form-range cms-position-y" id="{{ $fieldId }}_y" min="0" max="100" value="{{ $position['y'] }}" data-target="#{{ $fieldId }}">
            </div>
        </div>
        <div class="small text-muted mt-1 cms-position-label" data-label-for="{{ $fieldId }}">{{ $value ?: '50% 50%' }}</div>
    @else
        <input
            type="text"
            class="form-control cms-live-input"
            id="{{ $fieldId }}"
            name="content[{{ $fieldKey }}]"
            value="{{ $value }}"
            data-cms-field="{{ $fieldKey }}"
        >
    @endif

    @if (! empty($field['hint']))
        <div class="form-text">{{ $field['hint'] }}</div>
    @endif
</div>
