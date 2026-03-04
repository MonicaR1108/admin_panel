<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PublicUserPasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $resetUrl,
        public string $name = ''
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reset your password',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.public-user-password-reset',
            with: [
                'resetUrl' => $this->resetUrl,
                'name' => $this->name,
                'expiresInMinutes' => 30,
            ],
        );
    }
}

