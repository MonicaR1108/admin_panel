<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserOtpMail;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('mail:test {to : Recipient email}', function () {
    $to = (string) $this->argument('to');

    try {
        Mail::raw('Test email from Garage Bill (' . now()->toDateTimeString() . ')', function ($message) use ($to) {
            $message->to($to)->subject('Garage Bill SMTP Test');
        });
    } catch (\Throwable $e) {
        report($e);
        $this->error('Mail send failed: ' . $e->getMessage());

        return 1;
    }

    $this->info('Mail sent (or accepted by SMTP server). Check inbox/spam.');

    return 0;
})->purpose('Send a test email using current SMTP settings');

Artisan::command('otp:test {to : Recipient email}', function () {
    $to = (string) $this->argument('to');
    $otp = random_int(100000, 999999);
    $otpExpiry = now()->addMinutes(10)->format('Y-m-d H:i:s');

    try {
        Mail::to($to)->send(new UserOtpMail($otp, $otpExpiry, 'Test User'));
    } catch (\Throwable $e) {
        report($e);
        $this->error('OTP mail send failed: ' . $e->getMessage());

        return 1;
    }

    $this->info('OTP mail sent. OTP=' . $otp . ' (expires ' . $otpExpiry . ')');

    return 0;
})->purpose('Send a test OTP email (same template as user verification)');
