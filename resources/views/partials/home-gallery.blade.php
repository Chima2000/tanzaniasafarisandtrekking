<div class="sortable-masonry">
    <div class="items-container row clearfix">
        @if (isset($galleryImages[0]))
            <div class="col-lg-6 col-md-12 col-sm-12 gallery-block masonry-item small-column all">
                <div class="gallery-block-one">
                    <figure class="image-box"><img src="{{ $galleryImages[0] }}" alt="Tanzania safari gallery"></figure>
                    <div class="view-btn"><a href="{{ $galleryImages[0] }}" class="lightbox-image" data-fancybox="gallery"><i class="flaticon-zoom-in"></i></a></div>
                </div>
            </div>
        @endif
        <div class="col-lg-6 col-md-12 col-sm-12 gallery-block masonry-item small-column all">
            <div class="row clearfix">
                @foreach (array_slice($galleryImages, 1, 2) as $galleryImage)
                    <div class="col-lg-6 col-md-6 col-sm-12 gallery-block masonry-item small-column all">
                        <div class="gallery-block-one">
                            <figure class="image-box"><img src="{{ $galleryImage }}" alt="Tanzania safari gallery"></figure>
                            <div class="view-btn"><a href="{{ $galleryImage }}" class="lightbox-image" data-fancybox="gallery"><i class="flaticon-zoom-in"></i></a></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @if (isset($galleryImages[3]))
            <div class="col-lg-6 col-md-12 col-sm-12 gallery-block masonry-item small-column all">
                <div class="gallery-block-one">
                    <figure class="image-box"><img src="{{ $galleryImages[3] }}" alt="Tanzania safari gallery"></figure>
                    <div class="view-btn"><a href="{{ $galleryImages[3] }}" class="lightbox-image" data-fancybox="gallery"><i class="flaticon-zoom-in"></i></a></div>
                </div>
            </div>
        @endif
        <div class="col-lg-6 col-md-12 col-sm-12 gallery-block masonry-item small-column all">
            <div class="row clearfix">
                @foreach (array_slice($galleryImages, 4, 2) as $galleryImage)
                    <div class="col-lg-6 col-md-6 col-sm-12 gallery-block masonry-item small-column all">
                        <div class="gallery-block-one">
                            <figure class="image-box"><img src="{{ $galleryImage }}" alt="Tanzania safari gallery"></figure>
                            <div class="view-btn"><a href="{{ $galleryImage }}" class="lightbox-image" data-fancybox="gallery"><i class="flaticon-zoom-in"></i></a></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @foreach (array_slice($galleryImages, 6, 2) as $galleryImage)
            <div class="col-lg-6 col-md-6 col-sm-12 gallery-block masonry-item small-column all">
                <div class="gallery-block-one">
                    <figure class="image-box"><img src="{{ $galleryImage }}" alt="Tanzania safari gallery"></figure>
                    <div class="view-btn"><a href="{{ $galleryImage }}" class="lightbox-image" data-fancybox="gallery"><i class="flaticon-zoom-in"></i></a></div>
                </div>
            </div>
        @endforeach
    </div>
</div>
