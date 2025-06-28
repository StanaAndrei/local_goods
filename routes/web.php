<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Response;

use App\Http\Controllers\MainController;
use App\Http\Controllers\AuthController;

Route::redirect('/', '/welcome', Response::HTTP_MOVED_PERMANENTLY);

Route::get('/welcome', [MainController::class, 'welcome']);

Route::get('/home', function () {
    return view('pages.home'); // home.blade.php extends layouts.app
});

// Registration
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Login
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected route 
Route::get('/dashboard', fn() => view('pages.dashboard'))->middleware('auth');