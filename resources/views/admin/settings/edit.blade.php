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
                            @include('admin.partials.rich-text-field', [
                                'id' => 'hero_subtitle',
                                'name' => 'hero_subtitle',
                                'value' => old('hero_subtitle', $settings['hero_subtitle']),
                                'rows' => 3,
                                'oldKey' => 'hero_subtitle',
                            ])
                        </div>
                        <div class="mb-0">
                            <label class="form-label" for="footer_about">Footer about</label>
                            @include('admin.partials.rich-text-field', [
                                'id' => 'footer_about',
                                'name' => 'footer_about',
                                'value' => old('footer_about', $settings['footer_about']),
                                'rows' => 3,
                                'oldKey' => 'footer_about',
                            ])
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
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card">
                    <div class="card-header"><h4 class="card-title mb-0">Default SEO & analytics</h4></div>
                    <div class="card-body pt-0">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="default_meta_title">Default meta title</label>
                                <input type="text" class="form-control" id="default_meta_title" name="default_meta_title" value="{{ old('default_meta_title', $settings['default_meta_title'] ?? '') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="default_og_image">Default Open Graph image</label>
                                <input type="text" class="form-control" id="default_og_image" name="default_og_image" value="{{ old('default_og_image', $settings['default_og_image'] ?? '') }}" placeholder="/logo/...">
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label" for="default_meta_description">Default meta description</label>
                                <textarea class="form-control" id="default_meta_description" name="default_meta_description" rows="3">{{ old('default_meta_description', $settings['default_meta_description'] ?? '') }}</textarea>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label" for="default_meta_keywords">Default meta keywords</label>
                                <input type="text" class="form-control" id="default_meta_keywords" name="default_meta_keywords" value="{{ old('default_meta_keywords', $settings['default_meta_keywords'] ?? '') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="google_tag_manager_id">Google Tag Manager ID</label>
                                <input type="text" class="form-control" id="google_tag_manager_id" name="google_tag_manager_id" value="{{ old('google_tag_manager_id', $settings['google_tag_manager_id'] ?? '') }}" placeholder="GTM-XXXXXXX">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="google_analytics_id">Google Analytics 4 ID</label>
                                <input type="text" class="form-control" id="google_analytics_id" name="google_analytics_id" value="{{ old('google_analytics_id', $settings['google_analytics_id'] ?? '') }}" placeholder="G-XXXXXXXXXX">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Save settings</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <form method="POST" action="{{ route('admin.settings.admin-path.update') }}" class="mt-1" onsubmit="return confirm('Change the admin login path now? Bookmark the new URL after saving.');">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-12">
                <div class="card border-warning">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Admin login path</h4>
                    </div>
                    <div class="card-body pt-0">
                        <p class="text-muted">
                            Change this anytime. Common names like <code>admin</code> or <code>login</code> are blocked and show a 404.
                            After saving, use the new URL immediately — the old path stops working.
                        </p>

                        <div class="mb-3">
                            <label class="form-label" for="admin_panel_path">Path segment</label>
                            <div class="input-group">
                                <span class="input-group-text">{{ url('/') }}/</span>
                                <input
                                    type="text"
                                    class="form-control @error('admin_panel_path') is-invalid @enderror"
                                    id="admin_panel_path"
                                    name="admin_panel_path"
                                    value="{{ old('admin_panel_path', $adminPanelPath) }}"
                                    pattern="[a-z0-9]+(?:-[a-z0-9]+)*"
                                    minlength="6"
                                    maxlength="64"
                                    required
                                    autocomplete="off"
                                    spellcheck="false"
                                >
                                <span class="input-group-text">/login</span>
                                @error('admin_panel_path')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-text">Lowercase letters, numbers, and hyphens only (6–64 characters).</div>
                        </div>

                        <div class="alert alert-light border mb-3">
                            <div class="fw-semibold mb-1">Current login URL</div>
                            <code class="user-select-all">{{ session('admin_login_url', $adminLoginUrl) }}</code>
                        </div>

                        <button type="submit" class="btn btn-warning">Update login path</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
