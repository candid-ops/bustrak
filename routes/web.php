<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Driver;
use App\Http\Controllers\Customer;

// ── HOME ──────────────────────────────────────────────────────
Route::get('/', function () {
    return view('welcome');
});

// ── LIVE MAP (public) ─────────────────────────────────────────
Route::get('/map', [App\Http\Controllers\MapController::class, 'index'])->name('map');

// ── AUTH ──────────────────────────────────────────────────────
require __DIR__.'/auth.php';

// ── ADMIN ─────────────────────────────────────────────────────
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {
        Route::get('/dashboard', [Admin\DashboardController::class, 'index'])
            ->name('dashboard');
        Route::resource('buses',     Admin\BusController::class);
        Route::resource('drivers',   Admin\DriverController::class);
        Route::resource('routes',    Admin\RouteController::class);
        Route::resource('schedules', Admin\ScheduleController::class);
        Route::resource('bookings',  Admin\BookingController::class)
            ->only(['index', 'show', 'destroy']);
    });

// ── DRIVER ────────────────────────────────────────────────────
Route::prefix('driver')
    ->name('driver.')
    ->middleware(['auth', 'role:driver'])
    ->group(function () {
        Route::get('/dashboard', [Driver\DashboardController::class, 'index'])
            ->name('dashboard');
    });

// ── CUSTOMER ──────────────────────────────────────────────────
Route::prefix('customer')
    ->name('customer.')
    ->middleware(['auth', 'role:customer'])
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [Customer\DashboardController::class, 'index'])
            ->name('dashboard');

        // Booking flow
        Route::get('/book', [Customer\BookingController::class, 'index'])
            ->name('book');
        Route::post('/schedules', [Customer\BookingController::class, 'schedules'])
            ->name('schedules');
        Route::get('/seats/{schedule}', [Customer\BookingController::class, 'seats'])
            ->name('seats');
        Route::post('/book/store', [Customer\BookingController::class, 'store'])
            ->name('book.store');
        Route::post('/booking/{booking}/cancel', [Customer\BookingController::class, 'cancel'])
            ->name('booking.cancel');

        // Payment flow — order matters, specific routes before parameterised
        Route::get('/payment/{booking}/status', [Customer\PaymentController::class, 'status'])
            ->name('payment.status');
        Route::get('/payment/{booking}/check', [Customer\PaymentController::class, 'check'])
            ->name('payment.check');
        Route::get('/payment/{booking}', [Customer\PaymentController::class, 'show'])
            ->name('payment');
        Route::post('/payment/{booking}', [Customer\PaymentController::class, 'initiate'])
            ->name('payment.initiate');
    });

// ── M-PESA WEBHOOK (no CSRF) ──────────────────────────────────
Route::post('/api/mpesa/callback', [Customer\PaymentController::class, 'callback'])
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class])
    ->name('mpesa.callback');

// ── CUSTOM PASSWORD RESET (COMPLETE) ──────────────────────────
Route::post('/custom-forgot-password', [App\Http\Controllers\Auth\CustomPasswordResetController::class, 'sendResetLink'])
    ->name('custom.password.email');

Route::get('/reset-password/{token}', [App\Http\Controllers\Auth\CustomPasswordResetController::class, 'showResetForm'])
    ->name('custom.password.reset');

Route::post('/reset-password', [App\Http\Controllers\Auth\CustomPasswordResetController::class, 'reset'])
    ->name('custom.password.update');

    // Admin Analytics Routes
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {
        Route::get('/analytics', [App\Http\Controllers\Admin\AnalyticsController::class, 'index'])
            ->name('analytics');
        // ... existing routes
    });

    Route::get('/debug', function() {
    return [
        'public_exists' => is_dir(public_path()),
        'index_exists' => file_exists(public_path('index.php')),
        'artisan_exists' => file_exists(base_path('artisan')),
        'files_in_public' => scandir(public_path()),
        'laravel_version' => app()->version(),
    ];
});

    