<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Response;

use App\Http\Controllers\MainController;
use App\Http\Controllers\AuthController;

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