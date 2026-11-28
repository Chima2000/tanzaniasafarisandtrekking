@extends('layouts.admin')

@section('title', 'My Profile')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="mb-1">My Profile</h4>
                <p class="text-muted mb-0">Update your name, email, password, and profile photo for the admin panel.</p>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-xl-7">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Account details</h4>
                    </div>
                    <div class="card-body pt-0">
                        <div class="mb-3">
                            <label class="form-label" for="name">Name</label>
                            <input
                                type="text"
                                class="form-control @error('name') is-invalid @enderror"
                                id="name"
                                name="name"
                                value="{{ old('name', $user->name) }}"
                                maxlength="120"
                                required
                            >
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-0">
                            <label class="form-label" for="email">Email</label>
                            <input
                                type="email"
                                class="form-control @error('email') is-invalid @enderror"
                                id="email"
                                name="email"
                                value="{{ old('email', $user->email) }}"
                                maxlength="255"
                                required
                            >
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-1">Change password</h4>
                        <p class="text-muted mb-0 small">Leave blank to keep your current password.</p>
                    </div>
                    <div class="card-body pt-0">
                        <div class="mb-3">
                            <label class="form-label" for="current_password">Current password</label>
                            <input
                                type="password"
                                class="form-control @error('current_password') is-invalid @enderror"
                                id="current_password"
                                name="current_password"
                                autocomplete="current-password"
                            >
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="password">New password</label>
                            <input
                                type="password"
                                class="form-control @error('password') is-invalid @enderror"
                                id="password"
                                name="password"
                                autocomplete="new-password"
                            >
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-0">
                            <label class="form-label" for="password_confirmation">Confirm new password</label>
                            <input
                                type="password"
                                class="form-control"
                                id="password_confirmation"
                                name="password_confirmation"
                                autocomplete="new-password"
                            >
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-5">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Profile photo</h4>
                    </div>
                    <div class="card-body pt-0">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <img
                                src="{{ $user->avatarUrl() }}"
                                alt="{{ $user->name }}"
                                class="thumb-xl rounded-circle border"
                                style="width: 96px; height: 96px; object-fit: cover;"
                            >
                            <div>
                                <p class="mb-1 fw-medium">{{ $user->name }}</p>
                                <p class="text-muted mb-0 small">{{ $user->email }}</p>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="avatar">Upload new photo</label>
                            <input
                                type="file"
                                class="form-control @error('avatar') is-invalid @enderror"
                                id="avatar"
                                name="avatar"
                                accept="image/jpeg,image/png,image/webp"
                            >
                            <div class="form-text">JPG, PNG, or WebP. Maximum 4 MB.</div>
                            @error('avatar')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        @if ($user->hasCustomAvatar())
                            <div class="form-check mb-0">
                                <input class="form-check-input" type="checkbox" name="remove_avatar" value="1" id="remove_avatar" @checked(old('remove_avatar'))>
                                <label class="form-check-label" for="remove_avatar">Remove current photo</label>
                            </div>
                        @endif
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Save profile</button>
            </div>
        </div>
    </form>
@endsection
