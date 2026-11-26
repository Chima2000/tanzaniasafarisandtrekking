@extends('layouts.aventour')

@section('title', 'Blog | Tanzania Safaris and Trekking')
@section('meta_description', 'Safari tips, Kilimanjaro trek advice, and Northern Circuit destination stories from Tanzania Safaris and Trekking.')
@section('canonical', route('blog'))

@section('content')
    @include('partials.aventour.page-title', [
        'title' => 'Blog',
        'bg' => $pageBg,
        'crumbs' => [
            ['label' => 'Blog'],
        ],
    ])

    <section class="news-section sec-pad-3">
        <div class="auto-container">
            <div class="sec-title centred mb_40">
                <div class="sub-title"><i class="flaticon-parachuting"></i><span>Stories & Guides</span></div>
                <h2>Ideas for Your Next Tanzania Adventure</h2>
            </div>
            <div class="row clearfix">
                @foreach ($posts as $post)
                    <div class="col-lg-4 col-md-6 col-sm-12 news-block">
                        <article class="news-block-one blog-index-card">
                            <div class="image-box">
                                <figure class="image">
                                    <a href="{{ route('blog.show', $post['slug']) }}">
                                        <img src="{{ $post['image'] }}" alt="{{ $post['title'] }}">
                                    </a>
                                </figure>
                                <div class="view-btn">
                                    <a href="{{ $post['image'] }}" class="lightbox-image" data-fancybox="blog-gallery" aria-label="Preview {{ $post['title'] }} image">
                                        <i class="flaticon-zoom-in"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="lower-content">
                                <div class="category-box"><i class="flaticon-calendar-1"></i><span>{{ $post['category'] }}</span></div>
                                <h3><a href="{{ route('blog.show', $post['slug']) }}">{{ $post['title'] }}</a></h3>
                                <div class="lower-box">
                                    <ul class="post-info">
                                        <li><img src="/aventour/assets/images/icons/icon-14.png" alt=""><span>{{ $post['date'] }}</span></li>
                                        <li><img src="/aventour/assets/images/icons/icon-15.png" alt=""><span>{{ $post['author'] }}</span></li>
                                    </ul>
                                    <div class="link">
                                        <a href="{{ route('blog.show', $post['slug']) }}" aria-label="Read {{ $post['title'] }}">
                                            <i class="flaticon-arrow"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
