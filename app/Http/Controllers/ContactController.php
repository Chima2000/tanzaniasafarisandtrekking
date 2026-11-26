<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactSubmissionRequest;
use App\Mail\ContactSubmissionMail;
use App\Models\ContactSubmission;
use App\Models\NotificationRecipient;
use App\Support\NotificationRecipients;
use App\Support\TourCatalog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class ContactController extends Controller
{
    public function store(
        StoreContactSubmissionRequest $request,
        TourCatalog $catalog,
        NotificationRecipients $notificationRecipients,
    ): RedirectResponse|JsonResponse {
        $validated = $request->validated();
        $packageSlug = $validated['package'] ?? null;
        $package = $packageSlug ? $catalog->findPackage($packageSlug) : null;

        $payload = [
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'subject' => $validated['subject'] ?? null,
            'interest' => $validated['interest'] ?? null,
            'package' => $packageSlug,
            'package_name' => $package['name'] ?? null,
            'message' => $validated['message'] ?? null,
            'source' => $validated['source'] ?? 'contact',
        ];

        ContactSubmission::query()->create([
            'full_name' => $payload['full_name'],
            'email' => $payload['email'],
            'phone' => $payload['phone'],
            'subject' => $payload['subject'],
            'interest' => $payload['interest'],
            'package_slug' => $payload['package'],
            'package_name' => $payload['package_name'],
            'message' => $payload['message'],
            'source' => $payload['source'],
            'status' => 'new',
            'payload' => $payload,
        ]);

        dispatch(function () use ($notificationRecipients, $payload): void {
            try {
                $notificationRecipients->send(NotificationRecipient::TYPE_CONTACT, new ContactSubmissionMail($payload));
            } catch (\Throwable $e) {
                report($e);
            }
        })->afterResponse();

        $message = 'Thank you! Your message has been received. We will get back to you shortly.';

        if ($request->expectsJson()) {
            return response()->json(['ok' => true, 'message' => $message]);
        }

        return redirect()
            ->back()
            ->with('success', $message);
    }
}
