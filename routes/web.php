<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Response;

use App\Http\Controllers\MainController;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;

Route::redirect('/', '/welcome', Response::HTTP_MOVED_PERMANENTLY);

Route::get('/welcome', [MainController::class, 'welcome']);

Route::get('/home', fn() => view('pages.home'));

// Registration
Route::get('/register', fn() => view('pages.auth.register'))->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Login
Route::get('/login', fn() => view('pages.auth.login'))->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected route 
Route::get('/dashboard', fn() => view('pages.dashboard'))->middleware('auth');


//reset password
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