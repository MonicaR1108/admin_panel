<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminUserPasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $username,
        public string $newPassword,
        public string $name = ''
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your password has been reset',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin-user-password-reset',
            with: [
                'username' => $this->username,
                'newPassword' => $this->newPassword,
                'name' => $this->name,
            ],
        );
    }
}

