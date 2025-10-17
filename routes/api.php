<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Admin Panel Routes
Route::prefix('admin')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/dashboard/stats', [DashboardController::class, 'stats']);
    
    // Admin Users Management
    Route::apiResource('admins', AdminController::class);
    Route::patch('admins/{admin}/toggle-status', [AdminController::class, 'toggleStatus']);
    Route::get('admins/role/{role}', [AdminController::class, 'byRole']);
    Route::get('admins-active', [AdminController::class, 'active']);
    Route::get('admins/stats', [AdminController::class, 'stats']);
    
    // Categories Management
    Route::apiResource('categories', CategoryController::class);
    
    // Products Management
    Route::apiResource('products', ProductController::class);
    Route::get('products/category/{category}', [ProductController::class, 'byCategory']);
    Route::patch('products/{product}/toggle-featured', [ProductController::class, 'toggleFeatured']);
    Route::post('products/upload-images', [ProductController::class, 'uploadImages']);
    
    // Orders Management
    Route::get('orders/export', [OrderController::class, 'export']);
    Route::apiResource('orders', OrderController::class)->only(['index', 'show', 'destroy']);
    Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus']);
    Route::patch('orders/{order}/payment-status', [OrderController::class, 'updatePaymentStatus']);
    Route::post('orders/{order}/notes', [OrderController::class, 'addNotes']);
    Route::get('orders/{order}/invoice', [OrderController::class, 'invoice']);
    Route::get('orders/{order}/invoice/download', [OrderController::class, 'downloadInvoice']);
    Route::get('orders/status/{status}', [OrderController::class, 'byStatus']);
    
    // Customers Management
    Route::get('customers/export', [CustomerController::class, 'export']);
    Route::apiResource('customers', CustomerController::class);
    Route::get('customers/{customer}/orders', [CustomerController::class, 'orders']);
    
    // Coupons Management
    Route::apiResource('coupons', CouponController::class);
    Route::patch('coupons/{coupon}/toggle-status', [CouponController::class, 'toggleStatus']);
    
});

// Public Frontend API Routes
Route::prefix('')->group(function () {
    // Categories for frontend
    Route::get('categories', [CategoryController::class, 'index']);
    
    // Products for frontend
    Route::get('products', [ProductController::class, 'index']);
    Route::get('products/{product}', [ProductController::class, 'show']);
    Route::get('products/category/{category}', [ProductController::class, 'byCategory']);
    
    // Orders (for frontend checkout)
    Route::post('orders', [OrderController::class, 'store']);
    
    // Coupons validation
    Route::post('coupons/validate', [CouponController::class, 'validate']);
});