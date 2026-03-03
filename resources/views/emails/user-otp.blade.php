<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>OTP Verification</title>
</head>
<body style="margin:0; padding:0; background:#f5f6fa; font-family:Arial, Helvetica, sans-serif;">
    <div style="max-width:600px; margin:0 auto; padding:24px;">
        <div style="background:#ffffff; border:1px solid rgba(0,0,0,.08); border-radius:14px; padding:22px;">
            <h2 style="margin:0 0 10px; font-size:18px; color:#111827;">OTP Verification</h2>
            @if(!empty($name))
                <p style="margin:0 0 14px; color:#374151;">Hi {{ $name }},</p>
            @endif
            <p style="margin:0 0 12px; color:#374151;">
                Your OTP code is:
            </p>
            <div style="display:inline-block; font-size:26px; letter-spacing:4px; font-weight:700; padding:10px 14px; border-radius:12px; background:#eef7f1; border:1px solid rgba(31,174,45,.25); color:#166534;">
                {{ $otp }}
            </div>
            <p style="margin:14px 0 0; color:#6b7280; font-size:13px;">
                This OTP will expire at {{ $expiresAt }}.
            </p>
            <p style="margin:12px 0 0; color:#6b7280; font-size:13px;">
                If you did not request this code, you can ignore this email.
            </p>
        </div>
        <div style="text-align:center; color:#9ca3af; font-size:12px; margin-top:14px;">
            Garage Bill
        </div>
    </div>
</body>
</html>
