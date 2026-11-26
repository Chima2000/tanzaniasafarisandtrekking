@php
    $slides = array_values($value ?: []);
    if ($slides === []) {
        $slides = app(\App\Support\HomeHeroSlides::class)->defaultSlides();
    }
@endphp

<div
    class="cms-hero-slides"
    data-cms-hero-slides
    data-cms-section="{{ $sectionId }}"
    data-next-index="{{ count($slides) }}"
>
    <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
        <div>
            <h5 class="mb-1">{{ $field['label'] }}</h5>
            <p class="text-muted small mb-0">Add as many image or video slides as you need. Autosave keeps the public homepage in sync.</p>
        </div>
        <button type="button" class="btn btn-sm btn-primary" data-hero-slide-add>Add slide</button>
    </div>

    <div class="cms-hero-slides__list" data-hero-slides-list>
        @foreach ($slides as $index => $slide)
            @include('admin.pages._hero_slide_item', [
                'index' => $index,
                'slide' => $slide,
                'sectionId' => $sectionId,
            ])
        @endforeach
    </div>

    <template data-hero-slide-template>
        @include('admin.pages._hero_slide_item', [
            'index' => '__INDEX__',
            'slide' => [
                'type' => 'image',
                'media' => '',
                'poster' => '',
                'position' => '50% 50%',
                'kicker' => '',
                'title' => '',
                'body' => '',
                'cta' => 'See Packages',
                'cta_url' => '/safari-packages',
                'badge_label' => 'Adventures start from',
                'badge_value' => '',
                'badge_text' => 'Packages',
            ],
            'sectionId' => $sectionId,
        ])
    </template>
</div>
