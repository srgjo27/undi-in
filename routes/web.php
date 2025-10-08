<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Be\AdminDashboardController;
use App\Http\Controllers\Be\ProfileController;
use App\Http\Controllers\Web\BuyerDashboardController;
use App\Http\Controllers\Be\PropertyController;

Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate']);
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'store']);
});

Route::middleware(['auth', 'checkstatus'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/edit', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    Route::middleware('checkrole:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // User Management
        Route::resource('users', \App\Http\Controllers\Be\Admin\UserController::class);
        Route::patch('/users/{user}/toggle-status', [\App\Http\Controllers\Be\Admin\UserController::class, 'toggleStatus'])->name('users.toggle-status');

        // Property Management
        Route::get('/properties', [\App\Http\Controllers\Be\Admin\PropertyController::class, 'index'])->name('properties.index');
        Route::get('/properties/{property}', [\App\Http\Controllers\Be\Admin\PropertyController::class, 'show'])->name('properties.show');
        Route::patch('/properties/{property}/status', [\App\Http\Controllers\Be\Admin\PropertyController::class, 'updateStatus'])->name('properties.update-status');
        Route::delete('/properties/{property}', [\App\Http\Controllers\Be\Admin\PropertyController::class, 'destroy'])->name('properties.destroy');
        Route::patch('/properties/bulk-status', [\App\Http\Controllers\Be\Admin\PropertyController::class, 'bulkUpdateStatus'])->name('properties.bulk-status');

        // Transaction Management
        Route::get('/transactions', [\App\Http\Controllers\Be\Admin\TransactionController::class, 'index'])->name('transactions.index');
        Route::get('/transactions/{transaction}', [\App\Http\Controllers\Be\Admin\TransactionController::class, 'show'])->name('transactions.show');
        Route::patch('/transactions/{transaction}/status', [\App\Http\Controllers\Be\Admin\TransactionController::class, 'updateStatus'])->name('transactions.update-status');
        Route::get('/transactions-report', [\App\Http\Controllers\Be\Admin\TransactionController::class, 'report'])->name('transactions.report');

        // Coupon & Raffle Management
        Route::get('/coupons', [\App\Http\Controllers\Be\Admin\CouponController::class, 'index'])->name('coupons.index');
        Route::get('/coupons/{coupon}', [\App\Http\Controllers\Be\Admin\CouponController::class, 'show'])->name('coupons.show');
        Route::get('/coupons-report', [\App\Http\Controllers\Be\Admin\CouponController::class, 'report'])->name('coupons.report');
        Route::get('/raffles', [\App\Http\Controllers\Be\Admin\CouponController::class, 'raffles'])->name('coupons.raffles');
        Route::get('/raffles/{property}', [\App\Http\Controllers\Be\Admin\CouponController::class, 'raffleDetail'])->name('coupons.raffle-detail');
        Route::post('/raffles/{property}/conduct', [\App\Http\Controllers\Be\Admin\CouponController::class, 'conductRaffle'])->name('coupons.conduct-raffle')->middleware('throttle:3,1');

        // System Configuration
        Route::get('/system/config', [\App\Http\Controllers\Be\Admin\SystemConfigController::class, 'index'])->name('system.config');
        Route::put('/system/config', [\App\Http\Controllers\Be\Admin\SystemConfigController::class, 'update'])->name('system.config.update');
        Route::post('/system/test-payment', [\App\Http\Controllers\Be\Admin\SystemConfigController::class, 'testPaymentGateway'])->name('system.test-payment');
    });

    Route::middleware('checkrole:seller')->prefix('seller')->name('seller.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::resource('properties', PropertyController::class);
        Route::patch('/properties/{property}/status', [PropertyController::class, 'updateStatus'])->name('properties.update-status');
        Route::delete('/property-images/{image}', [PropertyController::class, 'deleteImage'])->name('property-images.destroy');
        Route::patch('/property-images/{image}/set-primary', [PropertyController::class, 'setPrimaryImage'])->name('property-images.set-primary');
    });

    Route::middleware('checkrole:buyer')->prefix('buyer')->name('buyer.')->group(function () {
        Route::get('/dashboard', [BuyerDashboardController::class, 'index'])->name('dashboard');
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
