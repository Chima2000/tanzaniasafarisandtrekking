<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\BookingRequest;
use App\Support\TourCatalog;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(TourCatalog $catalog): View
    {
        $packages = $catalog->packages();

        return view('admin.dashboard', [
            'packageCount' => $packages->count(),
            'destinationCount' => $catalog->destinations()->count(),
            'blogPostCount' => BlogPost::query()->count(),
            'publishedBlogPostCount' => BlogPost::query()->published()->count(),
            'bookingCount' => BookingRequest::query()->count(),
            'newBookingCount' => BookingRequest::query()->where('status', 'new')->count(),
            'recentBookings' => BookingRequest::query()->latest()->limit(5)->get(),
            'recentPosts' => BlogPost::query()->latest()->limit(5)->get(),
            'featuredPackages' => $packages->take(5)->values(),
        ]);
    }
}
