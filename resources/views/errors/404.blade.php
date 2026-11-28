@extends('layouts.aventour')

@section('title', 'Page Not Found | Tanzania Safaris and Trekking')
@section('meta_description', 'The page you requested could not be found. Explore safari packages, Kilimanjaro treks, or contact Tanzania Safaris and Trekking.')
@section('meta_robots', 'noindex, follow')
@section('canonical', url('/'))

@section('content')
    @php
        $company = site_settings('company_name', 'Tanzania Safaris and Trekking');
        $galleryImage = app(\App\Support\TourCatalog::class)->galleryImages(1)[0] ?? null;
        $settingsImage = site_settings('default_og_image');
        $errorBg = $galleryImage
            ?: (filled($settingsImage) && ! str_contains((string) $settingsImage, '/logo/')
                ? $settingsImage
                : asset('photos/2 Days Tarangire and Ngorongoro Safari7.jpeg'));
    @endphp

    <section class="error-lost-section" aria-labelledby="error-lost-heading">
        <div class="error-lost-section__media" style="background-image: url('{{ $errorBg }}');" role="img" aria-label="Tanzania safari landscape"></div>
        <div class="error-lost-section__veil" aria-hidden="true"></div>

        <div class="auto-container">
            <div class="error-lost-section__panel">
                <p class="error-lost-section__brand">{{ $company }}</p>
                <p class="error-lost-section__code" aria-hidden="true">404</p>
                <h1 id="error-lost-heading">This trail does not lead anywhere</h1>
                <p class="error-lost-section__copy">
                    The page you are looking for may have moved, been renamed, or never existed.
                    Head back to camp and keep planning your Tanzania adventure.
                </p>

                <div class="error-lost-section__actions">
                    <a href="{{ route('home') }}" class="theme-btn"><span>Back to Home</span><i class="flaticon-arrow"></i></a>
                    <a href="{{ route('packages.index') }}" class="theme-btn secondary-bg"><span>Browse Packages</span><i class="flaticon-arrow"></i></a>
                    <a href="{{ route('contact') }}" class="theme-btn secondary-bg"><span>Contact Us</span><i class="flaticon-arrow"></i></a>
                </div>

                <nav class="error-lost-section__links" aria-label="Helpful links">
                    <a href="{{ route('packages.safari') }}">Safari packages</a>
                    <a href="{{ route('packages.trekking') }}">Kilimanjaro treks</a>
                    <a href="{{ route('destinations.index') }}">Destinations</a>
                    <a href="{{ route('book-now') }}">Book now</a>
                </nav>
            </div>
        </div>
    </section>
@endsection
