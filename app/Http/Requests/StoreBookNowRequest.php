<?php

namespace App\Http\Requests;

use App\Support\BookNowOptions;
use App\Support\TourCatalog;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreBookNowRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $destinationSlugs = app(TourCatalog::class)->destinations()->pluck('slug')->all();
        $packageSlugs = app(TourCatalog::class)->packages()->pluck('slug')->all();

        return [
            'full_name' => ['required', 'string', 'max:200'],
            'email' => ['required', 'email', 'max:254'],
            'phone' => ['nullable', 'string', 'max:40'],
            'interest' => ['nullable', 'string', Rule::in(BookNowOptions::interests())],
            'budget' => ['required', 'string', Rule::in(BookNowOptions::budgets())],
            'duration_days' => ['required', 'integer', 'min:1', 'max:60'],
            'destinations' => ['required', 'array', 'min:1'],
            'destinations.*' => ['string', Rule::in($destinationSlugs)],
            'adults' => ['required', 'integer', 'min:1', 'max:40'],
            'children' => ['nullable', 'integer', 'min:0', 'max:40'],
            'accommodation' => ['required', 'string', Rule::in(BookNowOptions::accommodationValues())],
            'travel_date' => ['required', 'date', 'after_or_equal:today'],
            'notes' => ['nullable', 'string', 'max:5000'],
            'package' => ['nullable', 'string', Rule::in($packageSlugs)],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'full_name.required' => 'Full name is required.',
            'email.required' => 'Email is required.',
            'budget.required' => 'Please choose a budget range.',
            'duration_days.required' => 'Please choose how many days you want to travel.',
            'destinations.required' => 'Please select at least one destination.',
            'destinations.min' => 'Please select at least one destination.',
            'adults.required' => 'Please tell us how many adults are travelling.',
            'accommodation.required' => 'Please choose an accommodation style.',
            'travel_date.required' => 'Please choose your travel start date.',
            'travel_date.after_or_equal' => 'Travel date must be today or a future date.',
        ];
    }

    /**
     * @return array{
     *     full_name: string,
     *     email: string,
     *     phone: ?string,
     *     package: ?string,
     *     package_name: ?string,
     *     lines: list<string>
     * }
     */
    public function bookingPayload(): array
    {
        $validated = $this->validated();
        $catalog = app(TourCatalog::class);
        $adults = (int) $validated['adults'];
        $children = (int) ($validated['children'] ?? 0);
        $destinations = array_values($validated['destinations']);
        $labels = $catalog->destinations()
            ->whereIn('slug', $destinations)
            ->map(fn (array $destination): string => $destination['full_name'] ?? $destination['title'])
            ->values()
            ->all();

        $packageSlug = $validated['package'] ?? null;
        $package = $packageSlug ? $catalog->findPackage($packageSlug) : null;
        $travelDate = (string) $validated['travel_date'];
        $notes = trim((string) ($validated['notes'] ?? ''));
        $phone = trim((string) ($validated['phone'] ?? ''));

        $travellerParts = [$adults.' '.Str::plural('adult', $adults)];
        if ($children > 0) {
            $travellerParts[] = $children.' '.Str::plural('child', $children);
        }

        $lines = [
            'Travellers: '.implode(', ', $travellerParts),
            'Duration: '.$validated['duration_days'].' days',
            'Destinations: '.implode(', ', $labels),
            'Budget: '.$validated['budget'],
            'Stays: '.$validated['accommodation'],
            'Travel date: '.$travelDate,
        ];

        if ($package !== null) {
            array_unshift($lines, 'Package: '.$package['name']);
        }

        if (! empty($validated['interest'])) {
            $lines[] = 'Focus: '.$validated['interest'];
        }
        if ($phone !== '') {
            $lines[] = 'Phone: '.$phone;
        }
        if ($notes !== '') {
            $lines[] = '';
            $lines[] = $notes;
        }

        return [
            'full_name' => trim($validated['full_name']),
            'email' => trim($validated['email']),
            'phone' => $phone !== '' ? $phone : null,
            'package' => $packageSlug,
            'package_name' => $package['name'] ?? null,
            'lines' => $lines,
        ];
    }

    protected function prepareForValidation(): void
    {
        $destinations = array_values(array_filter(
            (array) $this->input('destinations', []),
            fn (mixed $slug): bool => is_string($slug) && $slug !== ''
        ));

        $this->merge([
            'adults' => $this->filled('adults') ? max(0, (int) $this->input('adults')) : null,
            'children' => max(0, (int) $this->input('children', 0)),
            'destinations' => $destinations,
            'duration_days' => $this->filled('duration_days') ? (int) $this->input('duration_days') : null,
            'package' => $this->filled('package') ? (string) $this->input('package') : null,
            'notes' => trim((string) $this->input('notes', '')) ?: null,
            'phone' => trim((string) $this->input('phone', '')) ?: null,
        ]);
    }
}
