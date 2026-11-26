@extends('layouts.aventour')

@section('title', ($categoryLabel ?? 'Tour Packages').' | Tanzania Safaris and Trekking')

@php
    $bodyClass = trim(($bodyClass ?? '').' has-sticky-sidebar');
@endphp

@section('content')
    @include('partials.aventour.page-title', [
        'title' => $pageTitle,
        'bg' => $pageBg,
        'crumbs' => $activeCategory
            ? [
                ['label' => 'Tour Packages', 'url' => route('packages.index')],
                ['label' => $pageTitle],
            ]
            : [
                ['label' => $pageTitle],
            ],
    ])

    <section class="package-section">
        <div class="auto-container">
            <div class="row clearfix">
                <div class="col-lg-4 col-md-12 col-sm-12 sidebar-side">
                    <div class="package-sidebar mr_20">
                        <div class="sidebar-widget search-widget">
                            <div class="widget-title">
                                <h3>Search</h3>
                            </div>
                            <div class="search-inner">
                                <form action="{{ route('packages.index') }}" method="get">
                                    <div class="form-group">
                                        <input type="search" name="q" value="{{ $search }}" placeholder="keyword" required>
                                        @if ($activeCategory)
                                            <input type="hidden" name="category" value="{{ $activeCategory }}">
                                        @endif
                                        <button type="submit"><i class="flaticon-zoom"></i></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="sidebar-widget category-widget">
                            <div class="widget-title">
                                <h3>Experiences</h3>
                                <i class="flaticon-down-arrow"></i>
                            </div>
                            <div class="widget-content">
                                <ul class="category-list clearfix">
                                    <li @class(['active' => blank($activeCategory)])>
                                        <a href="{{ route('packages.index') }}">All Packages ({{ $totalPackages }})</a>
                                    </li>
                                    @foreach ($categories as $category)
                                        <li @class(['active' => $activeCategory === $category['label']])>
                                            <a href="{{ route('packages.index', ['category' => $category['label']]) }}">
                                                {{ $category['label'] }} ({{ $category['count'] }})
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="sidebar-widget category-widget destinations-widget">
                            <div class="widget-title">
                                <h3>Destinations</h3>
                                <i class="flaticon-down-arrow"></i>
                            </div>
                            <div class="widget-content">
                                <ul class="category-list clearfix">
                                    @foreach ($destinations as $destination)
                                        <li>
                                            <a href="{{ route('destinations.show', $destination['slug']) }}">
                                                {{ $destination['title'] }} – {{ $destination['places'] }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="sidebar-widget rating-widget">
                            <div class="widget-title">
                                <h3>Rating</h3>
                                <i class="flaticon-down-arrow"></i>
                            </div>
                            <div class="widget-content">
                                <ul class="rating-list clearfix">
                                    <li><i class="flaticon-star"></i><i class="flaticon-star"></i><i class="flaticon-star"></i><i class="flaticon-star"></i><i class="flaticon-star"></i></li>
                                    <li><i class="flaticon-star"></i><i class="flaticon-star"></i><i class="flaticon-star"></i><i class="flaticon-star"></i><i class="flaticon-star light"></i><span>and above</span></li>
                                    <li><i class="flaticon-star"></i><i class="flaticon-star"></i><i class="flaticon-star"></i><i class="flaticon-star light"></i><i class="flaticon-star light"></i><span>and above</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-md-12 col-sm-12 content-side">
                    <div class="main-content p_relative">
                        <div class="sort-box">
                            <p>Showing {{ $packages->count() }} {{ \Illuminate\Support\Str::plural('result', $packages->count()) }}</p>
                            <div class="select-box">
                                <span class="text">Sort by: </span>
                                <select class="selectmenu" onchange="if (this.value) window.location.href=this.value;">
                                    <option value="{{ route('packages.index', array_filter(['category' => $activeCategory, 'q' => $search ?: null])) }}">Newest</option>
                                    <option value="{{ route('packages.index', array_filter(['category' => 'Safari', 'q' => $search ?: null])) }}">Safari Packages</option>
                                    <option value="{{ route('packages.index', array_filter(['category' => 'Trekking', 'q' => $search ?: null])) }}">Kilimanjaro Treks</option>
                                    <option value="{{ route('packages.index', array_filter(['category' => 'Day Trip', 'q' => $search ?: null])) }}">Day Adventures</option>
                                </select>
                            </div>
                        </div>
                        <div class="row clearfix">
                            @forelse ($packages as $package)
                                @include('partials.aventour.package-card', ['package' => $package])
                            @empty
                                <div class="col-12">
                                    <p>No packages matched your filters. <a href="{{ route('packages.index') }}">View all packages</a>.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
