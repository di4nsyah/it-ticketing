<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

// MVC route: request pertama masuk ke file ini, terus di lempar ke controller
// route cuma nentuin "request ini ditangani siapa", nggak nyentuh database

// landing page, belum butuh login
Route::get('/', function () {
    return view('welcome');
});

// MVC route: /dashboard -> DashboardController@index
// auth = wajib login, verified = email udah dikonfirmasi
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// middleware auth di group() bikin semua route di dalemnya ikut wajib login
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    // MVC route: resource bikin 7 route sekaligus, cuma 4 yg kepake
    // GET /tickets -> index, GET /tickets/create -> create,
    // POST /tickets -> store, GET /tickets/{id} -> show
    Route::resource('tickets', TicketController::class)
        ->only(['index', 'create', 'store', 'show', 'update']);

    // cancel dipisah karena aturannya beda, PATCH karena ubah data bukan nambah
    Route::patch('/tickets/{ticket}/cancel', [TicketController::class, 'cancel'])
        ->name('tickets.cancel');
});

// route auth bawaan Breeze: login, logout, lupa password
require __DIR__.'/auth.php';
