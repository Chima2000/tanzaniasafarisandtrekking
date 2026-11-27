<?php

namespace App\Providers;

use App\Models\BookingRequest;
use App\Support\AdminPanelPath;
use App\Support\JsonLd;
use App\Support\ManagedPageContent;
use App\Support\PageSeo;
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
        $helpers = app_path('helpers.php');

        if (is_file($helpers)) {
            require_once $helpers;
        }

        $this->app->singleton(SiteSettings::class);
        $this->app->singleton(AdminPanelPath::class);
        $this->app->singleton(ManagedPageContent::class);
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

        View::composer(['home', 'layouts.aventour'], function ($view): void {
            if (! $view->offsetExists('pageSeo')) {
                $view->with('pageSeo', PageSeo::forCurrentRoute());
            }

            $view->with('pageJsonLd', JsonLd::forView($view));
        });

        View::composer(['home', 'layouts.aventour', 'partials.cms-preview'], function ($view): void {
            $view->with('cmsPreview', app(ManagedPageContent::class)->isPreviewMode());
        });

        View::composer('admin.partials.topbar', function ($view): void {
            $view->with([
                'newBookingCount' => BookingRequest::query()->where('status', 'new')->count(),
                'recentNotificationBookings' => BookingRequest::query()->latest()->limit(5)->get(),
            ]);
        });
    }
}
