<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verify your account</title>
</head>
<body style="margin:0; padding:0; background:#f5f6fa; font-family:Arial, Helvetica, sans-serif;">
    <div style="max-width:600px; margin:0 auto; padding:24px;">
        <div style="background:#ffffff; border:1px solid rgba(0,0,0,.08); border-radius:14px; padding:22px;">
            <h2 style="margin:0 0 10px; font-size:18px; color:#111827;">Verify your account</h2>
            @if(!empty($name))
                <p style="margin:0 0 14px; color:#374151;">Hi {{ $name }},</p>
            @endif
            <p style="margin:0 0 14px; color:#374151;">
                Click the button below to verify your account. This link will expire at {{ $expiresAt }}.
            </p>

            <p style="margin:0 0 14px;">
                <a href="{{ $verificationUrl }}"
                    style="display:inline-block; padding:12px 18px; border-radius:12px; background:#198754; color:#ffffff; text-decoration:none; font-weight:700;">
                    Verify Account
                </a>
            </p>

            <p style="margin:0 0 14px; color:#6b7280; font-size:13px;">
                If the button doesn't work, copy and paste this link into your browser:
                <br>
                <span style="word-break:break-all;">{{ $verificationUrl }}</span>
            </p>

            <p style="margin:12px 0 0; color:#6b7280; font-size:13px;">
                If you did not request this, you can ignore this email.
            </p>
        </div>
        <div style="text-align:center; color:#9ca3af; font-size:12px; margin-top:14px;">
            Garage Bill
        </div>
    </div>
</body>
</html>
