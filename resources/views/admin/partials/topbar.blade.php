@php
    $hour = (int) now()->format('G');
    $greeting = $hour < 12 ? 'Good Morning' : ($hour < 18 ? 'Good Afternoon' : 'Good Evening');
    $adminName = auth()->user()->name;
    $adminEmail = auth()->user()->email;
    $avatar = asset('admin/assets/images/users/avatar-1.jpg');
@endphp
{{-- Top Bar Start --}}
<div class="topbar d-print-none">
    <div class="container-xxl">
        <nav class="topbar-custom d-flex justify-content-between" id="topbar-custom">
            <ul class="topbar-item list-unstyled d-inline-flex align-items-center mb-0">
                <li>
                    <button class="nav-link mobile-menu-btn nav-icon" id="togglemenu" type="button">
                        <i class="iconoir-menu-scale"></i>
                    </button>
                </li>
                <li class="mx-3 welcome-text">
                    <h3 class="mb-0 fw-bold text-truncate">{{ $greeting }}, {{ $adminName }}!</h3>
                </li>
            </ul>
            <ul class="topbar-item list-unstyled d-inline-flex align-items-center mb-0">
                <li class="hide-phone app-search">
                    <form role="search" action="{{ route('admin.bookings.index') }}" method="get">
                        <input type="search" name="search" class="form-control top-search mb-0" placeholder="Search here...">
                        <button type="submit"><i class="iconoir-search"></i></button>
                    </form>
                </li>

                <li class="topbar-item">
                    <a class="nav-link nav-icon" href="javascript:void(0);" id="light-dark-mode">
                        <i class="icofont-sun dark-mode"></i>
                        <i class="icofont-moon light-mode"></i>
                    </a>
                </li>

                <li class="topbar-item">
                    <a class="nav-link nav-icon" href="{{ route('home') }}" target="_blank" rel="noopener" title="View website">
                        <i class="iconoir-open-new-window"></i>
                    </a>
                </li>

                <li class="dropdown topbar-item">
                    <a class="nav-link dropdown-toggle arrow-none nav-icon" data-bs-toggle="dropdown" href="#" role="button"
                        aria-haspopup="false" aria-expanded="false">
                        <i class="icofont-bell-alt"></i>
                        @if (($newBookingCount ?? 0) > 0)
                            <span class="alert-badge"></span>
                        @endif
                    </a>
                    <div class="dropdown-menu stop dropdown-menu-end dropdown-lg py-0">
                        <h5 class="dropdown-item-text m-0 py-3 d-flex justify-content-between align-items-center">
                            Notifications
                            <a href="{{ route('admin.bookings.index') }}" class="badge text-body-tertiary badge-pill">
                                <i class="iconoir-plus-circle fs-4"></i>
                            </a>
                        </h5>
                        <div class="ms-0" style="max-height:230px;" data-simplebar>
                            @forelse (($recentNotificationBookings ?? []) as $booking)
                                <a href="{{ route('admin.bookings.show', $booking) }}" class="dropdown-item py-3">
                                    <small class="float-end text-muted ps-2">{{ $booking->created_at?->diffForHumans() }}</small>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 bg-primary-subtle text-primary thumb-md rounded-circle">
                                            <i class="iconoir-calendar fs-4"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-2 text-truncate">
                                            <h6 class="my-0 fw-normal text-dark fs-13">{{ $booking->full_name }}</h6>
                                            <small class="text-muted mb-0">New booking request</small>
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div class="dropdown-item py-3 text-muted">No new notifications</div>
                            @endforelse
                        </div>
                        <a href="{{ route('admin.bookings.index') }}" class="dropdown-item text-center text-dark fs-13 py-2">
                            View All <i class="fi-arrow-right"></i>
                        </a>
                    </div>
                </li>

                <li class="dropdown topbar-item">
                    <a class="nav-link dropdown-toggle arrow-none nav-icon" data-bs-toggle="dropdown" href="#" role="button"
                        aria-haspopup="false" aria-expanded="false">
                        <img src="{{ $avatar }}" alt="{{ $adminName }}" class="thumb-lg rounded-circle">
                    </a>
                    <div class="dropdown-menu dropdown-menu-end py-0">
                        <div class="d-flex align-items-center dropdown-item py-2 bg-secondary-subtle">
                            <div class="flex-shrink-0">
                                <img src="{{ $avatar }}" alt="{{ $adminName }}" class="thumb-md rounded-circle">
                            </div>
                            <div class="flex-grow-1 ms-2 text-truncate align-self-center">
                                <h6 class="my-0 fw-medium text-dark fs-13">{{ $adminName }}</h6>
                                <small class="text-muted mb-0">{{ $adminEmail }}</small>
                            </div>
                        </div>
                        <div class="dropdown-divider mt-0"></div>
                        <small class="text-muted px-2 pb-1 d-block">Account</small>
                        <a class="dropdown-item" href="{{ route('admin.settings.edit') }}">
                            <i class="las la-cog fs-18 me-1 align-text-bottom"></i> Site Settings
                        </a>
                        <a class="dropdown-item" href="{{ route('home') }}" target="_blank" rel="noopener">
                            <i class="las la-external-link-alt fs-18 me-1 align-text-bottom"></i> View Website
                        </a>
                        <div class="dropdown-divider mb-0"></div>
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="las la-power-off fs-18 me-1 align-text-bottom"></i> Logout
                            </button>
                        </form>
                    </div>
                </li>
            </ul>
        </nav>
    </div>
</div>
{{-- Top Bar End --}}
<div class="startbar-overlay d-print-none"></div>
