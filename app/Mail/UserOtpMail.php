<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public int $otp,
        public string $expiresAt,
        public string $name = ''
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your OTP Verification Code',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.user-otp',
            with: [
                'otp' => $this->otp,
                'expiresAt' => $this->expiresAt,
                'name' => $this->name,
            ],
        );
    }
}
