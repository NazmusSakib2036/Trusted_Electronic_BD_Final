<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\FrontendController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminManagementController;
use App\Http\Controllers\SmsController;

// Frontend routes
Route::get('/', [FrontendController::class, 'home'])->name('home');
Route::get('/products', [FrontendController::class, 'products'])->name('products');
Route::get('/product/{id}', [FrontendController::class, 'productDetail'])->name('product.detail');
Route::get('/cart', [FrontendController::class, 'cart'])->name('cart');
Route::get('/checkout', [FrontendController::class, 'checkout'])->name('checkout');

// Admin Authentication Routes (not protected)
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.post');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
});

// Protected Admin routes
Route::prefix('admin')->middleware('admin.auth')->group(function () {
    Route::get('/', [AdminAuthController::class, 'dashboard'])->name('admin.dashboard');
    
    // Admin Management Routes (Super Admin only)
    Route::resource('admins', AdminManagementController::class)->names([
        'index' => 'admin.admins.index',
        'create' => 'admin.admins.create',
        'store' => 'admin.admins.store',
        'show' => 'admin.admins.show',
        'edit' => 'admin.admins.edit',
        'update' => 'admin.admins.update',
        'destroy' => 'admin.admins.destroy',
    ]);
    Route::post('/admins/{admin}/toggle-status', [AdminManagementController::class, 'toggleStatus'])->name('admin.admins.toggle-status');
    
    Route::get('/products', function () {
        return view('admin.products.index');
    })->name('admin.products');
    
    Route::get('/products/create', function () {
        return view('admin.products.create');
    })->name('admin.products.create');
    
    Route::get('/products/{id}/edit', function ($id) {
        return view('admin.products.edit', ['productId' => $id]);
    })->name('admin.products.edit');
    
    Route::get('/categories', function () {
        return view('admin.categories.index');
    })->name('admin.categories');
    
    Route::get('/orders', function () {
        return view('admin.orders.index');
    })->name('admin.orders');
    
    Route::get('/customers', function () {
        return view( 'admin.customers.index');
    })->name('admin.customers');
    
    Route::get('/coupons', function () {
        return view('admin.coupons.index');
    })->name('admin.coupons');
    
    Route::get('/coupons/create', function () {
        return view('admin.coupons.create');
    })->name('admin.coupons.create');
    
    // SMS Management Routes
    Route::prefix('sms')->name('admin.sms.')->group(function () {
        Route::get('/', [SmsController::class, 'index'])->name('index');
        Route::get('/compose', [SmsController::class, 'compose'])->name('compose');
        Route::post('/send', [SmsController::class, 'send'])->name('send');
        Route::get('/balance', [SmsController::class, 'balance'])->name('balance');
        Route::get('/settings', [SmsController::class, 'settings'])->name('settings');
        Route::post('/settings', [SmsController::class, 'updateSettings'])->name('settings.update');
        Route::post('/resend/{id}', [SmsController::class, 'resend'])->name('resend');
    });
});
