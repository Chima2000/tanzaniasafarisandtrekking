<!DOCTYPE html>
<html lang="en" dir="ltr" data-bs-theme="light">
<head>
    <meta charset="utf-8" />
    <title>@yield('title', 'Admin Login') | Tanzania Safaris Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('logo/tanzania-safaris-and-trekking-logo.png') }}" type="image/png">
    <link rel="apple-touch-icon" href="{{ asset('logo/tanzania-safaris-and-trekking-logo.png') }}">
    <link href="{{ admin_asset('css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ admin_asset('css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ admin_asset('css/app.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ admin_asset('css/brand-theme.css') }}" rel="stylesheet" type="text/css" />
    <link href="/aventour/assets/css/ajax-loader.css?v={{ filemtime(public_path('aventour/assets/css/ajax-loader.css')) }}" rel="stylesheet" type="text/css" />
</head>
<body class="auth-login-page">
    @include('partials.aventour.ajax-loader')
    <div class="auth-login-bg" style="background-image: url('{{ asset('photos/bg-login.jpg') }}');"></div>
    <div class="auth-aside-overlay auth-login-overlay"></div>
    <div class="container-xxl position-relative auth-login-content">
        <div class="row vh-100 d-flex justify-content-center">
            <div class="col-12 align-self-center">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-5 col-xl-4 mx-auto">
                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ admin_asset('libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ admin_asset('js/auth.js') }}"></script>
    <script src="/aventour/assets/js/ajax-loader.js?v={{ filemtime(public_path('aventour/assets/js/ajax-loader.js')) }}"></script>
    @include('partials.whatsapp-button')
</body>
</html>
