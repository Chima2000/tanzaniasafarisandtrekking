@extends('layouts.admin-auth')

@section('title', 'Admin Login')

@section('content')
    <div class="card">
        <div class="card-body p-0 auth-header-box rounded-top">
            <div class="text-center p-4">
                <a href="{{ route('home') }}" class="logo logo-admin auth-brand-logo">
                    <img src="{{ asset('logo/tanzaniasafaritrekkinglogowhite.png') }}" alt="Tanzania Safaris and Trekking">
                </a>
                <h4 class="mt-3 mb-1 fw-semibold text-white fs-18">Admin Panel</h4>
                <p class="text-white-50 fw-medium mb-0">Sign in to manage the website.</p>
            </div>
        </div>
        <div class="card-body pt-0">
            <form class="my-4" method="POST" action="{{ route('admin.login.store') }}">
                @csrf
                <div class="form-group mb-2">
                    <label class="form-label" for="email">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="Enter email" required autofocus>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="password" placeholder="Enter password" required>
                        <span class="input-group-text" data-password-toggle="password" role="button" aria-label="Show password">
                            <i class="far fa-eye"></i>
                        </span>
                    </div>
                    @error('password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group row mt-3">
                    <div class="col-sm-6">
                        <div class="form-check form-switch form-switch-success">
                            <input class="form-check-input" type="checkbox" id="remember" name="remember" value="1" @checked(old('remember'))>
                            <label class="form-check-label" for="remember">Remember me</label>
                        </div>
                    </div>
                </div>

                <div class="form-group mb-0 row">
                    <div class="col-12">
                        <div class="d-grid mt-3">
                            <button class="btn btn-primary" type="submit">
                                Log In <i class="fas fa-sign-in-alt ms-1"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
            <div class="text-center mb-2">
                <p class="text-muted mb-0">
                    <a href="{{ route('home') }}" class="text-primary">Back to website</a>
                </p>
            </div>
        </div>
    </div>
@endsection
