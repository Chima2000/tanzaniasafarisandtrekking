@extends('layouts.admin')

@section('title', 'Edit Page')

@push('styles')
<style>
    .cms-editor-shell {
        display: grid;
        grid-template-columns: minmax(320px, 42%) minmax(0, 1fr);
        gap: 1rem;
        align-items: start;
    }
    .cms-editor-pane,
    .cms-preview-pane {
        min-width: 0;
    }
    .cms-preview-pane {
        position: sticky;
        top: 1rem;
    }
    .cms-preview-frame-wrap {
        border: 1px solid var(--bs-border-color);
        border-radius: .75rem;
        overflow: hidden;
        background: #111;
        height: calc(100vh - 8rem);
        min-height: 520px;
    }
    .cms-preview-frame-wrap iframe {
        width: 100%;
        height: 100%;
        border: 0;
        background: #fff;
    }
    .cms-save-status {
        font-size: .85rem;
    }
    .cms-section-card .card-header button {
        width: 100%;
        text-align: left;
    }
    @media (max-width: 1199.98px) {
        .cms-editor-shell {
            grid-template-columns: 1fr;
        }
        .cms-preview-pane {
            position: static;
        }
        .cms-preview-frame-wrap {
            height: 70vh;
        }
    }
</style>
@endpush

@section('content')
    <div class="page-title-box d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <h4 class="mb-1">Visual page editor</h4>
            <p class="text-muted mb-0">{{ $page->title }} · <code>{{ $page->slug }}</code></p>
        </div>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <span class="cms-save-status text-muted" id="cms-save-status">Ready</span>
            @if ($previewUrl)
                <a href="{{ $previewUrl }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-secondary">Open live page</a>
            @endif
            <a href="{{ route('admin.pages.index') }}" class="btn btn-sm btn-light">All pages</a>
        </div>
    </div>

    <form
        method="POST"
        action="{{ route('admin.pages.update', $page) }}"
        id="cms-page-form"
        data-upload-url="{{ route('admin.media.upload') }}"
        data-preview-url="{{ $previewUrl }}"
    >
        @csrf
        @method('PUT')

        <div class="cms-editor-shell">
            <div class="cms-editor-pane">
                <div class="card mb-3">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Publishing & SEO</h4>
                    </div>
                    <div class="card-body pt-0">
                        <div class="mb-3">
                            <label class="form-label" for="title">Title</label>
                            <input type="text" class="form-control cms-live-input" id="title" name="title" value="{{ old('title', $page->title) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="hero_image">Hero / page banner image</label>
                            <div class="input-group">
                                <input type="text" class="form-control cms-live-input cms-image-url" id="hero_image" name="hero_image" value="{{ old('hero_image', $page->hero_image) }}" placeholder="/photos/... or upload">
                                <button type="button" class="btn btn-outline-secondary" data-media-library-pick data-target="#hero_image" data-title="Choose image">Library</button>
                                <label class="btn btn-outline-secondary mb-0">
                                    Upload
                                    <input type="file" class="d-none cms-image-upload" accept="image/*" data-target="#hero_image">
                                </label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="seo_title">SEO title</label>
                            <input type="text" class="form-control cms-live-input" id="seo_title" name="seo_title" value="{{ old('seo_title', $page->seo_title) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="seo_description">SEO description</label>
                            <textarea class="form-control cms-live-input" id="seo_description" name="seo_description" rows="3">{{ old('seo_description', $page->seo_description) }}</textarea>
                            <label class="form-label mt-3" for="seo_keywords">SEO keywords</label>
                            <input type="text" class="form-control cms-live-input" id="seo_keywords" name="seo_keywords" value="{{ old('seo_keywords', $page->seo_keywords) }}" placeholder="Comma-separated keywords">
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" role="switch" id="is_published" name="is_published" value="1" @checked(old('is_published', $page->is_published))>
                            <label class="form-check-label" for="is_published">Published</label>
                        </div>
                        <button type="submit" class="btn btn-primary w-100" id="cms-manual-save">Save changes</button>
                    </div>
                </div>

                <div class="accordion cms-section-card" id="cms-sections">
                    @foreach ($blueprint['sections'] as $index => $section)
                        <div class="accordion-item mb-2 border rounded overflow-hidden">
                            <h2 class="accordion-header" id="heading-{{ $section['id'] }}">
                                <button
                                    class="accordion-button @if ($index !== 0) collapsed @endif"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#collapse-{{ $section['id'] }}"
                                    aria-expanded="{{ $index === 0 ? 'true' : 'false' }}"
                                    aria-controls="collapse-{{ $section['id'] }}"
                                    data-cms-focus-section="{{ $section['id'] }}"
                                >
                                    {{ $section['label'] }}
                                </button>
                            </h2>
                            <div
                                id="collapse-{{ $section['id'] }}"
                                class="accordion-collapse collapse @if ($index === 0) show @endif"
                                data-bs-parent="#cms-sections"
                            >
                                <div class="accordion-body">
                                    @foreach ($section['fields'] as $field)
                                        @include('admin.pages._field', [
                                            'field' => $field,
                                            'content' => $content,
                                            'sectionId' => $section['id'],
                                        ])
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="cms-preview-pane">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h5 class="mb-0">Live preview</h5>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="cms-refresh-preview">Refresh</button>
                </div>
                <div class="cms-preview-frame-wrap">
                    @if ($previewUrl)
                        <iframe id="cms-preview-frame" src="{{ $previewUrl }}" title="Live page preview"></iframe>
                    @else
                        <div class="p-4 text-white-50">No public preview route is mapped for this page.</div>
                    @endif
                </div>
                <p class="text-muted small mt-2 mb-0">
                    Edits autosave, then the preview reloads so you can see the exact public page. Click a section in the editor to scroll the preview to that area. Use the image sliders to reposition photos.
                </p>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
<script>
(() => {
    const form = document.getElementById('cms-page-form');
    if (!form) return;

    const statusEl = document.getElementById('cms-save-status');
    const frame = document.getElementById('cms-preview-frame');
    const refreshBtn = document.getElementById('cms-refresh-preview');
    const uploadUrl = form.dataset.uploadUrl;
    const previewUrl = form.dataset.previewUrl;
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    let saveTimer = null;
    let saving = false;

    const setStatus = (text, tone = 'muted') => {
        statusEl.className = `cms-save-status text-${tone}`;
        statusEl.textContent = text;
    };

    const refreshPreview = (sectionId = null) => {
        if (!frame || !previewUrl) return;
        const url = new URL(previewUrl, window.location.origin);
        url.searchParams.set('_ts', Date.now().toString());
        if (sectionId) {
            url.hash = `cms-${sectionId}`;
        }
        frame.src = url.toString();
    };

    const saveForm = async ({ reloadPreview = true, sectionId = null } = {}) => {
        if (saving) return;
        saving = true;
        setStatus('Saving…', 'warning');

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                body: new FormData(form),
            });

            if (!response.ok) {
                throw new Error('Save failed');
            }

            setStatus('Saved · preview synced', 'success');
            if (reloadPreview) {
                refreshPreview(sectionId);
            }
        } catch (error) {
            setStatus('Could not save — try again', 'danger');
        } finally {
            saving = false;
        }
    };

    const queueSave = (sectionId = null) => {
        setStatus('Editing…', 'muted');
        clearTimeout(saveTimer);
        saveTimer = setTimeout(() => saveForm({ sectionId }), 700);
    };

    form.addEventListener('input', (event) => {
        const input = event.target;
        if (!input.classList?.contains('cms-live-input')) {
            return;
        }

        const section = input.closest('[data-cms-section]')?.dataset.cmsSection
            || document.querySelector('.accordion-button:not(.collapsed)')?.dataset.cmsFocusSection
            || null;
        queueSave(section);
    });

    form.addEventListener('focusin', (event) => {
        const input = event.target;
        if (!input.classList?.contains('cms-live-input')) {
            return;
        }
        const field = input.dataset.cmsField;
        const section = input.closest('[data-cms-section]')?.dataset.cmsSection;
        if (frame?.contentWindow && field) {
            frame.contentWindow.postMessage({ type: 'cms-focus', field, section }, '*');
        }
    });

    form.addEventListener('input', (event) => {
        const slider = event.target;
        if (!slider.classList?.contains('cms-position-x') && !slider.classList?.contains('cms-position-y')) {
            return;
        }

        const target = document.querySelector(slider.dataset.target);
        if (!target) return;
        const wrap = slider.closest('.cms-field, .cms-hero-slide');
        const x = wrap?.querySelector('.cms-position-x')?.value ?? '50';
        const y = wrap?.querySelector('.cms-position-y')?.value ?? '50';
        const value = `${x}% ${y}%`;
        target.value = value;
        const label = document.querySelector(`[data-label-for="${target.id}"]`);
        if (label) label.textContent = value;

        const previewImg = document.querySelector(`[data-preview-for="${target.id.replace(/_position$/, '_media')}"]`)
            || document.querySelector(`[data-preview-for="${target.id}"]`);
        if (previewImg) {
            previewImg.style.objectPosition = value;
        }

        target.dispatchEvent(new Event('input', { bubbles: true }));
    });

    const uploadMediaFile = async (fileInput) => {
        const file = fileInput.files?.[0];
        const target = document.querySelector(fileInput.dataset.target);
        if (!file || !target) return;

        const body = new FormData();
        body.append('file', file);

        setStatus('Uploading media…', 'warning');

        try {
            const response = await fetch(uploadUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                body,
            });

            if (!response.ok) {
                throw new Error('Upload failed');
            }

            const payload = await response.json();
            target.value = payload.url;
            const preview = document.querySelector(`[data-preview-for="${target.id}"]`);
            if (preview) {
                preview.src = payload.url;
                preview.closest('.cms-image-preview')?.classList.remove('d-none');
            }
            target.dispatchEvent(new Event('input', { bubbles: true }));
            setStatus('Media uploaded', 'success');
        } catch (error) {
            setStatus('Upload failed', 'danger');
        } finally {
            fileInput.value = '';
        }
    };

    form.addEventListener('change', (event) => {
        const fileInput = event.target;
        if (fileInput.classList?.contains('cms-image-upload') || fileInput.classList?.contains('cms-media-upload')) {
            uploadMediaFile(fileInput);
        }
    });

    form.addEventListener('input', (event) => {
        const input = event.target;
        if (!input.classList?.contains('cms-image-url')) {
            return;
        }
        const preview = document.querySelector(`[data-preview-for="${input.id}"]`);
        if (!preview) return;
        const slide = input.closest('[data-hero-slide]');
        const isVideo = slide?.querySelector('[data-hero-slide-type]')?.value === 'video';
        if (input.value && !isVideo) {
            preview.src = input.value;
            preview.closest('.cms-image-preview')?.classList.remove('d-none');
        } else if (!input.value || isVideo) {
            preview.closest('.cms-image-preview')?.classList.add('d-none');
        }
    });

    const heroRoot = form.querySelector('[data-cms-hero-slides]');
    if (heroRoot) {
        const list = heroRoot.querySelector('[data-hero-slides-list]');
        const template = heroRoot.querySelector('[data-hero-slide-template]');

        const renumberSlides = () => {
            list.querySelectorAll('[data-hero-slide]').forEach((slide, index) => {
                slide.querySelector('[data-hero-slide-number]')?.replaceChildren(document.createTextNode(String(index + 1)));
                slide.querySelectorAll('[name^="content[hero_slides]"]').forEach((input) => {
                    input.name = input.name.replace(/content\[hero_slides\]\[(?:\d+|__INDEX__)\]/, `content[hero_slides][${index}]`);
                });
                slide.querySelectorAll('[id^="hero_slide_"]').forEach((el) => {
                    el.id = el.id.replace(/hero_slide_(?:\d+|__INDEX__)/, `hero_slide_${index}`);
                });
                slide.querySelectorAll('[data-target^="#hero_slide_"]').forEach((el) => {
                    el.dataset.target = el.dataset.target.replace(/#hero_slide_(?:\d+|__INDEX__)/, `#hero_slide_${index}`);
                });
                slide.querySelectorAll('[data-preview-for^="hero_slide_"]').forEach((el) => {
                    el.dataset.previewFor = el.dataset.previewFor.replace(/hero_slide_(?:\d+|__INDEX__)/, `hero_slide_${index}`);
                });
                slide.querySelectorAll('[data-label-for^="hero_slide_"]').forEach((el) => {
                    el.dataset.labelFor = el.dataset.labelFor.replace(/hero_slide_(?:\d+|__INDEX__)/, `hero_slide_${index}`);
                });
                slide.querySelectorAll('[data-media-library-pick]').forEach((button) => {
                    if (button.dataset.target) {
                        button.dataset.target = button.dataset.target.replace(/#hero_slide_(?:\d+|__INDEX__)/, `#hero_slide_${index}`);
                    }
                });
            });
            heroRoot.dataset.nextIndex = String(list.querySelectorAll('[data-hero-slide]').length);
        };

        const syncSlideTypeUi = (slide) => {
            const type = slide.querySelector('[data-hero-slide-type]')?.value || 'image';
            const isVideo = type === 'video';
            const label = slide.querySelector('[data-hero-media-label]');
            const pick = slide.querySelector('[data-hero-media-pick]');
            const upload = slide.querySelector('[data-hero-media-upload]');
            const preview = slide.querySelector('[data-hero-image-preview]');
            if (label) label.textContent = isVideo ? 'Video file / URL' : 'Background image';
            if (pick) {
                pick.dataset.category = isVideo ? 'video' : 'image';
                pick.dataset.title = isVideo ? 'Choose video' : 'Choose image';
            }
            if (upload) {
                upload.accept = isVideo ? 'video/mp4,video/webm,video/quicktime' : 'image/*';
            }
            slide.querySelector('[data-hero-poster-wrap]')?.classList.toggle('d-none', !isVideo);
            slide.querySelector('[data-hero-position-wrap]')?.classList.toggle('d-none', isVideo);
            if (isVideo) {
                preview?.classList.add('d-none');
            }
        };

        heroRoot.querySelector('[data-hero-slide-add]')?.addEventListener('click', () => {
            if (!template || !list) return;
            const html = template.innerHTML.replaceAll('__INDEX__', String(list.querySelectorAll('[data-hero-slide]').length));
            list.insertAdjacentHTML('beforeend', html);
            renumberSlides();
            const newest = list.querySelector('[data-hero-slide]:last-child');
            if (newest) syncSlideTypeUi(newest);
            if (typeof window.kltInitRichTextEditors === 'function') {
                window.kltInitRichTextEditors();
            }
            queueSave('hero-slideshow');
        });

        list?.addEventListener('click', (event) => {
            const slide = event.target.closest('[data-hero-slide]');
            if (!slide) return;

            if (event.target.closest('[data-hero-slide-remove]')) {
                if (list.querySelectorAll('[data-hero-slide]').length <= 1) {
                    alert('Keep at least one hero slide.');
                    return;
                }
                slide.remove();
                renumberSlides();
                queueSave('hero-slideshow');
                return;
            }

            if (event.target.closest('[data-hero-slide-up]')) {
                const prev = slide.previousElementSibling;
                if (prev) {
                    list.insertBefore(slide, prev);
                    renumberSlides();
                    queueSave('hero-slideshow');
                }
                return;
            }

            if (event.target.closest('[data-hero-slide-down]')) {
                const next = slide.nextElementSibling;
                if (next) {
                    list.insertBefore(next, slide);
                    renumberSlides();
                    queueSave('hero-slideshow');
                }
            }
        });

        list?.addEventListener('change', (event) => {
            if (event.target.matches('[data-hero-slide-type]')) {
                syncSlideTypeUi(event.target.closest('[data-hero-slide]'));
            }
        });

        list?.querySelectorAll('[data-hero-slide]').forEach(syncSlideTypeUi);
    }

    document.querySelectorAll('[data-cms-focus-section]').forEach((button) => {
        button.addEventListener('click', () => {
            const section = button.dataset.cmsFocusSection;
            if (frame?.contentWindow && section) {
                frame.contentWindow.postMessage({ type: 'cms-focus', section }, '*');
            }
        });
    });

    refreshBtn?.addEventListener('click', () => refreshPreview());

    form.addEventListener('submit', (event) => {
        if (event.submitter?.id === 'cms-manual-save') {
            return;
        }
    });

    window.addEventListener('message', (event) => {
        if (!event.data || event.data.type !== 'cms-request-field') return;
        const field = form.querySelector(`[data-cms-field="${event.data.field}"]`);
        if (!field) return;
        field.focus({ preventScroll: false });
        field.closest('.accordion-collapse')?.classList.add('show');
        field.scrollIntoView({ behavior: 'smooth', block: 'center' });
    });
})();
</script>
@include('admin.partials.media-upload-script')
@endpush
