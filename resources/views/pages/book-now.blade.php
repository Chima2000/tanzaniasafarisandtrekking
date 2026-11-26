@extends('layouts.aventour')

@section('title', $page?->seo_title ?? 'Book Now | Tanzania Safaris and Trekking')
@section('meta_description', $page?->seo_description ?? 'Book a Tanzania safari, Kilimanjaro climb, or day adventure. Share your group, dates, and places and we will confirm availability.')

@push('styles')
<link href="/aventour/assets/css/book-now.css?v={{ filemtime(public_path('aventour/assets/css/book-now.css')) }}" rel="stylesheet">
@endpush

@section('content')
@php
    $plannerStartStep = 1;
    if ($errors->any()) {
        $plannerStartStep = match (true) {
            $errors->hasAny(['full_name', 'email', 'phone']) => 4,
            $errors->hasAny(['budget', 'accommodation', 'notes']) => 3,
            $errors->has('destinations') || $errors->has('destinations.*') || $errors->has('interest') => 2,
            $errors->hasAny(['adults', 'children', 'duration_days', 'travel_date']) => 1,
            default => 1,
        };
    }
@endphp

@include('partials.aventour.page-title', [
    'title' => $pageContent['page_title'] ?? 'Book Now',
    'bg' => $pageBg,
    'crumbs' => [
        ['label' => $pageContent['page_title'] ?? 'Book Now'],
    ],
])

{{-- 1) Form first — primary action --}}
<section class="booking-section book-now-section sec-pad" data-cms-section="book-now-main">
    <div class="pattern-layer" style="background-image: url(/aventour/assets/images/shape/shape-1.png);"></div>
    <div class="auto-container">
        <div class="sec-title centred mb_40">
            <div class="sub-title"><i class="flaticon-parachuting"></i><span>Let’s Go!</span></div>
            <h2 data-cms-field="heading">{{ $pageContent['heading'] ?? 'Request Your Safari or Trek' }}</h2>
            <x-rich-text class="mt_12" data-cms-field="body" :content="$pageContent['body'] ?? null" fallback="Four short steps — we reply with availability and a draft outline." />
            @if (! empty($pageContent['form_intro']))
                <x-rich-text class="mt_12" data-cms-field="form_intro" :content="$pageContent['form_intro'] ?? null" />
            @endif
        </div>

        @if ($plannerPackage)
            <div class="book-now-selected centred mb_30">
                <span class="text">Selected package</span>
                <h4>
                    <a href="{{ route('packages.show', $plannerPackage['slug']) }}">{{ $plannerPackage['name'] }}</a>
                </h4>
                <p>{{ $plannerPackage['duration'] }} · {{ $plannerPackage['location'] }}</p>
            </div>
        @endif

        <div class="row clearfix justify-content-center">
            <div class="col-xl-10 col-lg-11 col-md-12 col-sm-12 form-column">
                <div class="booking-form book-now-shell">
                    @include('partials.book-now-form', [
                        'plannerStartStep' => $plannerStartStep,
                    ])
                </div>
            </div>
        </div>
    </div>
</section>

{{-- 2) Support info after the form --}}
<section class="contact-section book-now-follow-up sec-pad-3">
    <div class="auto-container">
        <div class="inner-container">
            <div class="shape" style="background-image: url(/aventour/assets/images/shape/shape-55.png);"></div>
            <div class="row clearfix">
                <div class="col-lg-5 col-md-12 col-sm-12 info-column">
                    <div class="info-box mr_65">
                        <div class="text-box">
                            <h2>Let’s Lock In Your Dates</h2>
                            <p>Share a few trip details above and our local desk will reply with availability, a draft outline, and next steps.</p>
                        </div>
                        <div class="inner-box">
                            <div class="single-item">
                                <div class="inner">
                                    <h5>Talk to Us</h5>
                                    <p><a href="tel:+255769079298">+255 769 079 298</a></p>
                                </div>
                                <div class="icon-box"><img src="/aventour/assets/images/icons/icon-80.png" alt=""></div>
                            </div>
                            <div class="single-item">
                                <div class="inner">
                                    <h5>Message Us</h5>
                                    <p><a href="mailto:info@tanzaniasafarisandtrekking.com">info@tanzaniasafarisandtrekking.com</a></p>
                                </div>
                                <div class="icon-box"><img src="/aventour/assets/images/icons/icon-81.png" alt=""></div>
                            </div>
                            <div class="single-item">
                                <div class="inner">
                                    <h5>What Happens Next</h5>
                                    <p>We confirm dates, send a draft plan and price, then refine lodges before you book.</p>
                                </div>
                                <div class="icon-box"><img src="/aventour/assets/images/icons/icon-82.png" alt=""></div>
                            </div>
                        </div>
                        <div class="social-box">
                            <h5>Prefer a short message?</h5>
                            <p class="mb_15">Questions only? Use the contact page and we reply within one business day.</p>
                            <a href="{{ route('contact') }}" class="theme-btn"><span>Contact Us</span><i class="flaticon-arrow"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7 col-md-12 col-sm-12 content-column">
                    <div class="book-now-follow-panel">
                        <div class="sec-title mb_25">
                            <div class="sub-title"><i class="flaticon-parachuting"></i><span>After You Send</span></div>
                            <h2>How the Desk Helps</h2>
                        </div>
                        <ul class="list-item clearfix">
                            <li><i class="flaticon-check"></i>We confirm dates and park availability for your group size</li>
                            <li><i class="flaticon-check"></i>You receive a draft itinerary and honest price range</li>
                            <li><i class="flaticon-check"></i>We refine lodges and logistics before you commit</li>
                            <li><i class="flaticon-check"></i>WhatsApp or email follow-up until the trip feels right</li>
                        </ul>
                        <div class="contact-widget mt_30">
                            <div class="bg-image" style="background-image: url('{{ $pageBg }}');"></div>
                            <h4>Need a Quick Chat?</h4>
                            <h3>Call or WhatsApp the desk.</h3>
                            <p>Same-day replies on business days.</p>
                            <div class="inner-box">
                                <div class="icon-box"><img src="/aventour/assets/images/icons/icon-73.png" alt=""></div>
                                <div class="inner">
                                    <span class="text">Talk to us</span>
                                    <a href="tel:+255769079298">+255 769 079 298</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="/aventour/assets/js/book-now.js?v={{ filemtime(public_path('aventour/assets/js/book-now.js')) }}" defer></script>
@endpush
