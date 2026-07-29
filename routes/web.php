<?php

use Illuminate\Support\Facades\Route;

// PUBLIC CONTROLLER
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\MidtransWebhookController;
use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\OrganizerController;

// ADMIN & ORGANIZER CONTROLLER
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\Admin\CheckinController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');

Route::get('/events/{event}', [EventController::class, 'show'])
    ->name('events.show');

// Profil Penyelenggara Publik (STEP 36)
Route::get('/organizers/{id}', [OrganizerController::class, 'show'])
    ->name('organizers.show');

/*
|--------------------------------------------------------------------------
| GOOGLE SSO ROUTES (STEP 32 & 33)
|--------------------------------------------------------------------------
*/

Route::get('/auth/google', [SocialiteController::class, 'redirectToGoogle'])
    ->name('auth.google');

Route::get('/auth/google/callback', [SocialiteController::class, 'handleGoogleCallback'])
    ->name('auth.google.callback');

/*
|--------------------------------------------------------------------------
| CHECKOUT & PAYMENTS
|--------------------------------------------------------------------------
*/

Route::get('/checkout/{event}', [CheckoutController::class, 'create'])
    ->name('checkout.create');

Route::post('/checkout/{event}', [CheckoutController::class, 'store'])
    ->name('checkout.store');

Route::get('/payment/{order_id}', [CheckoutController::class, 'payment'])
    ->name('checkout.payment');

Route::get('/success/{order_id}', [CheckoutController::class, 'success'])
    ->name('checkout.success');

/*
|--------------------------------------------------------------------------
| REVIEWS & RATINGS (STEP 34 & 35)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    Route::post('/transactions/{transaction}/review', [ReviewController::class, 'store'])
        ->name('reviews.store');
});

/*
|--------------------------------------------------------------------------
| MIDTRANS CALLBACK WEBHOOK
|--------------------------------------------------------------------------
*/

Route::post('/midtrans/callback', [MidtransWebhookController::class, 'handle']);

/*
|--------------------------------------------------------------------------
| AUTH ADMIN & ORGANIZER
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/login', [AuthController::class, 'showLogin'])
            ->name('login');

        Route::post('/login', [AuthController::class, 'login'])
            ->name('login.post');

        Route::post('/logout', [AuthController::class, 'logout'])
            ->name('logout');
    });

/*
|--------------------------------------------------------------------------
| ADMIN & ORGANIZER AREA (MULTI-TENANT)
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->middleware(['auth', 'admin_or_organizer'])
    ->name('admin.')
    ->group(function () {

        // Dashboard Analytics
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        // Master Data & Event Management
        Route::resource('categories', CategoryController::class);
        Route::resource('partners', PartnerController::class);
        Route::resource('events', AdminEventController::class);

        // Transactions & Reports
        Route::get('/transactions', [TransactionController::class, 'index'])
            ->name('transactions.index');

        Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])
            ->name('transactions.show');

        // Export PDF & Excel
        Route::get('/transactions/export/pdf', [ExportController::class, 'pdf'])
            ->name('transactions.export.pdf');

        Route::get('/transactions/export/excel', [ExportController::class, 'excel'])
            ->name('transactions.export.excel');

        // Check-in QR Scanner Panitia (STEP 38)
        Route::get('/checkin', [CheckinController::class, 'index'])
            ->name('checkin.index');

        Route::post('/checkin/process', [CheckinController::class, 'process'])
            ->name('checkin.process');
    });