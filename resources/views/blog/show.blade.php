@extends('layouts.aventour')

@section('title', $post['seo_title'])
@section('meta_description', $post['seo_description'])
@section('meta_keywords', $post['seo_keywords'] ?? '')
@section('canonical', route('blog.show', $post['slug']))
@section('og_type', 'article')
@section('og_title', $post['seo_title'])
@section('og_description', $post['seo_description'])
@section('og_image', absolute_url($post['image']) ?? '')
@section('twitter_title', $post['seo_title'])
@section('twitter_description', $post['seo_description'])
@section('twitter_image', absolute_url($post['image']) ?? '')

@section('content')
    <section class="page-title pt_150 pb_150 centred">
        <div class="bg-layer" style="background-image: url('{{ $pageBg }}');"></div>
        <div class="auto-container">
            <div class="content-box">
                <div class="category-box"><i class="flaticon-calendar-1"></i><span>{{ $post['category'] }}</span></div>
                <h1>{{ $post['title'] }}</h1>
                <nav aria-label="Breadcrumb">
                    <ul class="bread-crumb">
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li aria-hidden="true"><i class="flaticon-arrow-angle-pointing-to-right"></i></li>
                        <li><a href="{{ route('blog') }}">Blog</a></li>
                        <li aria-hidden="true"><i class="flaticon-arrow-angle-pointing-to-right"></i></li>
                        <li><span aria-current="page">{{ $post['title'] }}</span></li>
                    </ul>
                </nav>
                <ul class="post-info">
                    <li><img src="/aventour/assets/images/icons/icon-59.png" alt=""><span>{{ $post['date'] }}</span></li>
                    <li><img src="/aventour/assets/images/icons/icon-60.png" alt=""><span>{{ $post['author'] }}</span></li>
                </ul>
            </div>
        </div>
    </section>

    <section class="sidebar-page-container p_relative sec-pad-3">
        <div class="auto-container">
            <div class="row clearfix">
                <div class="col-lg-8 col-md-12 col-sm-12 content-side">
                    <article class="blog-details-content">
                        <div class="content-one mb_35">
                            <figure class="image-box"><img src="{{ $post['image'] }}" alt="{{ $post['title'] }}"></figure>
                            <x-rich-text :content="$post['intro'] ?? null" />
                        </div>

                        @foreach ($post['sections'] as $section)
                            <div class="content-three mb_35">
                                <div class="group-title mb_20">
                                    <h2>{{ $section['title'] }}</h2>
                                </div>
                                <x-rich-text :content="$section['body'] ?? null" />
                                <ul class="list-item clearfix has-internal-links">
                                    @foreach ($section['points'] as $point)
                                        <li><i class="flaticon-takeoff"></i><span>{!! linked($point) !!}</span></li>
                                    @endforeach
                                </ul>
                            </div>

                            @if ($loop->first && ! empty($post['quote']))
                                <blockquote class="mb_35">
                                    <div class="bg-image" style="background-image: url('{{ $post['image'] }}');"></div>
                                    <div class="quote-icon">
                                        <div class="shape" style="background-image: url(/aventour/assets/images/shape/shape-13.png);"></div>
                                        <img src="/aventour/assets/images/icons/icon-16.png" alt="">
                                    </div>
                                    <x-rich-text :content="$post['quote']['text'] ?? null" />
                                    <span class="name">{{ $post['quote']['name'] }}</span>
                                </blockquote>
                            @endif
                        @endforeach

                        @if (! empty($post['tips']))
                            <div class="content-three mb_35">
                                <div class="group-title mb_20">
                                    <h2>Practical Tips</h2>
                                </div>
                                <ul class="list-item clearfix has-internal-links">
                                    @foreach ($post['tips'] as $tip)
                                        <li><i class="flaticon-takeoff"></i><span>{!! linked($tip) !!}</span></li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="content-three mb_35">
                            <div class="mt_10">
                                <a href="{{ $ctaUrl }}" class="theme-btn"><span>{{ $post['cta_label'] }}</span><i class="flaticon-arrow"></i></a>
                            </div>
                        </div>

                        @if (! empty($post['tags']))
                            <div class="content-four pb_30">
                                <h6><img src="/aventour/assets/images/icons/icon-75.png" alt=""><span>Post Tags</span></h6>
                                <ul class="tags-list clearfix">
                                    @foreach ($post['tags'] as $tag)
                                        <li><a href="{{ route('blog') }}">{{ $tag }}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="post-nav mb_35">
                            <div class="post-nav-btn">
                                <div class="single-btn">
                                    <h5><img src="/aventour/assets/images/icons/icon-77.png" alt=""><span>Prev Post</span></h5>
                                    @if ($previous)
                                        <h4><a href="{{ route('blog.show', $previous['slug']) }}">{{ $previous['title'] }}</a></h4>
                                    @else
                                        <h4><span>You are at the latest story</span></h4>
                                    @endif
                                </div>
                                <div class="single-btn align-3">
                                    <h5><span>Next Post</span><img src="/aventour/assets/images/icons/icon-76.png" alt=""></h5>
                                    @if ($next)
                                        <h4><a href="{{ route('blog.show', $next['slug']) }}">{{ $next['title'] }}</a></h4>
                                    @else
                                        <h4><span>More stories coming soon</span></h4>
                                    @endif
                                </div>
                            </div>
                            <div class="btn-box">
                                <a href="{{ route('blog') }}"><img src="/aventour/assets/images/icons/icon-78.png" alt=""><span>Back to Blog</span></a>
                            </div>
                        </div>
                    </article>
                </div>

                <div class="col-lg-4 col-md-12 col-sm-12 sidebar-side">
                    <aside class="blog-sidebar ml_20">
                        <div class="sidebar-widget post-widget">
                            <div class="widget-title">
                                <h3>More Stories</h3>
                            </div>
                            <div class="post-inner">
                                @foreach ($relatedPosts as $related)
                                    <article class="post">
                                        <figure class="image-box">
                                            <a href="{{ route('blog.show', $related['slug']) }}">
                                                <img src="{{ $related['image'] }}" alt="{{ $related['title'] }}">
                                            </a>
                                        </figure>
                                        <div class="inner">
                                            <h4><a href="{{ route('blog.show', $related['slug']) }}">{{ $related['title'] }}</a></h4>
                                            <div class="post-date"><i class="flaticon-calendar-1"></i><span>{{ $related['date'] }}</span></div>
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                        </div>

                        <div class="sidebar-widget category-widget">
                            <div class="widget-title">
                                <h3>Keep Exploring</h3>
                            </div>
                            <div class="widget-content">
                                <ul class="category-list clearfix">
                                    <li><a href="{{ route('destinations.index') }}"><span>Destinations</span></a></li>
                                    <li><a href="{{ route('packages.safari') }}"><span>Safari Packages</span></a></li>
                                    <li><a href="{{ route('packages.trekking') }}"><span>Kilimanjaro Treks</span></a></li>
                                    <li><a href="{{ route('contact') }}"><span>Plan Your Trip</span></a></li>
                                </ul>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </section>
@endsection
