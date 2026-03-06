<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MasterItemController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\UserDetailsController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\PublicPasswordSetupController;
use App\Http\Controllers\PublicPasswordResetController;
use App\Http\Controllers\PublicUserVerificationController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/verify-user/{user}/{hash}', [PublicUserVerificationController::class, 'verify'])
    ->middleware(['throttle:6,1'])
    ->name('public.user.verify');

Route::middleware('track.public')->group(function () {
    Route::get('/', [PublicController::class, 'home'])->name('public.home');
    Route::post('/set-name', [PublicController::class, 'setName'])->name('public.set-name');
    Route::post('/create-password', [PublicPasswordSetupController::class, 'store'])->name('public.password.setup');

    Route::get('/forgot-password', [PublicPasswordResetController::class, 'requestForm'])->name('public.password.request');
    Route::post('/forgot-password', [PublicPasswordResetController::class, 'sendResetLink'])->name('public.password.email');
    Route::get('/reset-password/{token}', [PublicPasswordResetController::class, 'resetForm'])->name('public.password.reset.form');
    Route::post('/reset-password', [PublicPasswordResetController::class, 'resetPassword'])->name('public.password.reset');
});

Route::prefix('admin')->group(function () {
    Route::get('/', function () {
        return Auth::guard('admin')->check()
            ? redirect()->route('dashboard')
            : redirect()->route('login');
    });

    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AuthController::class, 'create'])->name('login');
        Route::post('/login', [AuthController::class, 'store'])->name('admin.login');
    });

    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');

        Route::get('/master-items', [MasterItemController::class, 'index'])->name('admin.master-items.index');
        Route::post('/master-items', [MasterItemController::class, 'store'])->name('admin.master-items.store');
        Route::get('/master-items/{token}', [MasterItemController::class, 'show'])
            ->where('token', '[A-Za-z0-9\\-_]+')
            ->name('admin.master-items.show');
        Route::get('/master-items/{token}/edit', [MasterItemController::class, 'edit'])
            ->where('token', '[A-Za-z0-9\\-_]+')
            ->name('admin.master-items.edit');
        Route::put('/master-items/{token}', [MasterItemController::class, 'update'])
            ->where('token', '[A-Za-z0-9\\-_]+')
            ->name('admin.master-items.update');
        Route::put('/master-items/{token}/status', [MasterItemController::class, 'updateStatus'])
            ->where('token', '[A-Za-z0-9\\-_]+')
            ->name('admin.master-items.status');
        Route::delete('/master-items/{token}', [MasterItemController::class, 'destroy'])
            ->where('token', '[A-Za-z0-9\\-_]+')
            ->name('admin.master-items.destroy');

        Route::get('/user-details', [UserDetailsController::class, 'index'])->name('admin.user-details.index');
        Route::get('/user-details/create', [UserDetailsController::class, 'create'])->name('admin.user-details.create');
        Route::post('/user-details', [UserDetailsController::class, 'store'])->name('admin.user-details.store');
        Route::get('/user-details/{user}/pending-verification', [UserDetailsController::class, 'pendingVerification'])->name('admin.user-details.pending-verification');
        Route::post('/user-details/{user}/resend-verification-link', [UserDetailsController::class, 'resendVerificationLink'])->name('admin.user-details.resend-verification-link');
        // Backward-compatible routes (OTP verification was replaced by email verification link).
        Route::get('/user-details/{user}/verify-otp', fn () => redirect()->route('admin.user-details.index')->with('status', 'OTP verification has been replaced by email verification link.'))
            ->name('admin.user-details.verify-otp.form');
        Route::post('/user-details/{user}/verify-otp', fn () => redirect()->route('admin.user-details.index')->with('status', 'OTP verification has been replaced by email verification link.'))
            ->name('admin.user-details.verify-otp');
        Route::get('/user-details/{user}', [UserDetailsController::class, 'show'])->name('admin.user-details.show');
        Route::get('/user-details/{user}/edit', [UserDetailsController::class, 'edit'])->name('admin.user-details.edit');
        Route::put('/user-details/{user}', [UserDetailsController::class, 'update'])->name('admin.user-details.update');
        Route::get('/user-details/{user}/reset-password', [UserDetailsController::class, 'resetPasswordForm'])->name('admin.user-details.reset-password.form');
        Route::post('/user-details/{user}/reset-password', [UserDetailsController::class, 'resetPassword'])->name('admin.user-details.reset-password');
        Route::delete('/user-details/{user}', [UserDetailsController::class, 'destroy'])->name('admin.user-details.destroy');

        Route::get('/transactions', [TransactionController::class, 'index'])->name('admin.transactions.index');
    });
});
