<div class="klt-wa-popup" id="{{ $popupId }}" @if ($hidden ?? false) hidden @endif>
    <div class="klt-wa-popup-card" role="dialog" aria-labelledby="{{ $popupId }}-title">
        <div class="klt-wa-popup-head">
            <h2 id="{{ $popupId }}-title" class="klt-wa-popup-title">{{ $popupTitle }}</h2>
            <p id="{{ $popupId }}-description" class="klt-wa-popup-description">{{ $popupDescription }}</p>
            @if ($interactive ?? false)
                <button type="button" class="klt-wa-popup-close" data-wa-close aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            @else
                <span class="klt-wa-popup-close" aria-hidden="true">&times;</span>
            @endif
        </div>
        @if (filled($popupNote))
            <p id="{{ $popupId }}-note" class="klt-wa-popup-note">{{ $popupNote }}</p>
        @else
            <p id="{{ $popupId }}-note" class="klt-wa-popup-note" hidden></p>
        @endif
        <div class="klt-wa-popup-list">
            @php $previousSection = null; @endphp
            @foreach ($entries as $entry)
                @if (filled($entry['section'] ?? null) && $entry['section'] !== $previousSection)
                    <p class="klt-wa-popup-section">{{ $entry['section'] }}</p>
                @endif
                @php $previousSection = $entry['section'] ?? null; @endphp
                <a class="klt-wa-person" href="{{ ($linkable ?? true) ? $entry['chat_url'] : '#' }}" @if (($interactive ?? false) && ($linkable ?? true)) target="_blank" rel="noopener noreferrer" @endif>
                    @if (filled($entry['photo_url'] ?? null))
                        <img src="{{ $entry['photo_url'] }}" alt="">
                    @else
                        <span class="klt-wa-avatar" aria-hidden="true">{{ $entry['initials'] }}</span>
                    @endif
                    <span class="klt-wa-person-copy">
                        <strong>{{ $entry['name'] }}</strong>
                        <em>{{ $entry['title'] }}</em>
                    </span>
                    <span class="klt-wa-person-chat" aria-hidden="true">
                        @include('partials.whatsapp-icon')
                    </span>
                </a>
            @endforeach
        </div>
    </div>
    <span class="klt-wa-popup-tail" aria-hidden="true"></span>
</div>
