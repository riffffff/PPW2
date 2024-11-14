<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\Auth\LoginRegisterController;
use App\Http\Controllers\SendEmailController;

Route::get('/', function () {
    return view('welcome');
});

// Group untuk Login dan Register
Route::controller(LoginRegisterController::class)->group(function () {
    Route::get('/register', 'register')->name('register');
    Route::post('/store', 'store')->name('store');
    Route::get('/login', 'login')->name('login');
    Route::post('/authenticate', 'authenticate')->name('authenticate');
    Route::post('/logout', 'logout')->name('logout');
});

// Rute yang hanya bisa diakses oleh admin
Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('book', BookController::class);
});

// Rute dashboard untuk pengguna non-admin
Route::get('/dashboard', [LoginRegisterController::class, 'dashboard'])->name('dashboard');

Route::resource('users', UserController::class);

Route::resource('gallery', GalleryController::class);

Route::get('/send-mail', [SendEmailController::class,'index'])->name('kirim-email');

Route::post('/post-email', [SendEmailController::class, 'store'])->name('post-email');

