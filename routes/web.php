<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Be\AdminDashboardController;

Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate']);
    Route::get('/register', [AuthController::class, 'register'])->name('register');
});

Route::post('/register', [AuthController::class, 'store']);

Route::middleware(['auth', 'checkrole:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
});

Route::middleware(['auth', 'checkrole:seller'])->group(function () {
    Route::get('/seller/dashboard', [AdminDashboardController::class, 'index'])->name('seller.dashboard');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Route untuk buyer (opsional, bisa dibuat nanti)
// Route::middleware(['auth', 'checkrole:buyer'])->group(function () {
//     Route::get('/buyer/dashboard', [BuyerController::class, 'index'])->name('buyer.dashboard');
// });
