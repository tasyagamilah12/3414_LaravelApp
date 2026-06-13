<?php

use Illuminate\Support\Facades\Route;

// USER CONTROLLER
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;

// ADMIN CONTROLLER
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\EventController as AdminEventController;


// ======================
// PUBLIC ROUTE
// ======================

Route::get('/', [HomeController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| Login Alias Laravel
|--------------------------------------------------------------------------
| Middleware auth bawaan Laravel mencari route bernama "login"
| Kita arahkan ke admin login.
|
*/

Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');


Route::get('/events/{event}',
[\App\Http\Controllers\EventController::class, 'show'])->name('events.show');

Route::get('/checkout', [EventController::class, 'checkout'])
    ->name('checkout');

Route::get('/my-ticket', [EventController::class, 'ticket'])
    ->name('ticket');


// ======================
// AUTH ADMIN
// ======================

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


// ======================
// ADMIN AREA
// ======================

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin'])
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::resource('categories', CategoryController::class);

        Route::resource('partners', PartnerController::class);

        Route::resource('events', AdminEventController::class);
    });
    