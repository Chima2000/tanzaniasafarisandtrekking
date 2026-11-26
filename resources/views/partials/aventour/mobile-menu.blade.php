<div class="mobile-menu">
    <div class="menu-backdrop"></div>
    <div class="close-btn"><i class="fas fa-times"></i></div>

    <nav class="menu-box">
        <div class="nav-logo">@include('partials.aventour.brand-logo', ['logoVariant' => 'white'])</div>
        <div class="menu-outer"></div>
        <div class="contact-info">
            <h4>Contact Info</h4>
            <ul>
                <li>Arusha, Tanzania</li>
                <li><a href="tel:+255769079298">+255 769 079 298</a></li>
                <li><a href="mailto:info@tanzaniasafarisandtrekking.com">info@tanzaniasafarisandtrekking.com</a></li>
            </ul>
        </div>
        <div class="social-links">
            <ul class="clearfix">
                <li><a href="{{ route('home') }}"><span class="fab fa-twitter"></span></a></li>
                <li><a href="{{ route('home') }}"><span class="fab fa-facebook-square"></span></a></li>
                <li><a href="{{ route('home') }}"><span class="fab fa-instagram"></span></a></li>
                <li><a href="{{ route('home') }}"><span class="fab fa-youtube"></span></a></li>
            </ul>
        </div>
    </nav>
</div>
