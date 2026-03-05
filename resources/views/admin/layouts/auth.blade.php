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

        .auth-logo{
            width: min(320px, 100%);
            height: auto;
            display: block;
        }

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

        .auth-input .password-toggle{
            background: #f3f6fb;
            cursor: pointer;
            user-select: none;
        }

        .auth-input .password-toggle:focus{
            box-shadow: none;
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

        @media (min-width: 992px){
            .auth-brand{
                justify-content: flex-end;
                padding-left: 0;
                padding-right: 10px;
            }
        }

        /* Admin login (scoped) */
        .admin-login-page{
            position: relative;
            overflow: hidden;
            isolation: isolate;
        }

        .admin-login-page::before{
            content: "";
            position: absolute;
            inset: 0;
            background:
                radial-gradient(1200px 700px at 15% 35%, rgba(255,255,255,.75), rgba(255,255,255,.15) 55%, transparent 75%),
                radial-gradient(1100px 700px at 82% 55%, rgba(31,174,45,.22), rgba(31,174,45,.10) 50%, transparent 75%),
                linear-gradient(135deg, #f6fafc, #eef7f1);
            filter: saturate(1.05);
            z-index: -2;
        }

        .admin-login-page::after{
            content: "";
            position: absolute;
            top: -12%;
            right: -18%;
            width: 62%;
            height: 124%;
            background: radial-gradient(circle at 30% 40%, rgba(31,174,45,.40), rgba(31,174,45,.16) 55%, transparent 78%);
            transform: rotate(6deg);
            z-index: -1;
        }

        .admin-login-page .login-shell{
            flex: 1 1 auto;
            display: flex;
            align-items: center;
            padding: 18px 0 8px;
        }

        .admin-login-page .login-grid{
            display: grid;
            grid-template-columns: minmax(0, 1fr) 460px;
            gap: clamp(18px, 4vw, 44px);
            align-items: center;
        }

        .admin-login-page .login-left{
            min-width: 0;
        }

        .admin-login-page .login-hero{
            display: flex;
            align-items: center;
            gap: 18px;
            margin-bottom: 14px;
            flex-wrap: nowrap;
        }

        .admin-login-page .login-brand-logo{
            width: auto;
            height: clamp(154px, 10.5vw, 280px);
            object-fit: contain;
            flex: 0 0 auto;
        }

        .admin-login-page .login-hero-title{
            font-weight: 950;
            letter-spacing: .2px;
            line-height: 1.12;
            font-size: clamp(1.55rem, 2.35vw, 2.55rem);
            margin: 0;
            min-width: 0;
            max-width: 34ch;
            overflow-wrap: anywhere;
            background: linear-gradient(90deg, #2f6f3c, #1f8a5b, #1d6aa1);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .admin-login-page .login-right{
            position: relative;
            padding: 34px 18px;
            border-radius: 22px;
            background: linear-gradient(180deg, rgba(31,174,45,.22), rgba(31,174,45,.10));
            border: 1px solid rgba(255,255,255,.38);
            box-shadow: 0 22px 60px rgba(0,0,0,.18);
            backdrop-filter: blur(8px);
        }

        .admin-login-page .login-card{
            background: rgba(255,255,255,.62);
            border: 1px solid rgba(0,0,0,.10);
            border-radius: 22px;
            box-shadow: 0 18px 48px rgba(0,0,0,.18);
            padding: 18px 18px 20px;
            backdrop-filter: blur(14px);
        }

        .admin-login-page .login-card-top{
            display: flex;
            justify-content: flex-end;
            margin-bottom: 10px;
        }

        .admin-login-page .login-back-btn{
            border-radius: 999px;
            background: rgba(255,255,255,.78);
        }

        .admin-login-page .login-submit{
            border-radius: 14px;
            font-weight: 900;
            letter-spacing: .2px;
            background: linear-gradient(90deg, #0f3d2e, #1b6d52);
            border: 0;
            box-shadow: 0 14px 30px rgba(15,61,46,.26);
            transition: transform .14s ease, box-shadow .18s ease, filter .18s ease;
        }

        .admin-login-page .login-submit:hover{
            transform: translateY(-1px);
            box-shadow: 0 18px 38px rgba(15,61,46,.32);
            filter: brightness(1.02);
        }

        @media (max-width: 991.98px){
            .admin-login-page .login-shell{ padding: 14px 0 8px; }
            .admin-login-page .login-grid{ grid-template-columns: 1fr; }
            .admin-login-page .login-right{ padding: 18px 14px; }
            .admin-login-page .login-hero{ gap: 12px; flex-wrap: wrap; }
            .admin-login-page .login-brand-logo{ height: 140px; }
        }
    </style>
</head>
<body>
    @yield('content')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
