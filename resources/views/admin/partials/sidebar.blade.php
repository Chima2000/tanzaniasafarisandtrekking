@php
    $isPages = request()->routeIs('admin.pages.*');
    $isFaqs = request()->routeIs('admin.faqs.*');
    $isBlog = request()->routeIs('admin.blog-posts.*');
    $isPackages = request()->routeIs('admin.packages.*');
    $isDestinations = request()->routeIs('admin.destinations.*');
    $isBookings = request()->routeIs('admin.bookings.*');
    $isSettings = request()->routeIs('admin.settings.*');
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
                    <li class="menu-label">
                        <span>Main Menu</span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                            <i class="iconoir-home-simple menu-icon"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <li class="menu-label mt-2">
                        <span>Website</span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $isSettings ? 'active' : '' }}" href="{{ route('admin.settings.edit') }}">
                            <i class="iconoir-settings menu-icon"></i>
                            <span>Site Settings</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $isPages ? 'active' : '' }}" href="{{ route('admin.pages.index') }}">
                            <i class="iconoir-page menu-icon"></i>
                            <span>Pages</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $isFaqs ? 'active' : '' }}" href="{{ route('admin.faqs.index') }}">
                            <i class="iconoir-help-circle menu-icon"></i>
                            <span>FAQs</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $isPackages ? 'active' : '' }}" href="{{ route('admin.packages.index') }}">
                            <i class="iconoir-map menu-icon"></i>
                            <span>Packages</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $isDestinations ? 'active' : '' }}" href="{{ route('admin.destinations.index') }}">
                            <i class="iconoir-pin-alt menu-icon"></i>
                            <span>Destinations</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $isBlog ? 'active' : '' }}" href="{{ route('admin.blog-posts.index') }}">
                            <i class="iconoir-journal-page menu-icon"></i>
                            <span>Blog Posts</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $isBookings ? 'active' : '' }}" href="{{ route('admin.bookings.index') }}">
                            <i class="iconoir-calendar menu-icon"></i>
                            <span>Bookings</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    {{-- end startbar-menu --}}
</div>
{{-- end leftbar-tab-menu --}}
