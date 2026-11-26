<?php

use App\Http\Controllers\Admin\Auth\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\BlogPostController as AdminBlogPostController;
use App\Http\Controllers\Admin\BookingRequestController as AdminBookingRequestController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DestinationController as AdminDestinationController;
use App\Http\Controllers\Admin\FaqController as AdminFaqController;
use App\Http\Controllers\Admin\PackageController as AdminPackageController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\SiteSettingController as AdminSiteSettingController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BookNowController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::get('/packages', [PackageController::class, 'index'])->name('packages.index');
Route::get('/packages/{slug}', [PackageController::class, 'show'])->name('packages.show');

Route::get('/destinations', [DestinationController::class, 'index'])->name('destinations.index');
Route::get('/destinations/{slug}', [DestinationController::class, 'show'])->name('destinations.show');

Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/blog', [BlogController::class, 'index'])->name('blog');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/faq', [PageController::class, 'faq'])->name('faq');

Route::get('/book-now', [BookNowController::class, 'create'])->name('book-now');
Route::post('/book-now', [BookNowController::class, 'store'])->name('book-now.store');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminLoginController::class, 'create'])->name('login');
        Route::post('/login', [AdminLoginController::class, 'store'])->name('login.store');
    });

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::post('/logout', [AdminLoginController::class, 'destroy'])->name('logout');
        Route::get('/', AdminDashboardController::class)->name('dashboard');

        Route::get('/settings', [AdminSiteSettingController::class, 'edit'])->name('settings.edit');
        Route::put('/settings', [AdminSiteSettingController::class, 'update'])->name('settings.update');

        Route::get('/pages', [AdminPageController::class, 'index'])->name('pages.index');
        Route::get('/pages/{page}/edit', [AdminPageController::class, 'edit'])->name('pages.edit');
        Route::put('/pages/{page}', [AdminPageController::class, 'update'])->name('pages.update');

        Route::resource('faqs', AdminFaqController::class)->except(['show']);

        Route::post('/blog-posts/sync', [AdminBlogPostController::class, 'sync'])->name('blog-posts.sync');
        Route::resource('blog-posts', AdminBlogPostController::class)
            ->except(['show'])
            ->parameters(['blog-posts' => 'blog_post']);

        Route::post('/packages/sync', [AdminPackageController::class, 'sync'])->name('packages.sync');
        Route::resource('packages', AdminPackageController::class);

        Route::post('/destinations/sync', [AdminDestinationController::class, 'sync'])->name('destinations.sync');
        Route::resource('destinations', AdminDestinationController::class);

        Route::get('/bookings', [AdminBookingRequestController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/{booking}', [AdminBookingRequestController::class, 'show'])->name('bookings.show');
        Route::put('/bookings/{booking}', [AdminBookingRequestController::class, 'update'])->name('bookings.update');
    });
});
