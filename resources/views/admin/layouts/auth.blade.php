<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin Login')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body{
            min-height: 100vh;
            background: radial-gradient(1200px 600px at 10% 30%, rgba(31,174,45,.14), transparent 60%),
                        radial-gradient(900px 500px at 80% 70%, rgba(229,231,0,.10), transparent 55%),
                        linear-gradient(135deg, #f7fafc, #eef7f1);
        }

        .auth-page{
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .auth-brand{
            display: flex;
            align-items: center;
            gap: 16px;
            padding-left: 18px; /* avoids left-edge clipping */
            overflow: visible;
        }

        .auth-logo{ width: 104px; height: 104px; }

        .auth-brand-name{
            font-weight: 950;
            letter-spacing: .4px;
            line-height: 1.12; /* prevent glyph clipping */
            font-size: clamp(2.2rem, 4vw, 3rem);
            color: #72b92b; /* solid color avoids browser clipping with gradient text */
            display: inline-block;
            padding-left: 6px; /* prevents first-letter clipping with gradient text */
            padding-right: 2px;
        }

        .auth-brand-sub{
            margin-top: 4px;
            font-size: 1.05rem;
            color: rgba(0,0,0,.55);
            font-weight: 600;
            letter-spacing: .2px;
        }

        .auth-card{
            border: 0;
            border-radius: 18px;
            box-shadow: 0 16px 40px rgba(0,0,0,.12);
        }

        .auth-input .input-group-text{
            background: #f3f6fb;
            border-right: 0;
        }

        .auth-input .form-control{
            border-left: 0;
            background: #f3f6fb;
        }

        .auth-input .form-control:focus{
            box-shadow: none;
            background: #fff;
        }

        .auth-input .input-group-text i{
            color: rgba(0,0,0,.45);
        }

        .auth-footer{
            font-size: .85rem;
            color: rgba(0,0,0,.45);
        }

        @media (max-width: 991.98px){
            .auth-brand{ justify-content: center; text-align: center; }
        }
    </style>
</head>
<body>
    @yield('content')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
