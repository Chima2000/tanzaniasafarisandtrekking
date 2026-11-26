@php
    $whatsapp = \App\Models\WhatsappSetting::current();
    $whatsappPage = $page ?? \App\Models\WhatsappSetting::pageKey();
@endphp

@if ($whatsapp->visibleOn($whatsappPage))
    @once
        <link rel="stylesheet" href="{{ asset('aventour/assets/css/whatsapp-button.css') }}">
    @endonce
    <div
        class="klt-wa-dock"
        data-wa-dock
        data-corner="{{ $whatsapp->corner }}"
        style="--klt-wa-color: {{ $whatsapp->iconColor() }}; --klt-wa-x: {{ (int) $whatsapp->offset_x }}px; --klt-wa-y: {{ (int) $whatsapp->offset_y }}px; --klt-wa-w: {{ (int) $whatsapp->icon_width }}px; --klt-wa-h: {{ (int) $whatsapp->icon_height }}px;"
    >
        @include('partials.whatsapp-popup-card', [
            'popupId' => 'kltWaPopup',
            'popupTitle' => $whatsapp->popupTitle(),
            'popupDescription' => $whatsapp->popupDescription(),
            'popupNote' => $whatsapp->popupNote(),
            'entries' => $whatsapp->popupEntries(),
            'hidden' => true,
            'interactive' => true,
        ])
        <button
            type="button"
            class="klt-whatsapp"
            aria-expanded="false"
            aria-controls="kltWaPopup"
            aria-label="Chat on WhatsApp"
        >
            <span class="klt-wa-glyph">@include('partials.whatsapp-icon')</span>
            <span class="klt-wa-x" aria-hidden="true">&times;</span>
        </button>
    </div>
    @once
        @include('partials.whatsapp-popup-script')
    @endonce
@endif
