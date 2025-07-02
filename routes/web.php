<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::redirect('/', '/welcome', Response::HTTP_MOVED_PERMANENTLY);

Route::get('/welcome', [MainController::class, 'welcome']);

Route::get('/home', fn () => view('pages.home'));

// Registration
Volt::route('/register', 'pages.auth.register')->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Login
Route::get('/login', fn () => view('pages.auth.login'))->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected route
Route::get('/dashboard', fn () => view('pages.dashboard'))->middleware('auth');

// reset password
Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
    ->middleware('guest')->name('password.request');

// Handle sending reset link
Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->middleware('guest')->name('password.email');

// Show form to reset password
Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
    ->middleware('guest')->name('password.reset');

// Handle resetting the password
Route::post('/reset-password', [NewPasswordController::class, 'store'])
    ->middleware('guest')->name('password.update');

// Products
Route::middleware(['auth', 'seller'])->group(function () {
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
});

Route::middleware(['auth', 'seller'])->group(function () {
    Route::get('/products/mine', [ProductController::class, 'mine'])->name('products.mine');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
});

// Temporarily add this to your route for debugging
Volt::route('/test-volt', 'test-volt');
