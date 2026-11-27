<?php

use App\Http\Controllers\Admin\Auth\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\BlogPostController as AdminBlogPostController;
use App\Http\Controllers\Admin\BookingRequestController as AdminBookingRequestController;
use App\Http\Controllers\Admin\ContactSubmissionController as AdminContactSubmissionController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DestinationController as AdminDestinationController;
use App\Http\Controllers\Admin\FaqController as AdminFaqController;
use App\Http\Controllers\Admin\MediaLibraryController as AdminMediaLibraryController;
use App\Http\Controllers\Admin\MediaUploadController as AdminMediaUploadController;
use App\Http\Controllers\Admin\NotificationRecipientController as AdminNotificationRecipientController;
use App\Http\Controllers\Admin\PackageController as AdminPackageController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\SitemapController as AdminSitemapController;
use App\Http\Controllers\Admin\SiteSettingController as AdminSiteSettingController;
use App\Http\Controllers\Admin\WhatsappWidgetController as AdminWhatsappWidgetController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BookNowController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SeoController;
use Illuminate\Support\Facades\Route;

Route::get('robots.txt', [SeoController::class, 'robots'])->name('robots_txt');
Route::get('sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');

Route::get('/', HomeController::class)->name('home');

Route::get('/search', [SearchController::class, 'index'])->name('search.index');
Route::get('/search/suggest', [SearchController::class, 'suggest'])->name('search.suggest');

Route::get('/packages', [PackageController::class, 'index'])->name('packages.index');
Route::get('/safari-packages', [PackageController::class, 'safari'])->name('packages.safari');
Route::get('/kilimanjaro-treks', [PackageController::class, 'trekking'])->name('packages.trekking');
Route::get('/day-trips', [PackageController::class, 'dayTrips'])->name('packages.day-trips');
Route::get('/cultural-experiences', [PackageController::class, 'cultural'])->name('packages.cultural');
Route::get('/packages/{slug}', [PackageController::class, 'show'])->name('packages.show');

Route::get('/destinations', [DestinationController::class, 'index'])->name('destinations.index');
Route::get('/destinations/{slug}', [DestinationController::class, 'show'])->name('destinations.show');

Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/blog', [BlogController::class, 'index'])->name('blog');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('contact.store');
Route::get('/faq', [PageController::class, 'faq'])->name('faq');

Route::get('/book-now', [BookNowController::class, 'create'])->name('book-now');
Route::post('/book-now', [BookNowController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('book-now.store');

$adminPath = admin_path();

Route::prefix($adminPath)->name('admin.')->middleware('noindex')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminLoginController::class, 'create'])->name('login');
        Route::post('/login', [AdminLoginController::class, 'store'])->name('login.store');
    });

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::post('/logout', [AdminLoginController::class, 'destroy'])->name('logout');
        Route::get('/', AdminDashboardController::class)->name('dashboard');

        Route::get('/settings', [AdminSiteSettingController::class, 'edit'])->name('settings.edit');
        Route::put('/settings', [AdminSiteSettingController::class, 'update'])->name('settings.update');
        Route::put('/settings/admin-path', [AdminSiteSettingController::class, 'updateAdminPath'])->name('settings.admin-path.update');

        Route::get('/notifications', [AdminNotificationRecipientController::class, 'index'])->name('notifications.index');
        Route::post('/notifications', [AdminNotificationRecipientController::class, 'store'])->name('notifications.store');
        Route::post('/notifications/{recipient}', [AdminNotificationRecipientController::class, 'update'])->name('notifications.update');
        Route::post('/notifications/{recipient}/delete', [AdminNotificationRecipientController::class, 'destroy'])->name('notifications.destroy');

        Route::get('/sitemap', [AdminSitemapController::class, 'index'])->name('sitemap.index');
        Route::post('/sitemap/generate', [AdminSitemapController::class, 'generate'])->name('sitemap.generate');
        Route::post('/sitemap/regenerate', [AdminSitemapController::class, 'regenerate'])->name('sitemap.regenerate');

        Route::get('/whatsapp', [AdminWhatsappWidgetController::class, 'edit'])->name('whatsapp.edit');
        Route::post('/whatsapp', [AdminWhatsappWidgetController::class, 'update'])->name('whatsapp.update');
        Route::post('/whatsapp/remove-number', [AdminWhatsappWidgetController::class, 'destroy'])->name('whatsapp.destroy');
        Route::post('/whatsapp/contacts', [AdminWhatsappWidgetController::class, 'storeContact'])->name('whatsapp.contacts.store');
        Route::post('/whatsapp/contacts/{contact}', [AdminWhatsappWidgetController::class, 'updateContact'])->name('whatsapp.contacts.update');
        Route::post('/whatsapp/contacts/{contact}/delete', [AdminWhatsappWidgetController::class, 'destroyContact'])->name('whatsapp.contacts.destroy');

        Route::get('/pages', [AdminPageController::class, 'index'])->name('pages.index');
        Route::get('/pages/{page}/edit', [AdminPageController::class, 'edit'])->name('pages.edit');
        Route::put('/pages/{page}', [AdminPageController::class, 'update'])->name('pages.update');

        Route::prefix('media')->name('media.')->group(function () {
            Route::get('browse', [AdminMediaLibraryController::class, 'browse'])->name('browse');
            Route::get('/', [AdminMediaLibraryController::class, 'index'])->name('index');
            Route::post('scan', [AdminMediaLibraryController::class, 'scan'])->name('scan');
            Route::post('upload', [AdminMediaUploadController::class, 'store'])->name('upload');
            Route::post('/', [AdminMediaLibraryController::class, 'store'])->name('store');
            Route::delete('bulk', [AdminMediaLibraryController::class, 'bulkDestroy'])->name('bulk-destroy');
            Route::put('{mediaItem}', [AdminMediaLibraryController::class, 'update'])->name('update');
            Route::delete('{mediaItem}', [AdminMediaLibraryController::class, 'destroy'])->name('destroy');
        });

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

        Route::get('/contacts', [AdminContactSubmissionController::class, 'index'])->name('contacts.index');
        Route::get('/contacts/{contact}', [AdminContactSubmissionController::class, 'show'])->name('contacts.show');
        Route::put('/contacts/{contact}', [AdminContactSubmissionController::class, 'update'])->name('contacts.update');
    });
});
