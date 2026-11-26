@php
    /** @var \Illuminate\Support\Collection<int, array<string, mixed>> $items */
    $items = $items ?? collect();
@endphp

<div class="three-item-carousel">
    <div class="swiper-wrapper">
        @foreach ($items as $post)
            <div class="swiper-slide">
                <article class="news-block-one">
                    <div class="image-box">
                        <figure class="image">
                            <a href="{{ route('blog.show', $post['slug']) }}">
                                <img src="{{ $post['image'] }}" alt="{{ $post['title'] }}">
                            </a>
                        </figure>
                        <div class="view-btn">
                            <a href="{{ $post['image'] }}" class="lightbox-image" data-fancybox="gallery" aria-label="Preview {{ $post['title'] }} image">
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
    <div class="three-item-pagination dots-style-one"></div>
</div>
