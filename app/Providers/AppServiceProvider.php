<?php

namespace App\Providers;

use App\Models\BookingRequest;
use App\Support\SiteSettings;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(SiteSettings::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer([
            'home',
            'layouts.aventour',
            'partials.aventour.header',
            'partials.aventour.footer',
            'partials.aventour.mobile-menu',
            'partials.aventour.sidebar-popup',
        ], function ($view): void {
            $view->with('siteSettings', app(SiteSettings::class)->all());
        });

        View::composer('admin.partials.topbar', function ($view): void {
            $view->with([
                'newBookingCount' => BookingRequest::query()->where('status', 'new')->count(),
                'recentNotificationBookings' => BookingRequest::query()->latest()->limit(5)->get(),
            ]);
        });
    }
}
