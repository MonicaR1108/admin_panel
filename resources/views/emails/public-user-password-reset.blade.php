<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Password Reset</title>
</head>
<body style="margin:0; padding:0; background:#f5f6fa; font-family:Arial, Helvetica, sans-serif;">
    <div style="max-width:640px; margin:0 auto; padding:24px;">
        <div style="background:#ffffff; border:1px solid rgba(0,0,0,.08); border-radius:14px; padding:22px;">
            <h2 style="margin:0 0 10px; font-size:18px; color:#111827;">Reset your password</h2>

            @if(!empty($name))
                <p style="margin:0 0 14px; color:#374151;">Hi {{ $name }},</p>
            @endif

            <p style="margin:0 0 14px; color:#374151;">
                We received a request to reset your password. Click the button below to set a new password.
                This link expires in {{ $expiresInMinutes }} minutes.
            </p>

            <p style="margin:18px 0;">
                <a href="{{ $resetUrl }}" style="display:inline-block; padding:12px 16px; background:#198754; color:#fff; border-radius:10px; text-decoration:none; font-weight:700;">
                    Reset Password
                </a>
            </p>

            <p style="margin:0; color:#6b7280; font-size:13px;">
                If you didn’t request a password reset, you can ignore this email.
            </p>
        </div>

        <div style="text-align:center; color:#9ca3af; font-size:12px; margin-top:14px;">
            Garage Bill
        </div>
    </div>
</body>
</html>

