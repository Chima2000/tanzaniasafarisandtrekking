@extends('layouts.aventour')

@section('title', $page?->seo_title ?? "Faq's | Tanzania Safaris and Trekking")
@section('meta_description', $page?->seo_description ?? 'Frequently asked questions about Tanzania safaris, Kilimanjaro treks, and booking support.')

@php
    $bodyClass = trim(($bodyClass ?? '').' has-sticky-sidebar');
    $faqEmail = $siteSettings['email'] ?? 'info@tanzaniasafarisandtrekking.com';
    $sidebarHeading = $pageContent['sidebar_heading'] ?? 'Let us guide you—reach out anytime.';
@endphp

@section('content')
    @include('partials.aventour.page-title', [
        'title' => $page?->title ?? "Faq's",
        'bg' => $pageBg,
        'crumbs' => [
            ['label' => $page?->title ?? "Faq's"],
        ],
    ])

    <section class="faq-page-section sec-pad-3">
        <div class="auto-container">
            <div class="row clearfix">
                <div class="col-lg-4 col-md-12 col-sm-12 sidebar-side">
                    <div class="sidebar-content">
                        <ul class="faq-category clearfix mb_30">
                            <li><a href="{{ route('faq') }}">General Questions</a></li>
                            <li><a href="{{ route('packages.index') }}">Safari Packages</a></li>
                            <li><a href="{{ route('packages.index', ['category' => 'Trekking']) }}">Kilimanjaro Treks</a></li>
                            <li><a href="{{ route('destinations.index') }}">Destinations</a></li>
                            <li><a href="{{ route('contact') }}">Booking Support</a></li>
                        </ul>
                        <div class="faq-sidebar text-center">
                            <div class="inner-box">
                                <div class="icon-box"><img src="/aventour/assets/images/icons/icon-17.png" alt=""></div>
                                <h3>{{ $sidebarHeading }}</h3>
                                <a href="mailto:{{ $faqEmail }}">{{ $faqEmail }}</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-md-12 col-sm-12 content-side">
                    <div class="faq-content">
                        <ul class="accordion-box accordion-style-one">
                            @forelse ($faqs as $faq)
                                <li class="accordion block {{ $loop->first ? 'active-block' : '' }}">
                                    <div class="acc-btn {{ $loop->first ? 'active' : '' }}">
                                        <div class="icon-box"><i class="flaticon-plus"></i></div>
                                        <h4>{{ $faq->question }}</h4>
                                    </div>
                                    <div class="acc-content {{ $loop->first ? 'current' : '' }}">
                                        <div class="text">
                                            <p>{{ $faq->answer }}</p>
                                        </div>
                                    </div>
                                </li>
                            @empty
                                <li class="accordion block active-block">
                                    <div class="acc-btn active">
                                        <div class="icon-box"><i class="flaticon-plus"></i></div>
                                        <h4>How do I book a tour?</h4>
                                    </div>
                                    <div class="acc-content current">
                                        <div class="text">
                                            <p>Browse our safari and Kilimanjaro packages, open the tour you like, and send an enquiry. Our team will confirm dates, group size, and a tailored quote.</p>
                                        </div>
                                    </div>
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
