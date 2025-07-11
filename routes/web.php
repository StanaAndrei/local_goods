<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AcquisitionController;


use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::redirect('/', '/welcome', Response::HTTP_MOVED_PERMANENTLY);

Route::get('/welcome', [MainController::class, 'welcome']);

// Route::get('/home', fn () => view('pages.home'));

// Registration
Volt::route('/register', 'pages.auth.register')->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Login
Route::get('/login', fn () => view('pages.auth.login'))->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected route
Route::get('/dashboard', fn () => view('pages.dashboard'))->middleware('auth')->name('dashboard');

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
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
});

Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// email verif
// Show notice to verify email
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

// Handle verification link
Route::get('/email/verify/{id}/{hash}', function ($id, $hash, Request $request) {
    $user = User::findOrFail($id);

    // Check the hash matches
    if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
        abort(403, 'Invalid verification link.');
    }

    // If not verified, mark as verified
    if (! $user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
    }

    // Optionally, log the user in after verification
    Auth::login($user);

    return redirect('/dashboard')->with('status', 'Your email has been verified!');
})->middleware(['signed'])->name('verification.verify');

// Resend verification email
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('status', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Temporarily add this to your route for debugging
Volt::route('/test-volt', 'test-volt');

// user
Route::get('/profile/{id}', [UserController::class, 'show'])->name('user.profile');

Route::get('/products', [ProductController::class, 'all'])->name('products.all');

// about
Route::get('/about', fn () => view('pages.about'));

Route::post('/acquisitions', [AcquisitionController::class, 'store'])
    ->middleware('auth')->name('acquisitions.store');
