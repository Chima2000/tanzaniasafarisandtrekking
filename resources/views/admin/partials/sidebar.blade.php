@php
    $isPages = request()->routeIs('admin.pages.*');
    $isFaqs = request()->routeIs('admin.faqs.*');
    $isBlog = request()->routeIs('admin.blog-posts.*');
    $isPackages = request()->routeIs('admin.packages.*');
    $isDestinations = request()->routeIs('admin.destinations.*');
    $isBookings = request()->routeIs('admin.bookings.*');
    $isContacts = request()->routeIs('admin.contacts.*');
    $isSettings = request()->routeIs('admin.settings.*');
    $isProfile = request()->routeIs('admin.profile.*');
    $isNotifications = request()->routeIs('admin.notifications.*');
    $isSitemap = request()->routeIs('admin.sitemap.*');
    $isWhatsapp = request()->routeIs('admin.whatsapp.*');
    $isMedia = request()->routeIs('admin.media.*');
@endphp
{{-- leftbar-tab-menu --}}
<div class="startbar d-print-none">
    {{-- start brand --}}
    <div class="brand">
        <a href="{{ route('admin.dashboard') }}" class="logo">
            <span>
                <img src="{{ asset('logo/tanzaniasafaritrekkinglogowhite.png') }}" alt="logo-small" class="logo-sm">
            </span>
            <span>
                <img src="{{ asset('logo/tanzaniasafaritrekkinglogowhite.png') }}" alt="Tanzania Safaris and Trekking" class="logo-lg logo-light">
                <img src="{{ asset('logo/tanzaniasafaritrekkinglogowhite.png') }}" alt="Tanzania Safaris and Trekking" class="logo-lg logo-dark">
            </span>
        </a>
    </div>
    {{-- end brand --}}
    {{-- start startbar-menu --}}
    <div class="startbar-menu">
        <div class="startbar-collapse" id="startbarCollapse" data-simplebar>
            <div class="d-flex align-items-start flex-column w-100">
                <ul class="navbar-nav mb-auto w-100">
                    <li class="menu-label pt-0 mt-0">
                        <small class="label-border">
                            <div class="border_left hidden-xs"></div>
                            <div class="border_right"></div>
                        </small>
                        <span>Main</span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                            <i class="iconoir-home-simple menu-icon"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <li class="menu-label mt-2">
                        <small class="label-border">
                            <div class="border_left hidden-xs"></div>
                            <div class="border_right"></div>
                        </small>
                        <span>Tours</span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $isPackages ? 'active' : '' }}" href="{{ route('admin.packages.index') }}">
                            <i class="iconoir-map menu-icon"></i>
                            <span>Packages</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $isDestinations ? 'active' : '' }}" href="{{ route('admin.destinations.index') }}">
                            <i class="iconoir-pin menu-icon"></i>
                            <span>Destinations</span>
                        </a>
                    </li>

                    <li class="menu-label mt-2">
                        <small class="label-border">
                            <div class="border_left hidden-xs"></div>
                            <div class="border_right"></div>
                        </small>
                        <span>Content</span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $isPages ? 'active' : '' }}" href="{{ route('admin.pages.index') }}">
                            <i class="iconoir-page menu-icon"></i>
                            <span>Pages</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $isBlog ? 'active' : '' }}" href="{{ route('admin.blog-posts.index') }}">
                            <i class="iconoir-journal-page menu-icon"></i>
                            <span>Blog Posts</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $isFaqs ? 'active' : '' }}" href="{{ route('admin.faqs.index') }}">
                            <i class="iconoir-help-circle menu-icon"></i>
                            <span>FAQs</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $isMedia ? 'active' : '' }}" href="{{ route('admin.media.index') }}">
                            <i class="iconoir-media-image menu-icon"></i>
                            <span>Media Library</span>
                        </a>
                    </li>

                    <li class="menu-label mt-2">
                        <small class="label-border">
                            <div class="border_left hidden-xs"></div>
                            <div class="border_right"></div>
                        </small>
                        <span>Enquiries</span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $isBookings ? 'active' : '' }}" href="{{ route('admin.bookings.index') }}">
                            <i class="iconoir-calendar menu-icon"></i>
                            <span>Bookings</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $isContacts ? 'active' : '' }}" href="{{ route('admin.contacts.index') }}">
                            <i class="iconoir-mail menu-icon"></i>
                            <span>Contacts</span>
                        </a>
                    </li>

                    <li class="menu-label mt-2">
                        <small class="label-border">
                            <div class="border_left hidden-xs"></div>
                            <div class="border_right"></div>
                        </small>
                        <span>Settings</span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $isProfile ? 'active' : '' }}" href="{{ route('admin.profile.edit') }}">
                            <i class="iconoir-profile-circle menu-icon"></i>
                            <span>My Profile</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $isSettings ? 'active' : '' }}" href="{{ route('admin.settings.edit') }}">
                            <i class="iconoir-settings menu-icon"></i>
                            <span>Site Settings</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $isNotifications ? 'active' : '' }}" href="{{ route('admin.notifications.index') }}">
                            <i class="iconoir-send-mail menu-icon"></i>
                            <span>Email Notifications</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $isWhatsapp ? 'active' : '' }}" href="{{ route('admin.whatsapp.edit') }}">
                            <i class="lab la-whatsapp menu-icon"></i>
                            <span>WhatsApp Widget</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $isSitemap ? 'active' : '' }}" href="{{ route('admin.sitemap.index') }}">
                            <i class="iconoir-sitemap menu-icon"></i>
                            <span>Sitemap</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    {{-- end startbar-menu --}}
</div>
{{-- end leftbar-tab-menu --}}
