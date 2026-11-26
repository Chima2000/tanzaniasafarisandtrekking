@extends('layouts.admin')

@section('title', 'WhatsApp Widget')

@php
    $selectedPages = old('pages', $setting->pages ?? []);
    $selectedPages = is_array($selectedPages) ? $selectedPages : [];
    $iconColor = old('icon_color', $setting->iconColor());
@endphp

@push('styles')
    <link rel="stylesheet" href="{{ asset('aventour/assets/css/whatsapp-button.css') }}">
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="mb-1">WhatsApp Widget</h4>
                <p class="text-muted mb-0">Floating chat button for the public site. The button stays hidden until a number or profile is saved.</p>
            </div>
        </div>
    </div>

    <div class="klt-wa-settings">
        <form id="whatsappSettingsForm" method="post" action="{{ route('admin.whatsapp.update') }}" class="card" data-has-contacts="{{ $setting->contacts->isNotEmpty() ? '1' : '0' }}">
            @csrf
            <div class="card-header">
                <h4 class="card-title mb-1">WhatsApp button</h4>
                <p class="text-muted mb-0 small">Change the number, position, size, color, and where visitors can open the chat.</p>
            </div>
            <div class="card-body pt-0">
                <input type="hidden" name="pages[]" value="">

                <div class="form-check form-switch mb-3">
                    <input type="hidden" name="enabled" value="0">
                    <input class="form-check-input" type="checkbox" role="switch" id="whatsappEnabled" name="enabled" value="1" @checked(filter_var(old('enabled', $setting->enabled), FILTER_VALIDATE_BOOLEAN))>
                    <label class="form-check-label" for="whatsappEnabled">Show the WhatsApp button</label>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="whatsappPhone">WhatsApp number</label>
                        <input id="whatsappPhone" name="phone_number" type="text" inputmode="tel" class="form-control @error('phone_number') is-invalid @enderror" value="{{ old('phone_number', $setting->phone_number) }}" placeholder="+255 749 719 998">
                        <div class="form-text">Include the country code. Used when no team profiles are added.</div>
                        @error('phone_number')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="whatsappMessage">Opening message</label>
                        <input id="whatsappMessage" name="message" type="text" class="form-control @error('message') is-invalid @enderror" value="{{ old('message', $setting->message) }}" maxlength="300">
                        <div class="form-text">Optional text that opens with the chat.</div>
                        @error('message')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="whatsappCorner">Corner</label>
                        <select id="whatsappCorner" name="corner" class="form-select @error('corner') is-invalid @enderror">
                            @foreach (\App\Models\WhatsappSetting::CORNERS as $corner)
                                <option value="{{ $corner }}" @selected(old('corner', $setting->corner) === $corner)>
                                    {{ \App\Models\WhatsappSetting::CORNER_LABELS[$corner] }}
                                </option>
                            @endforeach
                        </select>
                        @error('corner')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="whatsappColor">Icon color</label>
                        <div class="klt-wa-color">
                            <input type="color" value="{{ $iconColor }}" data-wa-color-picker aria-label="Icon color">
                            <input id="whatsappColor" name="icon_color" type="text" class="form-control @error('icon_color') is-invalid @enderror" value="{{ $iconColor }}" maxlength="7" pattern="#[0-9A-Fa-f]{6}" required>
                        </div>
                        <div class="form-text">Default color is #25d366.</div>
                        @error('icon_color')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-6 col-lg-3">
                        <label class="form-label" for="whatsappOffsetX">Horizontal offset</label>
                        <input id="whatsappOffsetX" name="offset_x" type="number" min="0" max="240" class="form-control" value="{{ old('offset_x', $setting->offset_x) }}" required>
                    </div>
                    <div class="col-6 col-lg-3">
                        <label class="form-label" for="whatsappOffsetY">Vertical offset</label>
                        <input id="whatsappOffsetY" name="offset_y" type="number" min="0" max="400" class="form-control" value="{{ old('offset_y', $setting->offset_y) }}" required>
                    </div>
                    <div class="col-6 col-lg-3">
                        <label class="form-label" for="whatsappWidth">Icon width</label>
                        <input id="whatsappWidth" name="icon_width" type="number" min="40" max="96" class="form-control" value="{{ old('icon_width', $setting->icon_width) }}" required>
                    </div>
                    <div class="col-6 col-lg-3">
                        <label class="form-label" for="whatsappHeight">Icon height</label>
                        <input id="whatsappHeight" name="icon_height" type="number" min="40" max="96" class="form-control" value="{{ old('icon_height', $setting->icon_height) }}" required>
                    </div>
                </div>

                <hr>

                <h5 class="mb-1">Conversation card</h5>
                <p class="text-muted small mb-3">This title and description appear at the top of the popup when someone clicks the WhatsApp icon.</p>

                <div class="row g-3 mb-2">
                    <div class="col-md-6">
                        <label class="form-label" for="whatsappPopupTitle">Card title</label>
                        <input id="whatsappPopupTitle" name="popup_title" type="text" class="form-control @error('popup_title') is-invalid @enderror" value="{{ old('popup_title', $setting->popup_title) }}" maxlength="120" placeholder="{{ \App\Models\WhatsappSetting::POPUP_TITLE }}">
                        @error('popup_title')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="whatsappPopupNote">Reply note</label>
                        <input id="whatsappPopupNote" name="popup_note" type="text" class="form-control @error('popup_note') is-invalid @enderror" value="{{ old('popup_note', $setting->popup_note) }}" maxlength="180" placeholder="{{ \App\Models\WhatsappSetting::POPUP_NOTE }}">
                        @error('popup_note')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="whatsappPopupDescription">Card description</label>
                        <textarea id="whatsappPopupDescription" name="popup_description" class="form-control @error('popup_description') is-invalid @enderror" rows="2" maxlength="300" placeholder="{{ \App\Models\WhatsappSetting::POPUP_DESCRIPTION }}">{{ old('popup_description', $setting->popup_description) }}</textarea>
                        @error('popup_description')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr>

                <div class="form-check form-switch mb-3">
                    <input type="hidden" name="show_on_all_pages" value="0">
                    <input class="form-check-input" type="checkbox" role="switch" id="whatsappAllPages" name="show_on_all_pages" value="1" @checked(filter_var(old('show_on_all_pages', $setting->show_on_all_pages), FILTER_VALIDATE_BOOLEAN))>
                    <label class="form-check-label" for="whatsappAllPages">Whole site, including admin and staff login</label>
                    <div class="form-text">Shows the button on every listed page. Turn this off to choose pages one by one.</div>
                </div>

                <p class="mb-2 fw-semibold">Pages</p>
                <div class="klt-wa-pages">
                    @foreach (\App\Models\WhatsappSetting::PAGE_LABELS as $pageKey => $pageLabel)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="whatsappPage{{ $loop->index }}" name="pages[]" value="{{ $pageKey }}" data-wa-page data-wa-page-label="{{ $pageLabel }}" @checked(in_array($pageKey, $selectedPages, true))>
                            <label class="form-check-label" for="whatsappPage{{ $loop->index }}">{{ $pageLabel }}</label>
                        </div>
                    @endforeach
                </div>
                @error('pages')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
                @error('pages.*')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror

                <div class="d-flex flex-wrap gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">Save WhatsApp settings</button>
                </div>
            </div>
        </form>

        <aside class="card klt-wa-preview-card">
            <div class="card-header">
                <h4 class="card-title mb-1">Live preview</h4>
                <p class="text-muted mb-0 small">Move the icon before you save. The site updates after saving.</p>
            </div>
            <div class="card-body pt-0">
                <div class="klt-wa-stage" id="whatsappPreviewStage">
                    <div class="klt-wa-stage-bar"><span></span> Tanzania Safaris and Trekking</div>
                    <div class="klt-wa-stage-copy">
                        <strong>WhatsApp</strong>
                        <p>The button follows the corner, offsets, size, and color in this frame.</p>
                    </div>
                    <div
                        id="whatsappPreviewDock"
                        class="klt-wa-dock is-open"
                        data-corner="{{ old('corner', $setting->corner) }}"
                        style="--klt-wa-color: {{ $iconColor }}; --klt-wa-x: {{ (int) old('offset_x', $setting->offset_x) }}px; --klt-wa-y: {{ (int) old('offset_y', $setting->offset_y) }}px; --klt-wa-w: {{ (int) old('icon_width', $setting->icon_width) }}px; --klt-wa-h: {{ (int) old('icon_height', $setting->icon_height) }}px;"
                    >
                        @include('partials.whatsapp-popup-card', [
                            'popupId' => 'whatsappPreviewPopup',
                            'popupTitle' => $setting->popupTitle(),
                            'popupDescription' => $setting->popupDescription(),
                            'popupNote' => $setting->popupNote(),
                            'entries' => $setting->popupEntries(),
                            'hidden' => false,
                            'interactive' => false,
                            'linkable' => false,
                        ])
                        <div id="whatsappPreviewButton" class="klt-whatsapp" role="img" aria-label="Chat on WhatsApp">
                            <span class="klt-wa-glyph">@include('partials.whatsapp-icon')</span>
                            <span class="klt-wa-x" aria-hidden="true">&times;</span>
                        </div>
                    </div>
                </div>
                <p class="klt-wa-preview-note" id="whatsappPreviewNote"></p>

                @if (filled($setting->phone_number))
                    <form method="post" action="{{ route('admin.whatsapp.destroy') }}" class="mt-3">
                        @csrf
                        <button type="submit" class="btn btn-danger">Remove number</button>
                    </form>
                @endif
            </div>
        </aside>
    </div>

    @include('admin.whatsapp.contacts')
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('whatsappSettingsForm');
        const dock = document.getElementById('whatsappPreviewDock');
        const note = document.getElementById('whatsappPreviewNote');
        const colorPicker = form?.querySelector('[data-wa-color-picker]');
        const colorInput = form?.querySelector('[name="icon_color"]');
        const previewTitle = document.getElementById('whatsappPreviewPopup-title');
        const previewDescription = document.getElementById('whatsappPreviewPopup-description');
        const previewNote = document.getElementById('whatsappPreviewPopup-note');

        if (!form || !dock || !note || !colorPicker || !colorInput) {
            return;
        }

        const copy = {
            all: 'Visible across the site, including admin and staff login.',
            selected: 'Visible on: :pages.',
            hidden: 'Hidden until the button is enabled and a number or profile is saved.',
            popupTitle: @json(\App\Models\WhatsappSetting::POPUP_TITLE),
            popupDescription: @json(\App\Models\WhatsappSetting::POPUP_DESCRIPTION),
            popupNote: @json(\App\Models\WhatsappSetting::POPUP_NOTE),
        };
        const hasContacts = form.dataset.hasContacts === '1';

        function clamp(value, min, max, fallback) {
            const number = Number.parseInt(value, 10);
            if (Number.isNaN(number)) {
                return fallback;
            }

            return Math.min(max, Math.max(min, number));
        }

        function paint() {
            const corner = form.querySelector('[name="corner"]')?.value || 'bottom-right';
            const color = /^#[0-9a-fA-F]{6}$/.test(colorInput.value) ? colorInput.value : '#25d366';
            const offsetX = clamp(form.querySelector('[name="offset_x"]')?.value, 0, 240, 24);
            const offsetY = clamp(form.querySelector('[name="offset_y"]')?.value, 0, 400, 24);
            const width = clamp(form.querySelector('[name="icon_width"]')?.value, 40, 96, 56);
            const height = clamp(form.querySelector('[name="icon_height"]')?.value, 40, 96, 56);
            const enabled = form.querySelector('[name="enabled"][type="checkbox"]')?.checked === true;
            const allPages = form.querySelector('[name="show_on_all_pages"][type="checkbox"]')?.checked === true;
            const phone = form.querySelector('[name="phone_number"]')?.value.trim() || '';
            const popupTitle = form.querySelector('[name="popup_title"]')?.value.trim() || copy.popupTitle;
            const popupDescription = form.querySelector('[name="popup_description"]')?.value.trim() || copy.popupDescription;
            const popupNote = form.querySelector('[name="popup_note"]')?.value.trim() || copy.popupNote;
            const pages = Array.from(form.querySelectorAll('[data-wa-page]:checked')).map(function (input) {
                return input.dataset.waPageLabel;
            });

            const stage = document.getElementById('whatsappPreviewStage');
            const stageWidth = stage ? stage.clientWidth : 360;
            const stageHeight = stage ? stage.clientHeight : 520;
            const framePad = 12;
            const previewX = Math.min(offsetX, Math.max(framePad, stageWidth - width - framePad));
            const previewY = Math.min(offsetY, Math.max(framePad, stageHeight - height - framePad));

            dock.dataset.corner = corner;
            dock.style.setProperty('--klt-wa-color', color);
            dock.style.setProperty('--klt-wa-x', previewX + 'px');
            dock.style.setProperty('--klt-wa-y', previewY + 'px');
            dock.style.setProperty('--klt-wa-w', width + 'px');
            dock.style.setProperty('--klt-wa-h', height + 'px');
            dock.style.opacity = enabled && (phone !== '' || hasContacts) ? '1' : '0.4';

            if (previewTitle) {
                previewTitle.textContent = popupTitle;
            }
            if (previewDescription) {
                previewDescription.textContent = popupDescription;
            }
            if (previewNote) {
                previewNote.hidden = popupNote === '';
                previewNote.textContent = popupNote;
            }

            if (!enabled || (phone === '' && !hasContacts)) {
                note.textContent = copy.hidden;
                return;
            }

            note.textContent = allPages ? copy.all : copy.selected.replace(':pages', pages.join(', ') || '—');
            window.requestAnimationFrame(fitPreviewPopup);
        }

        function fitPreviewPopup() {
            const stage = document.getElementById('whatsappPreviewStage');
            const popup = dock.querySelector('.klt-wa-popup');

            if (!stage || !popup) {
                return;
            }

            popup.style.transform = 'none';
            popup.style.width = '';
            const stageRect = stage.getBoundingClientRect();
            const popupRect = popup.getBoundingClientRect();
            const pad = 10;
            const topLimit = stageRect.top + 46;
            let shiftX = 0;
            let shiftY = 0;

            if (popupRect.width > stageRect.width - pad * 2) {
                popup.style.width = Math.max(180, stageRect.width - pad * 2) + 'px';
            }

            const fitted = popup.getBoundingClientRect();

            if (fitted.left < stageRect.left + pad) {
                shiftX = stageRect.left + pad - fitted.left;
            } else if (fitted.right > stageRect.right - pad) {
                shiftX = stageRect.right - pad - fitted.right;
            }

            if (fitted.top < topLimit) {
                shiftY = topLimit - fitted.top;
            } else if (fitted.bottom > stageRect.bottom - pad) {
                shiftY = stageRect.bottom - pad - fitted.bottom;
            }

            popup.style.transform = 'translate(' + shiftX + 'px, ' + shiftY + 'px)';
        }

        colorPicker.addEventListener('input', function () {
            colorInput.value = colorPicker.value;
            paint();
        });

        colorInput.addEventListener('input', function () {
            if (/^#[0-9a-fA-F]{6}$/.test(colorInput.value)) {
                colorPicker.value = colorInput.value;
            }
        });

        form.addEventListener('input', paint);
        form.addEventListener('change', paint);
        window.addEventListener('resize', paint);
        paint();
    });
</script>
@endpush
