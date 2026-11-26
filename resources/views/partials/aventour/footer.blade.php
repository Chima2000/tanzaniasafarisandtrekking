<footer id="contact" class="main-footer bg-color-1">
    <div class="auto-container">
        <div class="widget-section">
            <div class="row clearfix">
                <div class="col-lg-3 col-md-6 col-sm-12 footer-column">
                    <div class="footer-widget logo-widget p_relative t_7">
                        @php
                            $footerPhone = $siteSettings['phone'] ?? '+255 769 079 298';
                            $footerWhatsappUrl = ($siteSettings['whatsapp_url'] ?? '') !== ''
                                ? $siteSettings['whatsapp_url']
                                : 'https://wa.me/'.preg_replace('/\D+/', '', $footerPhone);
                        @endphp
                        <figure class="footer-logo">@include('partials.aventour.brand-logo')</figure>
                        <x-rich-text :content="$siteSettings['footer_about'] ?? null" fallback="Expert-led safaris and Kilimanjaro treks across Tanzania." />
                        <ul class="download-list clearfix">
                            <li>
                                <a href="{{ $footerWhatsappUrl }}" target="_blank" rel="noopener">
                                    <img src="/aventour/assets/images/icons/icon-20.png" alt="">
                                    <span>WhatsApp Us</span>
                                    <i class="flaticon-arrow"></i>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('book-now') }}">
                                    <img src="/aventour/assets/images/icons/icon-21.png" alt="">
                                    <span>Book Now</span>
                                    <i class="flaticon-arrow"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 footer-column">
                    <div class="footer-widget links-widget">
                        <div class="widget-title">
                            <h3>Top Picks</h3>
                        </div>
                        <div class="widget-content">
                            <ul class="links-list clearfix">
                                <li><a href="{{ route('packages.safari') }}">Wildlife Tours</a></li>
                                <li><a href="{{ route('packages.trekking') }}">Kilimanjaro Treks</a></li>
                                <li><a href="{{ route('packages.day-trips') }}">Day Adventures</a></li>
                                <li><a href="{{ route('destinations.show', 'serengeti') }}">Serengeti</a></li>
                                <li><a href="{{ route('destinations.show', 'ngorongoro') }}">Ngorongoro</a></li>
                                <li><a href="{{ route('destinations.show', 'tarangire') }}">Tarangire</a></li>
                                <li><a href="{{ route('contact') }}">Custom Safari</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 footer-column">
                    <div class="footer-widget links-widget">
                        <div class="widget-title">
                            <h3>Useful Links</h3>
                        </div>
                        <div class="widget-content">
                            <ul class="links-list clearfix">
                                <li><a href="{{ route('home') }}">Home</a></li>
                                <li><a href="{{ route('packages.index') }}">Tour Packages</a></li>
                                <li><a href="{{ route('destinations.index') }}">Destinations</a></li>
                                <li><a href="{{ route('blog') }}">Blog</a></li>
                                <li><a href="{{ route('about') }}">About Us</a></li>
                                <li><a href="{{ route('faq') }}">Faq’s</a></li>
                                <li><a href="{{ route('contact') }}">Contact Us</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 footer-column">
                    <div class="footer-widget subscribe-widget">
                        <div class="widget-title">
                            <h3>Newsletter</h3>
                        </div>
                        <div class="widget-content">
                            <p>Subscribe for exclusive offers, expert tips, and curated travel inspiration.</p>
                            <form action="{{ route('contact') }}" method="get" data-ajax-loader>
                                <div class="form-group">
                                    <input type="email" name="email" placeholder="Email address" required>
                                    <button type="submit" class="theme-btn"><span>Subscribe Us</span><i class="flaticon-arrow"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-info pb_35">
            <div class="row">
                @php
                    $footerPhone = $siteSettings['phone'] ?? '+255 769 079 298';
                    $footerEmail = $siteSettings['email'] ?? 'info@tanzaniasafarisandtrekking.com';
                    $footerAddress = $siteSettings['address'] ?? 'Arusha, Tanzania';
                    $footerPhoneHref = 'tel:'.preg_replace('/\s+/', '', $footerPhone);
                    $instagramUrl = ($siteSettings['instagram_url'] ?? '') !== '' ? $siteSettings['instagram_url'] : route('home');
                    $facebookUrl = ($siteSettings['facebook_url'] ?? '') !== '' ? $siteSettings['facebook_url'] : route('home');
                    $twitterUrl = ($siteSettings['twitter_url'] ?? '') !== '' ? $siteSettings['twitter_url'] : route('home');
                    $whatsappUrl = ($siteSettings['whatsapp_url'] ?? '') !== '' ? $siteSettings['whatsapp_url'] : route('home');
                @endphp
                <div class="col-lg-4 col-md-6 col-sm-12 info-column">
                    <div class="single-info-box">
                        <div class="text-box">
                            <span>Talk to Us</span>
                            <p><a href="{{ $footerPhoneHref }}">{{ $footerPhone }}</a></p>
                        </div>
                        <div class="icon-box"><img src="/aventour/assets/images/icons/icon-20.png" alt=""></div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12 info-column">
                    <div class="single-info-box">
                        <div class="text-box">
                            <span>Message Us</span>
                            <p><a href="mailto:{{ $footerEmail }}">{{ $footerEmail }}</a></p>
                        </div>
                        <div class="icon-box"><img src="/aventour/assets/images/icons/icon-21.png" alt=""></div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12 info-column">
                    <div class="single-info-box">
                        <div class="text-box">
                            <span>Locate Us</span>
                            <p>{{ $footerAddress }}</p>
                        </div>
                        <div class="icon-box"><img src="/aventour/assets/images/icons/icon-22.png" alt=""></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="bottom-inner">
                <div class="copyright"><p>&copy; <a href="{{ route('home') }}">{{ $siteSettings['company_name'] ?? 'Tanzania Safaris and Trekking' }}</a> {{ date('Y') }}, All rights reserved.</p></div>
                <ul class="social-links">
                    <li><a href="{{ $instagramUrl }}" @if(($siteSettings['instagram_url'] ?? '') !== '') target="_blank" rel="noopener" @endif><i class="flaticon-instagram-logo"></i></a></li>
                    <li><a href="{{ route('home') }}"><i class="flaticon-youtube"></i></a></li>
                    <li><a href="{{ $facebookUrl }}" @if(($siteSettings['facebook_url'] ?? '') !== '') target="_blank" rel="noopener" @endif><i class="flaticon-facebook-app-symbol"></i></a></li>
                    <li><a href="{{ $twitterUrl }}" @if(($siteSettings['twitter_url'] ?? '') !== '') target="_blank" rel="noopener" @endif><i class="flaticon-twitter"></i></a></li>
                    <li><a href="{{ $whatsappUrl }}" @if(($siteSettings['whatsapp_url'] ?? '') !== '') target="_blank" rel="noopener" @endif><i class="flaticon-whatsapp"></i></a></li>
                </ul>
                <div class="right-column">
                    <div class="language-box">
                        <span class="text">Curr:</span>
                        <div class="select-box">
                            <select class="selectmenu">
                                <option>USD</option>
                                <option>EUR</option>
                                <option>TZS</option>
                            </select>
                        </div>
                    </div>
                    <div class="language-box">
                        <span class="text">Lang:</span>
                        <div class="select-box">
                            <select class="selectmenu">
                                <option>En</option>
                                <option>Sw</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
