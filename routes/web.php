<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CustomDesignController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

// ───────────────────────── صفحات اصلی ─────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/product/{slug}', [ShopController::class, 'show'])->name('shop.show');

Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// طراحی اختصاصی (صفحه عمومی است، ثبت درخواست برای همه آزاد)
Route::get('/custom', [CustomDesignController::class, 'index'])->name('custom.index');

// ───────────────────────── احراز هویت ─────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ───────────────────────── سبد خرید (مهمان هم می‌تواند) ─────────────────────────
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add/{product}', [CartController::class, 'add'])->name('add');
    Route::patch('/{key}', [CartController::class, 'update'])->name('update');
    Route::delete('/{key}', [CartController::class, 'destroy'])->name('destroy');
});

// ───────────────────────── پرداخت و سفارش (کاربر واردشده) ─────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    Route::get('/payment/{order}/fake', [PaymentController::class, 'fake'])->name('payment.fake');
    Route::get('/payment/{order}/pay', [PaymentController::class, 'pay'])->name('payment.pay');
    Route::get('/payment/{order}/callback', [PaymentController::class, 'callback'])->name('payment.callback');

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/favorites/{product}', [ProfileController::class, 'toggleFavorite'])->name('favorites.toggle');

    Route::post('/custom', [CustomDesignController::class, 'store'])->name('custom.store');
    Route::get('/custom/mine', [CustomDesignController::class, 'mine'])->name('custom.mine');
});

// ───────────────────────── پنل مدیریت ─────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::resource('products', Admin\ProductController::class)->except('show');
    Route::resource('categories', Admin\CategoryController::class)->except(['show', 'create', 'edit']);
    Route::resource('collections', Admin\CollectionController::class)->except(['show', 'create', 'edit']);

    Route::get('/orders', [Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [Admin\OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}', [Admin\OrderController::class, 'update'])->name('orders.update');

    Route::get('/designs', [Admin\CustomDesignController::class, 'index'])->name('designs.index');
    Route::get('/designs/{customDesign}', [Admin\CustomDesignController::class, 'show'])->name('designs.show');
    Route::patch('/designs/{customDesign}', [Admin\CustomDesignController::class, 'update'])->name('designs.update');

    Route::get('/messages', [Admin\MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{contactMessage}', [Admin\MessageController::class, 'show'])->name('messages.show');
    Route::delete('/messages/{contactMessage}', [Admin\MessageController::class, 'destroy'])->name('messages.destroy');
});
