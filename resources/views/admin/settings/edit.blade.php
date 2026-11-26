@extends('layouts.admin')

@section('title', 'Site Settings')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="mb-1">Site Settings</h4>
                <p class="text-muted mb-0">These values power contact details and messaging on the public website.</p>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.settings.update') }}">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header"><h4 class="card-title mb-0">Brand & contact</h4></div>
                    <div class="card-body pt-0">
                        <div class="mb-3">
                            <label class="form-label" for="company_name">Company name</label>
                            <input type="text" class="form-control" id="company_name" name="company_name" value="{{ old('company_name', $settings['company_name']) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="tagline">Tagline</label>
                            <input type="text" class="form-control" id="tagline" name="tagline" value="{{ old('tagline', $settings['tagline']) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="phone">Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $settings['phone']) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $settings['email']) }}">
                        </div>
                        <div class="mb-0">
                            <label class="form-label" for="address">Address</label>
                            <input type="text" class="form-control" id="address" name="address" value="{{ old('address', $settings['address']) }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header"><h4 class="card-title mb-0">Homepage messaging</h4></div>
                    <div class="card-body pt-0">
                        <div class="mb-3">
                            <label class="form-label" for="hero_kicker">Hero kicker</label>
                            <input type="text" class="form-control" id="hero_kicker" name="hero_kicker" value="{{ old('hero_kicker', $settings['hero_kicker']) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="hero_title">Hero title</label>
                            <input type="text" class="form-control" id="hero_title" name="hero_title" value="{{ old('hero_title', $settings['hero_title']) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="hero_subtitle">Hero subtitle</label>
                            <textarea class="form-control" id="hero_subtitle" name="hero_subtitle" rows="3">{{ old('hero_subtitle', $settings['hero_subtitle']) }}</textarea>
                        </div>
                        <div class="mb-0">
                            <label class="form-label" for="footer_about">Footer about</label>
                            <textarea class="form-control" id="footer_about" name="footer_about" rows="3">{{ old('footer_about', $settings['footer_about']) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card">
                    <div class="card-header"><h4 class="card-title mb-0">Social links</h4></div>
                    <div class="card-body pt-0">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="facebook_url">Facebook URL</label>
                                <input type="url" class="form-control" id="facebook_url" name="facebook_url" value="{{ old('facebook_url', $settings['facebook_url']) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="instagram_url">Instagram URL</label>
                                <input type="url" class="form-control" id="instagram_url" name="instagram_url" value="{{ old('instagram_url', $settings['instagram_url']) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="twitter_url">X / Twitter URL</label>
                                <input type="url" class="form-control" id="twitter_url" name="twitter_url" value="{{ old('twitter_url', $settings['twitter_url']) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="whatsapp_url">WhatsApp URL</label>
                                <input type="url" class="form-control" id="whatsapp_url" name="whatsapp_url" value="{{ old('whatsapp_url', $settings['whatsapp_url']) }}">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Save settings</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
