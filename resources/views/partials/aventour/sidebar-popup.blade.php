<div id="sidebar-popup" class="sidebar-popup">
    <div class="popup-inner">
        <div class="close-popup"><i class="flaticon-failed"></i></div>
        <figure class="logo">@include('partials.aventour.brand-logo')</figure>
        <div class="form-inner">
            <form action="{{ route('contact') }}" method="get">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="hello@example.com" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="your password" required>
                </div>
                <div class="forgot-password">
                    <div class="check-box-one">
                        <input class="check" type="checkbox" id="checkbox0">
                        <label for="checkbox0">Remember Me</label>
                    </div>
                    <button type="button">Forgot</button>
                </div>
                <div class="message-btn mb_17">
                    <button type="submit" class="theme-btn"><span>Appointment</span><i class="flaticon-arrow"></i></button>
                </div>
                <div class="text-box">
                    <p>New customer? <a href="{{ route('contact') }}">Create account.</a></p>
                </div>
            </form>
        </div>
        <div class="inner-box">
            <div class="icon-box"><i class="flaticon-calendar-1"></i></div>
            <div class="light-icon"><i class="flaticon-calendar-1"></i></div>
            <h3>Get <span>Custom <br />Tour</span> Solutions Now!</h3>
            <a href="{{ route('book-now') }}" class="theme-btn secondary-bg"><span>Book Now</span><i class="flaticon-arrow"></i></a>
        </div>
        <div class="copyright">
            <p>Copyrights &copy; {{ date('Y') }} <a href="{{ route('home') }}">Tanzania Safaris and Trekking</a>, All Rights Reserved.</p>
        </div>
    </div>
</div>
