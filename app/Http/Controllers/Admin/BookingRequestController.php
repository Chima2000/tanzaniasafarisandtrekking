<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookingRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingRequestController extends Controller
{
    public function index(): View
    {
        return view('admin.bookings.index', [
            'bookings' => BookingRequest::query()->latest()->paginate(15),
        ]);
    }

    public function show(BookingRequest $booking): View
    {
        return view('admin.bookings.show', [
            'booking' => $booking,
        ]);
    }

    public function update(Request $request, BookingRequest $booking): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:new,reviewed,contacted,closed'],
        ]);

        $booking->update($validated);

        return redirect()
            ->route('admin.bookings.show', $booking)
            ->with('success', 'Booking status updated.');
    }
}
