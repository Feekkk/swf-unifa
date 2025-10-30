<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

// Homepage
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Contact form route
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

// Authentication Routes
Route::group(['middleware' => 'guest'], function () {
    // Login Routes
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:5,1'); // 5 attempts per minute
    
    // Registration Routes
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->middleware('throttle:3,1'); // 3 attempts per minute
    
    // AJAX validation routes for registration
    Route::post('/check-username', [RegisterController::class, 'checkUsername'])->name('check.username')->middleware('throttle:10,1');
    Route::post('/check-email', [RegisterController::class, 'checkEmail'])->name('check.email')->middleware('throttle:10,1');
    Route::post('/check-student-id', [RegisterController::class, 'checkStudentId'])->name('check.student.id')->middleware('throttle:10,1');
    
    // Password Reset Routes
    Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email')->middleware('throttle:3,1');
    Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update')->middleware('throttle:3,1');
});

// Authenticated Student Dashboard
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('student.dashboard');
    })->name('dashboard');
});

// Logout Route (for authenticated users)
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');
