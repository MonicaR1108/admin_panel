@extends('public.layouts.app')

@section('title', 'Garage Bill')

@section('content')
    <style>
        .admin-content .py-3.py-lg-4{
            padding-top: 0 !important;
            padding-bottom: 0 !important;
        }

        .public-home-wrap{
            height: calc(100vh - var(--topbar-height));
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .public-home-card{
            max-width: 520px;
            width: 100%;
            border-radius: 22px;
            border: 1px solid rgba(0,0,0,.06);
            box-shadow: 0 18px 50px rgba(0,0,0,.10);
            background: #fff;
            padding: 22px 18px;
        }

        .public-home-logo{
            width: min(260px, 92%);
            height: auto;
            object-fit: contain;
        }

        .public-home-title{
            font-weight: 900;
            letter-spacing: .4px;
            color: #0f3d2e;
            margin-top: 14px;
        }

        .public-home-login-btn{
            padding: 14px 28px;
            font-weight: 800;
            border-radius: 16px;
            box-shadow: 0 14px 30px rgba(25,135,84,.25);
            transition: transform .14s ease, box-shadow .18s ease, filter .18s ease;
        }

        .public-home-login-btn:hover{
            transform: translateY(-2px);
            box-shadow: 0 18px 42px rgba(25,135,84,.30);
            filter: brightness(1.02);
        }

        .public-home-login-btn:active{
            transform: translateY(0);
        }
    </style>

    <div class="public-home-wrap">
        <div class="public-home-card text-center">
            <img class="public-home-logo" src="{{ asset('assets/logo_netautocare1.png') }}" alt="Garage Bill">

            @if (session()->has('password_setup_user_id'))
                <div class="public-home-title fs-5">Create your password</div>
                <div class="text-muted small mt-1">Your email is verified. Set a password to activate your account.</div>

                <form class="text-start mt-4" method="POST" action="{{ route('public.password.setup') }}" novalidate>
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-success w-100 public-home-login-btn">
                        <i class="bi bi-check2-circle me-2"></i>Set Password & Activate
                    </button>
                </form>
            @else
                <div class="mt-4 text-muted">
                    Welcome to Garage Bill.
                </div>

                <div class="mt-4">
                    <a href="{{ route('login') }}" class="btn btn-outline-success px-4 py-2 fw-semibold rounded-4">
                        Admin Login
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
