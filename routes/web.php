<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Response;

use App\Http\Controllers\MainController;

Route::redirect('/', '/welcome', Response::HTTP_MOVED_PERMANENTLY);

Route::get('/welcome', [MainController::class, 'welcome']);

Route::get('/home', function () {
    return view('pages.home'); // home.blade.php extends layouts.app
});