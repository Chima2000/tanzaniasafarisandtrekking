@php
    $priceLabel = $package['price_label'] ?? '$—';
    $blockedDates = array_values(array_filter((array) ($package['blocked_dates'] ?? [])));
@endphp

<div
    id="packageBookingModal"
    class="package-booking-modal"
    hidden
    aria-hidden="true"
    data-price-per-person="{{ $package['price_per_person'] ?? 0 }}"
    data-price-label="{{ $priceLabel }}"
    data-blocked-dates='@json($blockedDates)'
    data-package-name="{{ $package['name'] }}"
>
    <div class="package-booking-modal__backdrop" data-booking-modal-close></div>
    <div class="package-booking-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="packageBookingModalTitle">
        <button type="button" class="package-booking-modal__close" data-booking-modal-close aria-label="Close booking">
            <i class="fas fa-times"></i>
        </button>
        <div class="package-booking-modal__header">
            <div class="sub-title"><i class="flaticon-parachuting"></i><span>Continue booking</span></div>
            <h3 id="packageBookingModalTitle">{{ $package['name'] }}</h3>
            <p class="package-booking-modal__price">
                From <strong>{{ $priceLabel }}</strong> / traveler
                · estimated total <strong data-booking-estimated-total>{{ $priceLabel }}</strong>
            </p>
        </div>
        <div class="package-booking-modal__body booking-form book-now-shell">
            @include('partials.book-now-form')
        </div>
    </div>
</div>
