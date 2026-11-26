<?php

namespace App\Support;

use App\Models\NotificationRecipient;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;

class NotificationRecipients
{
    /**
     * Resolve active recipient emails for a notification type.
     * Falls back to mail.book_now_recipient when none are configured.
     *
     * @return list<string>
     */
    public function emailsFor(string $type): array
    {
        $emails = NotificationRecipient::query()
            ->active()
            ->forType($type)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->pluck('email')
            ->map(fn (string $email): string => strtolower(trim($email)))
            ->filter()
            ->unique()
            ->values()
            ->all();

        if ($emails !== []) {
            return $emails;
        }

        $fallback = strtolower(trim((string) config('mail.book_now_recipient', '')));

        return $fallback !== '' ? [$fallback] : [];
    }

    /**
     * Send a mailable to every recipient configured for the type.
     *
     * @return list<string> Recipients that were targeted
     */
    public function send(string $type, Mailable $mailable): array
    {
        $recipients = $this->emailsFor($type);

        if ($recipients === []) {
            return [];
        }

        Mail::to($recipients)->send($mailable);

        return $recipients;
    }
}
