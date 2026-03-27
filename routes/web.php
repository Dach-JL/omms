<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\Auth\GoogleController;

// --- Public Landing Page ---
Route::get('/', [HomeController::class, 'index'])->name('home');

// --- Google Authentication Routes ---
Route::get('/auth/google/redirect', [GoogleController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');

Route::get('/otp/verify', [OtpController::class, 'show'])->name('otp.index');
Route::post('/otp/check', [OtpController::class, 'verify'])->name('otp.check');


Route::get('/dashboard', function () {
   
    if (Auth::check() && !Session::has('otp_verified')) {
        return redirect()->route('otp.index'); 
    }
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');


Route::get('/home', function() {
    return redirect()->route('dashboard');
});

// --- Administrative Routes ---
Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/organ', [AdminController::class, 'organ'])->name('admin.organ');
    Route::get('/orgAdmin', [AdminController::class, 'orgAdmin'])->name('admin.orgAdmin');
    Route::post('/orgadmin-upload', [AdminController::class, 'orgadmin_upload'])->name('admin.upload');
    Route::get('/members', [AdminController::class, 'members'])->name('admin.members');
    Route::get('/payments', [AdminController::class, 'payments'])->name('admin.payments');
});

// --- Guest Information Routes ---
Route::get('/about', [HomeController::class, 'about'])->name('guest.about');
Route::get('/service', [HomeController::class, 'service'])->name('guest.service');
Route::get('/events', [HomeController::class, 'event'])->name('guest.events');
Route::get('/blogs', [HomeController::class, 'blog'])->name('guest.blogs');
Route::get('/contact', [HomeController::class, 'contact'])->name('guest.contact');