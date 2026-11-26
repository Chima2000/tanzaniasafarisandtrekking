@extends('layouts.aventour')

@section('title', $page?->seo_title ?? 'Tanzania Safari Destinations Guide | Serengeti, Ngorongoro, Kilimanjaro')
@section('meta_description', $page?->seo_description ?? 'Explore Tanzania destinations with detailed guides to Serengeti, Ngorongoro, Kilimanjaro, Tarangire, and Arusha—plus safari and trek packages matched to each place.')
@section('meta_keywords', $page?->seo_keywords ?? 'Tanzania destinations, Serengeti safari, Ngorongoro crater, Kilimanjaro trek, Tarangire elephants, Arusha day trips')
@section('canonical', route('destinations.index'))
@section('og_image', absolute_url($pageBg) ?? '')

@section('content')
    @include('partials.aventour.page-title', [
        'title' => $pageContent['page_title'] ?? 'Destinations',
        'bg' => $pageBg,
        'crumbs' => [
            ['label' => $pageContent['page_title'] ?? 'Destinations'],
        ],
    ])

    <section class="destination-index sec-pad-3" data-cms-section="destinations-main">
        <div class="auto-container">
            <div class="sec-title centred mb_50">
                <div class="sub-title"><i class="flaticon-parachuting"></i><span data-cms-field="subtitle">{{ $pageContent['subtitle'] ?? 'Northern Circuit Destination Guides' }}</span></div>
                <h2 data-cms-field="page_title">{{ $pageContent['page_title'] ?? 'Places Worth Building a Trip Around' }}</h2>
                <x-rich-text class="mt_12" data-cms-field="intro" :content="$pageContent['intro'] ?? null" fallback="Each destination guide covers the full story—overview, wildlife, timing, access, travel tips, FAQs, and the packages that feature that place." />
            </div>

            <div class="destination-index__list">
                @foreach ($destinations as $destination)
                    <article class="destination-index__card">
                        <figure class="destination-index__media">
                            <img src="{{ $destination['image'] }}" alt="{{ $destination['full_name'] }} travel guide">
                            <div class="destination-index__badge">
                                <i class="flaticon-location"></i>
                                <span>{{ $destination['region'] }}</span>
                            </div>
                        </figure>
                        <div class="destination-index__body">
                            <div class="destination-index__eyebrow">
                                <span>{{ $destination['starts'] }}</span>
                                <span>{{ $destination['places'] }}</span>
                            </div>
                            <h3>
                                <a href="{{ route('destinations.show', $destination['slug']) }}">{{ $destination['full_name'] }}</a>
                            </h3>
                            <p class="destination-index__headline">{{ $destination['headline'] }}</p>
                            <p class="destination-index__description">{{ \Illuminate\Support\Str::limit(strip_tags((string) ($destination['description'] ?? '')), 220) }}</p>

                            @if (! empty($destination['highlights']))
                                <ul class="destination-index__highlights has-internal-links">
                                    @foreach (array_slice($destination['highlights'], 0, 3) as $highlight)
                                        <li>{!! linked($highlight) !!}</li>
                                    @endforeach
                                </ul>
                            @endif

                            <div class="destination-index__footer">
                                <a href="{{ route('destinations.show', $destination['slug']) }}" class="theme-btn">
                                    <span>Read Full Guide</span><i class="flaticon-arrow"></i>
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="destination-index-cta pb_110">
        <div class="auto-container">
            <div class="destination-index-cta__box">
                <div class="sec-title">
                    <div class="sub-title"><i class="flaticon-parachuting"></i><span>Ready to Plan</span></div>
                    <h2>Build a Northern Circuit Around the Places You Love</h2>
                    <p class="mt_12">Tell us which destinations matter most—Serengeti plains, Ngorongoro crater, Kilimanjaro routes, Tarangire elephants, or Arusha day adventures—and we’ll shape the itinerary.</p>
                </div>
                <div class="destination-guide__actions">
                    <a href="{{ route('book-now') }}" class="theme-btn"><span>Start Planning</span><i class="flaticon-arrow"></i></a>
                    <a href="{{ route('packages.index') }}" class="theme-btn secondary-bg"><span>Browse Packages</span><i class="flaticon-arrow"></i></a>
                </div>
            </div>
        </div>
    </section>
@endsection
