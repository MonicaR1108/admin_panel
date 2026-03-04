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
            <h2 style="margin:0 0 10px; font-size:18px; color:#111827;">Password reset</h2>

            @if(!empty($name))
                <p style="margin:0 0 14px; color:#374151;">Hi {{ $name }},</p>
            @endif

            <p style="margin:0 0 14px; color:#374151;">
                Your password has been reset by an administrator. Use the credentials below to login.
            </p>

            <div style="border:1px solid rgba(0,0,0,.08); background:#f9fafb; border-radius:12px; padding:14px; margin:16px 0;">
                <div style="color:#6b7280; font-size:12px; margin-bottom:6px;">Username</div>
                <div style="font-weight:700; color:#111827;">{{ $username }}</div>
                <div style="height:10px;"></div>
                <div style="color:#6b7280; font-size:12px; margin-bottom:6px;">Temporary Password</div>
                <div style="font-weight:700; color:#111827;">{{ $newPassword }}</div>
            </div>

            <p style="margin:0; color:#6b7280; font-size:13px;">
                For security, please change your password after logging in.
            </p>
        </div>

        <div style="text-align:center; color:#9ca3af; font-size:12px; margin-top:14px;">
            Garage Bill
        </div>
    </div>
</body>
</html>

