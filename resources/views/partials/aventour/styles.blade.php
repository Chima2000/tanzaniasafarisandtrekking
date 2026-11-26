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
<link href="/aventour/assets/css/site-notice.css?v={{ filemtime(public_path('aventour/assets/css/site-notice.css')) }}" rel="stylesheet">

<style>
    /* Honeypot field — hidden from people, visible to naive bots */
    .ohnohoney {
        position: absolute !important;
        left: -10000px !important;
        top: auto !important;
        width: 1px !important;
        height: 1px !important;
        overflow: hidden !important;
        opacity: 0 !important;
        pointer-events: none !important;
    }
</style>

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

    .package-category-intro,
    .package-category-intro .auto-container,
    .package-category-intro__grid,
    .package-category-intro__media {
        overflow: visible !important;
    }
    .package-category-intro {
        padding: 80px 0 30px;
    }
    .package-category-intro__grid {
        display: grid;
        grid-template-columns: minmax(0, 1.15fr) minmax(280px, 0.85fr);
        gap: 48px;
        align-items: stretch;
    }
    .package-category-intro__copy {
        min-width: 0;
    }
    .package-category-intro__copy .sec-title {
        margin-bottom: 22px;
    }
    .package-category-intro__copy .sec-title h2 {
        max-width: 34rem;
    }
    .package-category-intro__text {
        display: grid;
        gap: 16px;
        margin-bottom: 28px;
    }
    .package-category-intro__text p {
        margin: 0;
        line-height: 1.8;
        color: #5a5a5a;
    }
    .package-category-intro__lead {
        font-size: 18px;
        color: #222 !important;
        line-height: 1.7 !important;
    }
    .package-category-intro__points {
        display: grid;
        gap: 18px;
    }
    .package-category-intro__point {
        padding: 18px 0 0;
        border-top: 1px solid rgba(0, 0, 0, 0.08);
    }
    .package-category-intro__point h3 {
        margin: 0 0 8px;
        font-size: 20px;
        line-height: 1.35;
    }
    .package-category-intro__point p {
        margin: 0;
        line-height: 1.7;
        color: #5a5a5a;
    }
    .package-category-intro__media {
        min-width: 0;
        height: 100%;
    }
    .package-category-intro__sticky {
        position: sticky;
        top: 120px;
        z-index: 5;
    }
    .package-category-intro__figure {
        margin: 0;
        overflow: hidden;
        border-radius: 8px;
        background: #f3f3f3;
    }
    .package-category-intro__figure img {
        display: block;
        width: 100%;
        height: min(70vh, 640px);
        object-fit: cover;
    }
    @media (max-width: 991px) {
        .package-category-intro {
            padding: 50px 0 10px;
        }
        .package-category-intro__grid {
            grid-template-columns: 1fr;
            gap: 28px;
        }
        .package-category-intro__media {
            order: -1;
            height: auto;
        }
        .package-category-intro__sticky {
            position: static;
            top: auto;
        }
        .package-category-intro__figure img {
            height: min(52vh, 420px);
        }
    }

    /* About page company sections */
    .about-company-intro__copy p + p {
        margin-top: 14px;
    }
    .about-highlights-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 24px;
    }
    .about-highlight-card {
        height: 100%;
        padding: 36px 32px;
        background: #fff;
        border: 1px solid rgba(0, 0, 0, 0.06);
        border-radius: 8px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }
    .about-highlight-card--alt {
        background: #f7f4ef;
    }
    .about-highlight-card__icon {
        margin-bottom: 18px;
    }
    .about-highlight-card__icon img {
        display: block;
        width: 48px;
        height: auto;
    }
    .about-highlight-card h3 {
        margin: 0 0 12px;
        font-size: 26px;
        line-height: 1.3;
    }
    .about-highlight-card p {
        margin: 0;
        line-height: 1.75;
        color: #5a5a5a;
    }
    @media (max-width: 767px) {
        .about-highlights-grid {
            grid-template-columns: 1fr;
            gap: 16px;
        }
        .about-highlight-card {
            padding: 28px 24px;
        }
        .about-highlight-card h3 {
            font-size: 22px;
        }
    }
    .about-info-card {
        height: 100%;
        padding: 36px 32px;
        background: #fff;
        border: 1px solid rgba(0, 0, 0, 0.06);
        border-radius: 8px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }
    .about-info-card--alt {
        background: #f7f4ef;
    }
    .about-info-card__label {
        display: inline-block;
        margin-bottom: 14px;
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #8a6a3d;
    }
    .about-info-card h3 {
        margin: 0 0 14px;
        font-size: 28px;
        line-height: 1.3;
    }
    .about-info-card p {
        margin: 0 0 18px;
        line-height: 1.75;
        color: #5a5a5a;
    }
    .about-info-card__list {
        margin: 0;
        padding: 0;
        list-style: none;
    }
    .about-info-card__list li {
        position: relative;
        padding-left: 18px;
        margin-bottom: 10px;
        line-height: 1.6;
        color: #444;
    }
    .about-info-card__list li::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0.65em;
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #8a6a3d;
    }
    .about-story-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.15fr) minmax(280px, 0.85fr);
        gap: 48px;
        align-items: stretch;
        overflow: visible;
    }
    .about-story-grid__text {
        display: grid;
        gap: 16px;
        margin-bottom: 28px;
    }
    .about-story-grid__text p {
        margin: 0;
        line-height: 1.8;
        color: #5a5a5a;
    }
    .about-story-grid__meta {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 14px;
    }
    .about-story-fact {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        min-height: 100%;
        padding: 18px 16px;
        background: #fff;
        border: 1px solid rgba(0, 0, 0, 0.06);
        border-radius: 10px;
        box-shadow: 0 8px 22px rgba(0, 0, 0, 0.04);
    }
    .about-story-fact__index {
        flex-shrink: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 34px;
        height: 34px;
        border-radius: 999px;
        background: rgba(138, 106, 61, 0.12);
        color: #8a6a3d;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.04em;
    }
    .about-story-fact__copy {
        min-width: 0;
    }
    .about-story-fact__copy strong {
        display: block;
        margin: 2px 0 6px;
        font-size: 13px;
        line-height: 1.2;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #222;
    }
    .about-story-fact__copy span {
        display: block;
        font-size: 15px;
        line-height: 1.45;
        color: #5a5a5a;
    }
    .about-story-grid__media {
        overflow: visible;
        height: 100%;
    }
    .about-story-grid__sticky {
        position: sticky;
        top: 120px;
    }
    .about-story-grid__sticky figure {
        margin: 0;
        overflow: hidden;
        border-radius: 8px;
    }
    .about-story-grid__sticky img {
        display: block;
        width: 100%;
        height: min(70vh, 620px);
        object-fit: cover;
    }
    .about-service-card,
    .about-why-card,
    .about-process-card {
        height: 100%;
        background: #fff;
        border-radius: 8px;
        border: 1px solid rgba(0, 0, 0, 0.06);
        padding: 24px;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    .about-service-card:hover,
    .about-why-card:hover,
    .about-process-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 14px 30px rgba(0, 0, 0, 0.08);
    }
    .about-service-card__image {
        margin: 0 0 18px;
        overflow: hidden;
        border-radius: 6px;
    }
    .about-service-card__image img {
        display: block;
        width: 100%;
        height: 180px;
        object-fit: cover;
    }
    .about-service-card h3,
    .about-why-card h3,
    .about-process-card h3 {
        margin: 0 0 10px;
        font-size: 22px;
        line-height: 1.35;
    }
    .about-service-card p,
    .about-why-card p,
    .about-process-card p {
        margin: 0 0 16px;
        line-height: 1.7;
        color: #5a5a5a;
    }
    .about-service-card a {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
        color: #222;
    }
    .about-why-card .icon-box {
        margin-bottom: 16px;
    }
    .about-values-list {
        display: grid;
        gap: 18px;
    }
    .about-values-list article {
        padding: 20px 0;
        border-top: 1px solid rgba(0, 0, 0, 0.08);
    }
    .about-values-list article:last-child {
        border-bottom: 1px solid rgba(0, 0, 0, 0.08);
    }
    .about-values-list h3 {
        margin: 0 0 8px;
        font-size: 22px;
    }
    .about-values-list p {
        margin: 0;
        line-height: 1.75;
        color: #5a5a5a;
    }
    .about-process-card__step {
        display: inline-block;
        margin-bottom: 14px;
        font-size: 14px;
        font-weight: 700;
        letter-spacing: 0.08em;
        color: #8a6a3d;
    }
    .about-cta-banner {
        position: relative;
        overflow: hidden;
        border-radius: 12px;
        min-height: 360px;
        background-size: cover;
        background-position: center;
        display: flex;
        align-items: center;
    }
    .about-cta-banner__overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(90deg, rgba(16, 16, 16, 0.82) 0%, rgba(16, 16, 16, 0.55) 100%);
    }
    .about-cta-banner__content {
        position: relative;
        z-index: 1;
        padding: 60px 50px;
        max-width: 760px;
    }
    .about-cta-banner__actions {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 28px;
    }
    .about-cta-banner__secondary {
        background: transparent !important;
        border: 1px solid rgba(255, 255, 255, 0.7);
    }
    .about-cta-banner .sec-title.light p,
    .about-cta-banner .sec-title.light h2,
    .about-cta-banner .sec-title.light .sub-title span {
        color: #fff;
    }
    @media (max-width: 991px) {
        .about-story-grid {
            grid-template-columns: 1fr;
            gap: 28px;
        }
        .about-story-grid__media {
            order: -1;
            height: auto;
        }
        .about-story-grid__sticky {
            position: static;
        }
        .about-story-grid__meta {
            grid-template-columns: 1fr;
        }
        .about-story-fact {
            padding: 16px;
        }
        .about-cta-banner__content {
            padding: 40px 24px;
        }
    }

    /* Contact page: full-width map above footer */
    .contact-map-section {
        position: relative;
        display: block;
        width: 100%;
        margin: 0;
        padding: 0;
        line-height: 0;
    }
    .contact-map-section .map-inner {
        position: relative;
        left: auto;
        top: auto;
        width: 100%;
        height: 450px;
        border-radius: 0;
        overflow: hidden;
        margin: 0;
    }
    .contact-map-section .map-inner iframe {
        display: block;
        width: 100%;
        height: 100%;
        border: 0;
    }
    @media (max-width: 767px) {
        .contact-map-section .map-inner {
            height: 320px;
        }
    }
</style>

<style>
.rich-text > *:first-child { margin-top: 0; }
.rich-text > *:last-child { margin-bottom: 0; }
.rich-text img,
.rich-text video,
.rich-text iframe { max-width: 100%; height: auto; border-radius: 8px; }
.rich-text iframe { width: 100%; aspect-ratio: 16 / 9; }
.rich-text ul, .rich-text ol { padding-left: 1.25rem; margin-bottom: 1rem; }
.rich-text a,
.has-internal-links a,
a.internal-link {
    color: var(--color-primary);
    font-weight: 700;
    text-decoration: underline;
    text-underline-offset: 0.18em;
    text-decoration-thickness: 1.5px;
}
.rich-text a:hover,
.has-internal-links a:hover,
a.internal-link:hover {
    color: var(--color-primary-hover);
}
.overview-content .rich-text,
.itinerary-day__body .rich-text,
.destination-guide__story-intro .rich-text,
.destination-guide__chapter-body .rich-text { margin-bottom: 1rem; }
</style>
