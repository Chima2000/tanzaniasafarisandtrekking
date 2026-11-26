<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookNowRequest;
use App\Mail\BookNowRequestMail;
use App\Models\BookingRequest;
use App\Models\NotificationRecipient;
use App\Support\BookNowOptions;
use App\Support\ManagedPageContent;
use App\Support\NotificationRecipients;
use App\Support\TourCatalog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BookNowController extends Controller
{
    public function create(Request $request, TourCatalog $catalog, ManagedPageContent $pages): View
    {
        $managed = $pages->for('book-now');

        $package = null;
        if ($request->filled('package')) {
            $package = $catalog->findPackage((string) $request->query('package'));
        }

        $oldDestinations = collect(old('destinations', $request->input('destinations', [])))
            ->filter()
            ->values()
            ->all();

        if ($oldDestinations === [] && $request->filled('destination')) {
            $oldDestinations = [(string) $request->query('destination')];
        }

        if ($oldDestinations === [] && $package !== null) {
            $matched = $catalog->destinations()
                ->filter(function (array $destination) use ($package): bool {
                    foreach ($destination['needles'] as $needle) {
                        if (str_contains($package['name'], $needle) || str_contains($package['location'], $needle)) {
                            return true;
                        }
                    }

                    return false;
                })
                ->pluck('slug')
                ->values()
                ->all();

            $oldDestinations = $matched;
        }

        $defaultDuration = old('duration_days', $request->query('duration_days'));
        if ($defaultDuration === null && $package !== null && preg_match('/(\d+)/', $package['duration'], $matches) === 1) {
            $defaultDuration = (int) $matches[1];
        }

        return view('pages.book-now', [
            'plannerDestinations' => $catalog->destinations()
                ->map(fn (array $destination): array => [
                    'slug' => $destination['slug'],
                    'name' => $destination['full_name'] ?? $destination['title'],
                    'region' => $destination['region'] ?? 'Tanzania',
                    'summary' => Str::limit(strip_tags((string) ($destination['description'] ?? '')), 72),
                    'image' => $destination['image'],
                ])
                ->values()
                ->all(),
            'plannerBudgets' => BookNowOptions::budgets(),
            'plannerDurations' => BookNowOptions::durations(),
            'plannerAccommodations' => BookNowOptions::accommodations(),
            'plannerInterests' => BookNowOptions::interests(),
            'plannerOldDestinations' => $oldDestinations,
            'plannerDefaultBudget' => old('budget', $request->query('budget', BookNowOptions::defaultBudget())),
            'plannerDefaultDuration' => max(1, (int) ($defaultDuration ?? 8)),
            'plannerDefaultAdults' => max(1, (int) old('adults', $request->query('adults', 2))),
            'plannerDefaultChildren' => max(0, (int) old('children', $request->query('children', 0))),
            'plannerMinDate' => now()->toDateString(),
            'plannerPackage' => $package,
            'plannerPackageSlug' => $package['slug'] ?? old('package', $request->query('package')),
            'page' => $managed['page'],
            'pageContent' => $managed['content'],
            'pageBg' => $managed['page']?->hero_image
                ?: ($package['image']
                    ?? $catalog->packages()->first()['image']
                    ?? '/aventour/assets/images/background/page-title.jpg'),
            'awardBg' => $catalog->safariPackages()->first()['image']
                ?? '/aventour/assets/images/background/award-bg.jpg',
            'current' => 'book-now',
        ]);
    }

    public function store(StoreBookNowRequest $request, NotificationRecipients $notificationRecipients): RedirectResponse|JsonResponse
    {
        $payload = $request->bookingPayload();
        $validated = $request->validated();

        BookingRequest::query()->create([
            'full_name' => $payload['full_name'],
            'email' => $payload['email'],
            'phone' => $payload['phone'],
            'package_slug' => $payload['package'],
            'package_name' => $payload['package_name'],
            'interest' => $validated['interest'] ?? null,
            'budget' => $validated['budget'] ?? null,
            'duration_days' => $validated['duration_days'] ?? null,
            'destinations' => $validated['destinations'] ?? [],
            'adults' => $validated['adults'] ?? 1,
            'children' => $validated['children'] ?? 0,
            'price_per_person' => $payload['price_per_person'] ?? null,
            'estimated_total' => $payload['estimated_total'] ?? null,
            'accommodation' => $validated['accommodation'] ?? null,
            'travel_date' => $validated['travel_date'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'payload' => $payload,
            'status' => 'new',
        ]);

        dispatch(function () use ($notificationRecipients, $payload): void {
            try {
                $notificationRecipients->send(NotificationRecipient::TYPE_BOOKING, new BookNowRequestMail($payload));
            } catch (\Throwable $e) {
                report($e);
            }
        })->afterResponse();

        $message = 'Thank you! Your booking request has been received. We will confirm availability shortly.';

        if ($request->expectsJson()) {
            return response()->json(['ok' => true, 'message' => $message]);
        }

        return redirect()->route('book-now')->with('success', $message);
    }
}
