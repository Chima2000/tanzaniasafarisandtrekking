@php
    $spamGuard = \App\Support\FormSpamGuard::issue();
    $honeypotId = 'company_website_'.uniqid();
@endphp
{{-- Invisible honeypot + timed token — bots fill the website field or submit too fast --}}
<div class="ohnohoney" aria-hidden="true">
    <label for="{{ $honeypotId }}">Company website</label>
    <input
        type="text"
        name="{{ \App\Support\FormSpamGuard::HONEYPOT_FIELD }}"
        id="{{ $honeypotId }}"
        value=""
        tabindex="-1"
        autocomplete="off"
    >
</div>
<input type="hidden" name="{{ \App\Support\FormSpamGuard::STARTED_AT_FIELD }}" value="{{ $spamGuard['started_at'] }}">
<input type="hidden" name="{{ \App\Support\FormSpamGuard::TOKEN_FIELD }}" value="{{ $spamGuard['token'] }}">
