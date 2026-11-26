<div class="six-item-carousel">
    <div class="swiper-wrapper">
        @foreach ($destinations as $destination)
            <div class="swiper-slide">
                <div class="destinations-block-one">
                    <div class="image-box">
                        <figure class="image"><img src="{{ $destination['image'] }}" alt="{{ $destination['title'] }}"></figure>
                        <div class="location"><i class="flaticon-location"></i><h3>{{ $destination['title'] }}</h3></div>
                    </div>
                    <div class="lower-content">
                        <p>{{ $destination['places'] }} - <span class="special-text">{{ $destination['starts'] }}</span></p>
                        <a href="{{ route('destinations.show', $destination['slug']) }}"><i class="flaticon-arrow"></i></a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="six-item-pagination dots-style-one"></div>
</div>
