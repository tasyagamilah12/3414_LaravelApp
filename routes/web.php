<?php

use Illuminate\Support\Facades\Route;

// PUBLIC CONTROLLER
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\MidtransWebhookController;

// ADMIN CONTROLLER
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\ExportController;

/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');

Route::get('/events/{event}', [EventController::class, 'show'])
    ->name('events.show');

/*
|--------------------------------------------------------------------------
| CHECKOUT
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
| AUTH ADMIN
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
| MIDTRANS CALLBACK
|--------------------------------------------------------------------------
*/

Route::post('/midtrans/callback', [MidtransWebhookController::class, 'handle']);

/*
|--------------------------------------------------------------------------
| ADMIN AREA
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->middleware(['auth', 'admin'])
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        // Master Data
        Route::resource('categories', CategoryController::class);
        Route::resource('partners', PartnerController::class);
        Route::resource('events', AdminEventController::class);

        // Transactions
        Route::get('/transactions', [TransactionController::class, 'index'])
            ->name('transactions.index');

        Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])
            ->name('transactions.show');

        // Export PDF
        Route::get('/transactions/export/pdf', [ExportController::class, 'pdf'])
            ->name('transactions.export.pdf');

        // Export Excel
        Route::get('/transactions/export/excel', [ExportController::class, 'excel'])
            ->name('transactions.export.excel');
    });