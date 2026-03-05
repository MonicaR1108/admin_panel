<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PublicUserVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $verificationUrl,
        public string $expiresAt,
        public string $name = '',
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Verify your account',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.user-verification-link',
            with: [
                'verificationUrl' => $this->verificationUrl,
                'expiresAt' => $this->expiresAt,
                'name' => $this->name,
            ],
        );
    }
}

