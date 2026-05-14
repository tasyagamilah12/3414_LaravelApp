<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PartnerController;

// User
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/event/1', [EventController::class, 'show'])->name('events.show');
Route::get('/checkout', [EventController::class, 'checkout'])->name('checkout');
Route::get('/my-ticket', [EventController::class, 'ticket'])->name('ticket');

// Admin
Route::prefix('admin')->group(function () {

    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/events', [AdminEventController::class, 'index'])
        ->name('admin.events');

    Route::get('/events/create', [AdminEventController::class, 'create'])
        ->name('admin.events.create');

    Route::post('/events', [AdminEventController::class, 'store'])
        ->name('admin.events.store');

    Route::get('/events/{id}/edit', [AdminEventController::class, 'edit'])
        ->name('admin.events.edit');

    Route::put('/events/{id}', [AdminEventController::class, 'update'])
        ->name('admin.events.update');

    Route::delete('/events/{id}', [AdminEventController::class, 'destroy'])
        ->name('admin.events.destroy');

    Route::get('/transactions', function () {
        return view('admin.transactions');
    })->name('admin.transactions');

    Route::get('/categories', [CategoryController::class, 'index'])
        ->name('admin.categories');
        
    Route::get('/partners', [PartnerController::class, 'index']);
    
    Route::get('/partners/create', [PartnerController::class, 'create']);
    
    Route::post('/partners/store', [PartnerController::class, 'store']);
});

