<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactSubmissionMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  array{
     *     full_name: string,
     *     email: string,
     *     phone: ?string,
     *     subject: ?string,
     *     interest: ?string,
     *     package: ?string,
     *     package_name: ?string,
     *     message: ?string,
     *     source: string
     * }  $payload
     */
    public function __construct(public array $payload) {}

    public function envelope(): Envelope
    {
        $subject = $this->payload['subject']
            ?: ($this->payload['package_name']
                ? 'Enquiry: '.$this->payload['package_name']
                : 'Contact message from '.$this->payload['full_name']);

        return new Envelope(
            subject: $subject,
            replyTo: [
                new Address($this->payload['email'], $this->payload['full_name']),
            ],
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.contact-submission',
            with: [
                'payload' => $this->payload,
            ],
        );
    }
}
