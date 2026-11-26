<!DOCTYPE html>
<html lang="en" dir="ltr" data-startbar="light" data-bs-theme="light">
<head>
    <meta charset="utf-8" />
    <title>@yield('title', 'Dashboard') | Tanzania Safaris Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('logo/tanzania-safaris-and-trekking-logo.png') }}" type="image/png">
    <link rel="apple-touch-icon" href="{{ asset('logo/tanzania-safaris-and-trekking-logo.png') }}">
    <link href="{{ asset('admin/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin/assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin/assets/css/brand-theme.css') }}" rel="stylesheet" type="text/css" />
    <link href="/aventour/assets/css/ajax-loader.css?v={{ filemtime(public_path('aventour/assets/css/ajax-loader.css')) }}" rel="stylesheet" type="text/css" />
    @stack('styles')
</head>
<body data-sidebar-size="default">
    @include('partials.aventour.ajax-loader')
    @include('admin.partials.topbar')
    @include('admin.partials.sidebar')

    <div class="page-wrapper">
        {{-- Page Content --}}
        <div class="page-content">
            <div class="container-xxl">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </div>

            {{-- start footer --}}
            <footer class="footer text-center text-sm-start d-print-none">
                <div class="container-xxl">
                    <div class="row">
                        <div class="col-12">
                            <div class="card mb-0 rounded-bottom-0">
                                <div class="card-body">
                                    <p class="text-muted mb-0">
                                        © {{ now()->year }} Tanzania Safaris and Trekking
                                        <span class="text-muted d-none d-sm-inline-block float-end">
                                            Admin panel for the public website
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
            {{-- end footer --}}
        </div>
        {{-- end page content --}}
    </div>
    {{-- end page-wrapper --}}

    <script src="{{ asset('admin/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('admin/assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/app.js') }}"></script>
    <script src="/aventour/assets/js/ajax-loader.js?v={{ filemtime(public_path('aventour/assets/js/ajax-loader.js')) }}"></script>
    @stack('scripts')
</body>
</html>
