<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Be\AdminDashboardController;
use App\Http\Controllers\Be\ProfileController;
use App\Http\Controllers\Be\Seller;
use App\Http\Controllers\Be\Admin;
use App\Http\Controllers\Web;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ContactController;

Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate']);
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'store']);
});

Route::get('/email/verify', [EmailVerificationController::class, 'notice'])->name('verification.notice');
Route::post('/email/verification-notification', [EmailVerificationController::class, 'resend'])->name('verification.send');
Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])->name('verification.verify');

Route::post('/contact-admin', [ContactController::class, 'submitContactForm'])->name('contact.admin');



Route::middleware(['auth', 'checkstatus'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/edit', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::patch('/profile/bank', [ProfileController::class, 'updateBankAccount'])->name('profile.bank.update');

    Route::prefix('messages')->name('messages.')->group(function () {
        Route::get('/', [MessageController::class, 'index'])->name('index');
        Route::post('/conversations', [MessageController::class, 'startConversation'])->name('start-conversation');
        Route::get('/conversations/{conversation}/messages', [MessageController::class, 'getMessages'])->name('get-messages');
        Route::post('/conversations/{conversation}/messages', [MessageController::class, 'sendMessage'])->name('send-message');
        Route::patch('/messages/{message}', [MessageController::class, 'editMessage'])->name('edit-message');
        Route::delete('/messages/{message}', [MessageController::class, 'deleteMessage'])->name('delete-message');
        Route::get('/users', [MessageController::class, 'getUserList'])->name('users');
        Route::get('/search', [MessageController::class, 'search'])->name('search');
        Route::get('/unread-count', [MessageController::class, 'getUnreadCount'])->name('unread-count');
    });

    Route::middleware('checkrole:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::resource('users', Admin\UserController::class);
        Route::patch('/users/{user}/toggle-status', [Admin\UserController::class, 'toggleStatus'])->name('users.toggle-status');

        Route::get('/properties', [Admin\PropertyController::class, 'index'])->name('properties.index');
        Route::get('/properties/{property}', [Admin\PropertyController::class, 'show'])->name('properties.show');
        Route::patch('/properties/{property}/status', [Admin\PropertyController::class, 'updateStatus'])->name('properties.update-status');
        Route::delete('/properties/{property}', [Admin\PropertyController::class, 'destroy'])->name('properties.destroy');
        Route::patch('/properties/bulk-status', [Admin\PropertyController::class, 'bulkUpdateStatus'])->name('properties.bulk-status');

        Route::get('/transactions', [Admin\TransactionController::class, 'index'])->name('transactions.index');
        Route::get('/transactions/{transaction}', [Admin\TransactionController::class, 'show'])->name('transactions.show');
        Route::patch('/transactions/{transaction}/status', [Admin\TransactionController::class, 'updateStatus'])->name('transactions.update-status');
        Route::get('/transactions-report', [Admin\TransactionController::class, 'report'])->name('transactions.report');

        Route::get('/coupons', [Admin\CouponController::class, 'index'])->name('coupons.index');
        Route::get('/coupons/{coupon}', [Admin\CouponController::class, 'show'])->name('coupons.show');
        Route::get('/coupons-report', [Admin\CouponController::class, 'report'])->name('coupons.report');
        Route::get('/raffles', [Admin\CouponController::class, 'raffles'])->name('coupons.raffles');
        Route::get('/raffles/{property}', [Admin\CouponController::class, 'raffleDetail'])->name('coupons.raffle-detail');
        Route::post('/raffles/{property}/conduct', [Admin\CouponController::class, 'conductRaffle'])->name('coupons.conduct-raffle')->middleware('throttle:3,1');

        Route::get('/system/config', [Admin\SystemConfigController::class, 'index'])->name('system.config');
        Route::put('/system/config', [Admin\SystemConfigController::class, 'update'])->name('system.config.update');
    });

    Route::middleware('checkrole:seller')->prefix('seller')->name('seller.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::resource('properties', Seller\PropertyController::class);
        Route::patch('/properties/{property}/status', [Seller\PropertyController::class, 'updateStatus'])->name('properties.update-status');
        Route::delete('/property-images/{image}', [Seller\PropertyController::class, 'deleteImage'])->name('property-images.destroy');
        Route::patch('/property-images/{image}/set-primary', [Seller\PropertyController::class, 'setPrimaryImage'])->name('property-images.set-primary');

        // Orders management
        Route::get('/orders', [Seller\OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [Seller\OrderController::class, 'show'])->name('orders.show');
        Route::get('/orders-awaiting-verification', [Seller\OrderController::class, 'awaitingVerification'])->name('orders.awaiting-verification');
        Route::post('/orders/{order}/verify-transfer', [Seller\OrderController::class, 'verifyTransfer'])->name('orders.verify-transfer');
        Route::get('/orders/{order}/transfer-proof', [Seller\OrderController::class, 'showTransferProof'])->name('orders.transfer-proof');
    });

    Route::middleware('checkrole:buyer')->prefix('buyer')->name('buyer.')->group(function () {
        Route::get('/home', [Web\HomeController::class, 'index'])->name('home');
        Route::get('/properties', [Web\HomeController::class, 'allProperties'])->name('properties.index');

        Route::get('/properties/{property}', [Web\OrderController::class, 'show'])->name('properties.show');

        Route::post('/properties/{property}/order', [Web\OrderController::class, 'store'])->name('orders.store');
        Route::get('/orders', [Web\OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [Web\OrderController::class, 'orderShow'])->name('orders.show');
        Route::get('/orders/{order}/payment', [Web\OrderController::class, 'payment'])->name('orders.payment');
        Route::patch('/orders/{order}/cancel', [Web\OrderController::class, 'cancel'])->name('orders.cancel');
        Route::post('/orders/{order}/upload-transfer-proof', [Web\OrderController::class, 'uploadTransferProof'])->name('orders.upload-transfer-proof');

        Route::get('/coupons', [Web\OrderController::class, 'coupons'])->name('coupons.index');
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
