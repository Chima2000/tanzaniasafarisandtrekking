{{-- Centered notice popup — reuses Aventour close-search / theme-btn / sec-title patterns --}}
<div id="siteNoticePopup" class="site-notice-popup" aria-hidden="true" hidden>
    <div class="site-notice-popup__overlay overlay-layer" data-site-notice-close></div>
    <div class="site-notice-popup__dialog popup-inner" role="alertdialog" aria-modal="true" aria-labelledby="siteNoticeTitle" aria-describedby="siteNoticeMessage">
        <button type="button" class="close-search site-notice-popup__close" data-site-notice-close aria-label="Close notification">
            <i class="fas fa-times"></i>
        </button>
        <div class="site-notice-popup__icon" data-site-notice-icon aria-hidden="true">
            <i class="flaticon-check"></i>
        </div>
        <div class="sec-title centred mb_20">
            <div class="sub-title"><i class="flaticon-parachuting"></i><span data-site-notice-eyebrow>Notice</span></div>
            <h2 id="siteNoticeTitle" data-site-notice-title>Thank you!</h2>
            <p id="siteNoticeMessage" data-site-notice-message></p>
        </div>
        <div class="message-btn centred">
            <button type="button" class="theme-btn" data-site-notice-close>
                <span>Close</span><i class="flaticon-arrow"></i>
            </button>
        </div>
    </div>
</div>

@if (session('success'))
    <div id="siteNoticeBoot" hidden data-type="success" data-eyebrow="Confirmed" data-title="Thank you!" data-message="{{ session('success') }}"></div>
@endif
