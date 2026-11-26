@php
    $logoVariant = $logoVariant ?? 'default';
    $logoUrl = $logoVariant === 'white'
        ? asset('logo/tanzaniasafaritrekkinglogowhite.png')
        : asset('logo/tanzania-safaris-and-trekking-logo.png');
@endphp
<a href="{{ route('home') }}" class="brand-image-logo" aria-label="Tanzania Safaris and Trekking">
    <img src="{{ $logoUrl }}" alt="Tanzania Safaris and Trekking logo" width="220" height="64" decoding="async">
</a>
