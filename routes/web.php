<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::any('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/product/{id}', [ProductController::class, 'detail'])->name('product.detail');
Route::get('/category/{slug}', [ProductController::class, 'category'])->name('category');
Route::get('/search', [ProductController::class, 'search'])->name('search');
Route::get('/api/search', [ProductController::class, 'apiSearch']);
Route::post('/buy-now', [OrderController::class, 'buyNow'])->name('buy_now');

// User Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/user/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');
    Route::get('/user/profile', [UserController::class, 'profile'])->name('user.profile');
    Route::post('/user/profile', [UserController::class, 'updateProfile']);
    Route::get('/user/orders', [OrderController::class, 'history'])->name('user.orders');
    Route::post('/product/{id}/review', [ProductController::class, 'storeReview'])->name('product.review.store');
    Route::get('/user/notifications', [NotificationController::class, 'index'])->name('user.notifications');
    Route::get('/user/chat', [ChatController::class, 'index'])->name('user.chat');
    
    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
    
    // Checkout & Orders
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::post('/checkout', [OrderController::class, 'placeOrder'])->name('checkout.place');
    Route::get('/order/{code}', [OrderController::class, 'status'])->name('order.status');
    Route::post('/user/orders/confirm-received/{id}', [OrderController::class, 'confirmReceived'])->name('user.orders.confirm_received');

    // AJAX API Polling
    Route::get('/api/chat/messages', [ChatController::class, 'getMessages']);
    Route::post('/api/chat/send', [ChatController::class, 'sendMessage']);
    Route::post('/api/chat/edit/{id}', [ChatController::class, 'editMessage']);
    Route::delete('/api/chat/delete/{id}', [ChatController::class, 'deleteMessage']);
    Route::get('/api/chat/unread-counts', [ChatController::class, 'getUnreadCounts']);
    Route::get('/api/notifications/count', [NotificationController::class, 'getUnreadCount']);
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark_read');
    Route::get('/notifications/{id}/read', [NotificationController::class, 'readAndRedirect'])->name('notifications.read');
    Route::get('/api/notifications', [NotificationController::class, 'apiGetNotifications']);
});

// Admin Protected Routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // Admin Products CRUD
    Route::get('/admin/products', [AdminController::class, 'products'])->name('admin.products');
    Route::get('/admin/products/create', [AdminController::class, 'createProduct'])->name('admin.products.create');
    Route::post('/admin/products/store', [AdminController::class, 'storeProduct'])->name('admin.products.store');
    Route::get('/admin/products/edit/{id}', [AdminController::class, 'editProduct'])->name('admin.products.edit');
    Route::post('/admin/products/update/{id}', [AdminController::class, 'updateProduct'])->name('admin.products.update');
    Route::post('/admin/products/delete/{id}', [AdminController::class, 'deleteProduct'])->name('admin.products.delete');
    Route::post('/admin/products/bulk-delete', [AdminController::class, 'bulkDeleteProducts'])->name('admin.products.bulk_delete');
    
    // Admin Orders
    Route::get('/admin/orders', [AdminController::class, 'orders'])->name('admin.orders');
    Route::post('/admin/orders/status/{id}', [AdminController::class, 'updateOrderStatus'])->name('admin.orders.status');
    Route::post('/admin/orders/request-confirmation/{id}', [AdminController::class, 'requestOrderConfirmation'])->name('admin.orders.request_confirmation');
    
    // Admin Vouchers
    Route::get('/admin/vouchers', [AdminController::class, 'vouchers'])->name('admin.vouchers');
    Route::post('/admin/vouchers/store', [AdminController::class, 'storeVoucher'])->name('admin.vouchers.store');
    Route::post('/admin/vouchers/delete/{id}', [AdminController::class, 'deleteVoucher'])->name('admin.vouchers.delete');
    
    // Admin Customers
    Route::get('/admin/customers', [AdminController::class, 'customers'])->name('admin.customers');
    
    // Admin Chat
    Route::get('/admin/chat', [ChatController::class, 'adminChat'])->name('admin.chat');
    
    // Admin Analytics
    Route::get('/admin/sales', [AdminController::class, 'sales'])->name('admin.sales');
    Route::get('/admin/finance', [AdminController::class, 'finance'])->name('admin.finance');
    
    // Admin Profile
    Route::get('/admin/profile', [AdminController::class, 'profile'])->name('admin.profile');
    Route::post('/admin/profile', [AdminController::class, 'updateProfile'])->name('admin.profile.update');
});
