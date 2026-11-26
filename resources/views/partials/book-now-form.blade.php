<form
    id="bookNowForm"
    class="book-now-form"
    method="POST"
    action="{{ route('book-now.store') }}"
    data-start-step="{{ $plannerStartStep }}"
    data-success-message="{{ session('success') }}"
    novalidate
>
    @csrf
    <input type="hidden" name="package" value="{{ $plannerPackageSlug }}">

    <div class="sec-title mb_25">
        <div class="sub-title"><i class="flaticon-parachuting"></i><span>Booking Request</span></div>
        <h2>Tell Us About Your Trip</h2>
    </div>

    <div class="book-now-progress" data-book-steps>
        <div class="book-now-progress__track">
            <button type="button" class="active" data-step-jump="1" data-step-label="1">
                <span>01</span>
                <em>Trip</em>
            </button>
            <button type="button" data-step-jump="2" data-step-label="2">
                <span>02</span>
                <em>Places</em>
            </button>
            <button type="button" data-step-jump="3" data-step-label="3">
                <span>03</span>
                <em>Style</em>
            </button>
            <button type="button" data-step-jump="4" data-step-label="4">
                <span>04</span>
                <em>Details</em>
            </button>
        </div>
    </div>

    @if ($errors->any())
        <div class="book-now-alert" role="alert">
            <strong>Please fix the highlighted fields below.</strong>
        </div>
    @endif

    <div class="book-now-panels">
        <div class="book-now-panel active" data-book-panel="1" data-validate="adults,duration_days,travel_date">
            <h4>Trip basics</h4>
            <p class="mb_20">Group size, length, and preferred start date.</p>
            <div class="row clearfix">
                <div class="col-lg-6 col-md-6 col-sm-12 single-column">
                    <div class="form-group @error('adults') is-invalid @enderror">
                        <label for="book_adults">Adults <em>*</em></label>
                        <input id="book_adults" type="number" name="adults" min="1" max="40" value="{{ old('adults', $plannerDefaultAdults) }}" required>
                        <span class="field-error" data-error-for="adults">@error('adults'){{ $message }}@enderror</span>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 single-column">
                    <div class="form-group @error('children') is-invalid @enderror">
                        <label for="book_children">Children</label>
                        <input id="book_children" type="number" name="children" min="0" max="40" value="{{ old('children', $plannerDefaultChildren) }}">
                        <span class="field-error" data-error-for="children">@error('children'){{ $message }}@enderror</span>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 single-column">
                    <div class="form-group @error('duration_days') is-invalid @enderror">
                        <div class="icon"><img src="/aventour/assets/images/icons/icon-5.png" alt=""></div>
                        <label for="book_duration_days">Trip length <em>*</em></label>
                        <div class="select-box">
                            <select id="book_duration_days" class="wide" name="duration_days" required>
                                @foreach ($plannerDurations as $days)
                                    <option value="{{ $days }}" @selected((int) old('duration_days', $plannerDefaultDuration) === $days)>{{ $days }} days</option>
                                @endforeach
                            </select>
                        </div>
                        <span class="field-error" data-error-for="duration_days">@error('duration_days'){{ $message }}@enderror</span>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 single-column">
                    <div class="form-group @error('travel_date') is-invalid @enderror">
                        <div class="icon"><img src="/aventour/assets/images/icons/icon-5.png" alt=""></div>
                        <label for="book_travel_date">Travel start date <em>*</em></label>
                        <input id="book_travel_date" type="date" name="travel_date" min="{{ $plannerMinDate }}" value="{{ old('travel_date') }}" required>
                        <span class="field-error" data-error-for="travel_date">@error('travel_date'){{ $message }}@enderror</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="book-now-panel" data-book-panel="2" data-validate="destinations">
            <h4>Where do you want to go?</h4>
            <p class="mb_20">Choose one or more places — we’ll build a realistic Northern Circuit order.</p>
            <div class="form-group @error('destinations') is-invalid @enderror @error('destinations.*') is-invalid @enderror">
                <div class="book-now-places" role="group" aria-label="Destinations">
                    @foreach ($plannerDestinations as $destination)
                        <label class="book-now-place">
                            <input
                                type="checkbox"
                                name="destinations[]"
                                value="{{ $destination['slug'] }}"
                                @checked(in_array($destination['slug'], $plannerOldDestinations, true))
                            >
                            <span>
                                <strong>{{ $destination['name'] }}</strong>
                                <small>{{ $destination['region'] }}</small>
                            </span>
                        </label>
                    @endforeach
                </div>
                <span class="field-error" data-error-for="destinations">@error('destinations'){{ $message }}@enderror @error('destinations.*'){{ $message }}@enderror</span>
            </div>
            <div class="form-group @error('interest') is-invalid @enderror">
                <label for="book_interest">Trip focus</label>
                <div class="select-box">
                    <select id="book_interest" class="wide" name="interest">
                        <option value="">Select one</option>
                        @foreach ($plannerInterests as $option)
                            <option value="{{ $option }}" @selected(old('interest', request('interest')) === $option)>{{ $option }}</option>
                        @endforeach
                    </select>
                </div>
                <span class="field-error" data-error-for="interest">@error('interest'){{ $message }}@enderror</span>
            </div>
        </div>

        <div class="book-now-panel" data-book-panel="3" data-validate="budget,accommodation">
            <h4>Budget and stay style</h4>
            <p class="mb_20">Helps us shortlist camps and lodges that fit.</p>
            <div class="row clearfix">
                <div class="col-lg-6 col-md-6 col-sm-12 single-column">
                    <div class="form-group @error('budget') is-invalid @enderror">
                        <label for="book_budget">Budget feel <em>*</em></label>
                        <div class="select-box">
                            <select id="book_budget" class="wide" name="budget" required>
                                @foreach ($plannerBudgets as $option)
                                    <option value="{{ $option }}" @selected(old('budget', $plannerDefaultBudget) === $option)>{{ $option }}</option>
                                @endforeach
                            </select>
                        </div>
                        <span class="field-error" data-error-for="budget">@error('budget'){{ $message }}@enderror</span>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 single-column">
                    <div class="form-group @error('accommodation') is-invalid @enderror">
                        <label for="book_accommodation">Accommodation style <em>*</em></label>
                        <div class="select-box">
                            <select id="book_accommodation" class="wide" name="accommodation" required>
                                <option value="">Select one</option>
                                @foreach ($plannerAccommodations as $option)
                                    <option value="{{ $option['value'] }}" @selected(old('accommodation') === $option['value'])>{{ $option['label'] }} — {{ $option['blurb'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <span class="field-error" data-error-for="accommodation">@error('accommodation'){{ $message }}@enderror</span>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 single-column">
                    <div class="form-group @error('notes') is-invalid @enderror">
                        <label for="book_notes">Must-sees &amp; notes</label>
                        <textarea id="book_notes" name="notes" placeholder="Migration timing, Machame vs Lemosho, dietary needs…">{{ old('notes') }}</textarea>
                        <span class="field-error" data-error-for="notes">@error('notes'){{ $message }}@enderror</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="book-now-panel" data-book-panel="4" data-validate="full_name,email">
            <h4>Your details</h4>
            <p class="mb_20">Where should we send the draft itinerary?</p>
            <div class="row clearfix">
                <div class="col-lg-6 col-md-6 col-sm-12 single-column">
                    <div class="form-group @error('full_name') is-invalid @enderror">
                        <label for="book_full_name">Full name <em>*</em></label>
                        <input id="book_full_name" type="text" name="full_name" value="{{ old('full_name') }}" placeholder="Your name" required autocomplete="name">
                        <span class="field-error" data-error-for="full_name">@error('full_name'){{ $message }}@enderror</span>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 single-column">
                    <div class="form-group @error('email') is-invalid @enderror">
                        <label for="book_email">Email <em>*</em></label>
                        <input id="book_email" type="email" name="email" value="{{ old('email') }}" placeholder="you@email.com" required autocomplete="email">
                        <span class="field-error" data-error-for="email">@error('email'){{ $message }}@enderror</span>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 single-column">
                    <div class="form-group @error('phone') is-invalid @enderror">
                        <label for="book_phone">Phone / WhatsApp</label>
                        <input id="book_phone" type="tel" name="phone" value="{{ old('phone') }}" placeholder="+255 …" autocomplete="tel">
                        <span class="field-error" data-error-for="phone">@error('phone'){{ $message }}@enderror</span>
                    </div>
                </div>
            </div>

            <div class="book-now-summary" data-book-summary>
                <div class="book-now-summary__head">
                    <div class="sec-title mb_0">
                        <div class="sub-title"><i class="flaticon-parachuting"></i><span>Review</span></div>
                        <h4>Request Summary</h4>
                    </div>
                    <p class="book-now-summary__intro">Confirm the details below before you send. Tap the <i class="fas fa-pen" aria-hidden="true"></i> pen on any row to edit that part quickly.</p>
                </div>
                <ul class="book-now-summary__list list-item clearfix" data-book-summary-list></ul>
            </div>
        </div>
    </div>

    <div class="book-now-actions clearfix">
        <button type="button" class="theme-btn secondary-bg" data-book-prev disabled><span>Back</span></button>
        <div class="message-btn">
            <button type="button" class="theme-btn" data-book-next><span>Continue</span><i class="flaticon-arrow"></i></button>
            <button type="submit" class="theme-btn" data-book-submit hidden disabled><span>Send Booking Request</span><i class="flaticon-arrow"></i></button>
        </div>
    </div>
</form>
