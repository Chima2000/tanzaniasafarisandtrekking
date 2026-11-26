<link rel="icon" href="{{ asset('logo/tanzania-safaris-and-trekking-logo.png') }}" type="image/png">
<link rel="apple-touch-icon" href="{{ asset('logo/tanzania-safaris-and-trekking-logo.png') }}">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Mona+Sans:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Merienda:wght@300..900&display=swap" rel="stylesheet">

<link href="/aventour/assets/css/fontawesome/fontawesome.css" rel="stylesheet" />
<link href="/aventour/assets/css/fontawesome/brands.css" rel="stylesheet" />
<link href="/aventour/assets/css/fontawesome/regular.css" rel="stylesheet" />
<link href="/aventour/assets/css/fontawesome/solid.css" rel="stylesheet" />

<link href="/aventour/assets/css/flaticon.css" rel="stylesheet">
<link href="/aventour/assets/css/swiper-bundle.min.css" rel="stylesheet">
<link href="/aventour/assets/css/bootstrap.css" rel="stylesheet">
<link href="/aventour/assets/css/jquery.fancybox.min.css" rel="stylesheet">
<link href="/aventour/assets/css/animate.css" rel="stylesheet">
<link href="/aventour/assets/css/nice-select.css" rel="stylesheet">
<link href="/aventour/assets/css/odometer.css" rel="stylesheet">
<link href="/aventour/assets/css/jquery-ui.css" rel="stylesheet">
<link href="/aventour/assets/css/elpath.css" rel="stylesheet">
<link href="/aventour/assets/css/style.css" rel="stylesheet">
<link href="/aventour/assets/css/responsive.css" rel="stylesheet">
<link href="/aventour/assets/css/brand.css?v={{ filemtime(public_path('aventour/assets/css/brand.css')) }}" rel="stylesheet">
<link href="/aventour/assets/css/ajax-loader.css?v={{ filemtime(public_path('aventour/assets/css/ajax-loader.css')) }}" rel="stylesheet">

<style>
    .brand-image-logo {
        display: inline-flex;
        align-items: center;
        line-height: 0;
    }
    .brand-image-logo img {
        display: block;
        width: auto;
        max-width: 100%;
        height: auto;
        object-fit: contain;
    }

    /* Day-by-day itinerary: full-width photos in accordion cards */
    .tour-details .itinerary-content.itinerary-day-by-day .single-item {
        padding-bottom: 30px;
    }
    .tour-details .itinerary-content.itinerary-day-by-day .itinerary-days {
        display: flex;
        flex-direction: column;
        gap: 16px;
        margin-top: 28px;
    }
    .tour-details .itinerary-content.itinerary-day-by-day .itinerary-day {
        background: #f3f6f8;
        border: 1px solid #dde5e9;
        border-radius: 18px;
        overflow: hidden;
    }
    .tour-details .itinerary-content.itinerary-day-by-day .itinerary-day__header {
        display: flex;
        align-items: center;
        gap: 14px;
        width: 100%;
        padding: 18px 22px;
        cursor: pointer;
        list-style: none;
        border-bottom: 1px dashed transparent;
    }
    .tour-details .itinerary-content.itinerary-day-by-day .itinerary-day[open] .itinerary-day__header {
        border-bottom-color: #cfd8de;
    }
    .tour-details .itinerary-content.itinerary-day-by-day .itinerary-day__header::-webkit-details-marker {
        display: none;
    }
    .tour-details .itinerary-content.itinerary-day-by-day .itinerary-day__badge {
        flex-shrink: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 32px;
        padding: 4px 12px;
        border-radius: 8px;
        background: var(--color-primary);
        color: #fff;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.04em;
        line-height: 1;
        white-space: nowrap;
    }
    .tour-details .itinerary-content.itinerary-day-by-day .itinerary-day__title {
        flex: 1;
        margin: 0;
        min-width: 0;
        font-size: 20px;
        line-height: 1.35;
        color: var(--title-color);
    }
    .tour-details .itinerary-content.itinerary-day-by-day .itinerary-day__toggle {
        flex-shrink: 0;
        width: 12px;
        height: 12px;
        border-right: 2px solid #8a949b;
        border-bottom: 2px solid #8a949b;
        transform: rotate(45deg);
        transition: transform 0.2s ease;
        margin-top: -4px;
    }
    .tour-details .itinerary-content.itinerary-day-by-day .itinerary-day[open] .itinerary-day__toggle {
        transform: rotate(-135deg);
        margin-top: 4px;
    }
    .tour-details .itinerary-content.itinerary-day-by-day .itinerary-day__body {
        padding: 8px 22px 22px;
    }
    .tour-details .itinerary-content.itinerary-day-by-day .itinerary-day__image {
        margin: 0 0 18px;
        border-radius: 16px;
        overflow: hidden;
    }
    .tour-details .itinerary-content.itinerary-day-by-day .itinerary-day__image img {
        display: block;
        width: 100%;
        height: auto;
        aspect-ratio: 16 / 9;
        object-fit: cover;
        object-position: center;
        border-radius: 16px;
    }
    .tour-details .itinerary-content.itinerary-day-by-day .itinerary-day__description {
        margin: 0;
        font-size: 16px;
        line-height: 1.7;
        color: #5b6670;
    }
    .tour-details .itinerary-content.itinerary-day-by-day .itinerary-day__meta {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin: 18px 0 0;
    }
    .tour-details .itinerary-content.itinerary-day-by-day .itinerary-day__meta > div {
        display: flex;
        flex-wrap: wrap;
        gap: 6px 10px;
        margin: 0;
    }
    .tour-details .itinerary-content.itinerary-day-by-day .itinerary-day__meta dt {
        margin: 0;
        font-weight: 700;
        color: var(--title-color);
    }
    .tour-details .itinerary-content.itinerary-day-by-day .itinerary-day__meta dd {
        margin: 0;
        color: #5b6670;
    }
    .tour-details .itinerary-content.itinerary-day-by-day .itinerary-day__stay {
        margin-top: 22px;
    }
    .tour-details .itinerary-content.itinerary-day-by-day .itinerary-day__stay h4 {
        margin: 0 0 12px;
        font-size: 16px;
        line-height: 1.4;
        color: var(--title-color);
    }
    .tour-details .itinerary-content.itinerary-day-by-day .itinerary-day__stay-card {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 12px;
        border-radius: 14px;
        background: #fff;
        border: 1px solid #dde5e9;
    }
    .tour-details .itinerary-content.itinerary-day-by-day .itinerary-day__stay-image {
        flex-shrink: 0;
        width: 64px;
        height: 64px;
        margin: 0;
        border-radius: 10px;
        overflow: hidden;
    }
    .tour-details .itinerary-content.itinerary-day-by-day .itinerary-day__stay-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    .tour-details .itinerary-content.itinerary-day-by-day .itinerary-day__stay-copy {
        flex: 1;
        min-width: 0;
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    .tour-details .itinerary-content.itinerary-day-by-day .itinerary-day__stay-type {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: var(--color-primary);
    }
    .tour-details .itinerary-content.itinerary-day-by-day .itinerary-day__stay-copy strong {
        font-size: 15px;
        line-height: 1.35;
        color: var(--title-color);
    }
    .tour-details .itinerary-content.itinerary-day-by-day .itinerary-day__stay-copy span:not(.itinerary-day__stay-type) {
        font-size: 13px;
        color: #7a858e;
    }
    .tour-details .itinerary-content.itinerary-day-by-day .itinerary-day__stay-link {
        flex-shrink: 0;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 14px;
        font-weight: 600;
        color: var(--color-primary);
        white-space: nowrap;
    }
    @media (max-width: 767px) {
        .tour-details .itinerary-content.itinerary-day-by-day .itinerary-day__header {
            padding: 16px;
            gap: 10px;
        }
        .tour-details .itinerary-content.itinerary-day-by-day .itinerary-day__title {
            font-size: 17px;
        }
        .tour-details .itinerary-content.itinerary-day-by-day .itinerary-day__body {
            padding: 6px 16px 16px;
        }
        .tour-details .itinerary-content.itinerary-day-by-day .itinerary-day__stay-card {
            flex-wrap: wrap;
        }
    }
    .tour-sidebar .contact-widget .bg-image {
        background-size: cover !important;
        background-position: center center !important;
        background-repeat: no-repeat !important;
    }

    .page-title .content-box {
        max-width: 100%;
        padding-inline: 12px;
    }
    .page-title h1 {
        max-width: 100%;
        overflow-wrap: anywhere;
        word-break: break-word;
        hyphens: auto;
    }
    .page-title .bread-crumb {
        flex-wrap: wrap;
        row-gap: 6px;
        max-width: 100%;
        padding-inline: 0;
    }
    .page-title .bread-crumb li,
    .page-title .bread-crumb li a,
    .page-title .bread-crumb li span {
        max-width: min(100%, 42rem);
        overflow-wrap: anywhere;
        word-break: break-word;
        line-height: 1.4;
    }
    @media (max-width: 991px) {
        .page-title {
            padding: 100px 0 72px;
        }
        .page-title h1 {
            font-size: clamp(1.5rem, 1.8vw + 1rem, 2.35rem) !important;
            line-height: 1.2 !important;
            margin-bottom: 8px;
        }
    }
    @media (max-width: 767px) {
        .page-title {
            padding: 72px 0 52px;
        }
        .page-title h1 {
            font-size: clamp(1.2rem, 1.6vw + 0.75rem, 1.55rem) !important;
            line-height: 1.25 !important;
            margin-bottom: 6px;
        }
        /* Long package names already appear in the H1 — keep crumbs compact. */
        .page-title .bread-crumb li:nth-last-child(-n + 2) {
            display: none;
        }
    }
    @media (max-width: 499px) {
        .page-title {
            padding: 64px 0 44px;
        }
        .page-title h1 {
            font-size: 1.15rem !important;
            line-height: 1.3 !important;
        }
    }
    .tour-details .title-box .title-text h2 {
        overflow-wrap: anywhere;
        word-break: break-word;
    }
    @media (max-width: 767px) {
        .tour-details .title-box .title-text h2 {
            font-size: clamp(1.25rem, 1.8vw + 0.85rem, 1.65rem);
            line-height: 1.3;
        }
    }
    @media (max-width: 499px) {
        .tour-details .title-box .title-text h2 {
            font-size: 1.25rem;
            line-height: 1.3;
        }
    }

    .package-sidebar .category-widget .category-list li.active a {
        color: var(--theme-color);
    }
    .package-sidebar .category-widget .category-list li.active a:before {
        border-color: var(--theme-color);
    }
    .package-sidebar .category-widget .category-list li.active a:after {
        background-color: var(--theme-color);
    }

    .blog-sidebar .post-widget article {
        align-items: center;
        min-height: 80px;
    }
    .blog-sidebar .post-widget article .image-box {
        width: 80px;
        height: 80px;
        min-width: 80px;
        overflow: hidden;
        border-radius: 50%;
        flex-shrink: 0;
    }
    .blog-sidebar .post-widget article .image-box a {
        display: block;
        width: 100%;
        height: 100%;
    }
    .blog-sidebar .post-widget article .image-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        border-radius: 50%;
        display: block;
    }
    .blog-sidebar .post-widget article .inner {
        min-width: 0;
        flex: 1;
    }
    .blog-sidebar .post-widget article h4 {
        margin-bottom: 6px;
    }
    .blog-sidebar .post-widget article h4 a {
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
        line-clamp: 2;
        overflow: hidden;
        font-size: 18px;
        line-height: 26px;
        max-height: 52px;
    }
</style>
