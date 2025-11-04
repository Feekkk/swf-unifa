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

    // Profile edit routes
    Route::get('/profile/edit', [\App\Http\Controllers\Student\ProfileController::class, 'edit'])->name('student.profile.edit');
    Route::post('/profile', [\App\Http\Controllers\Student\ProfileController::class, 'update'])->name('student.profile.update');

    // Application routes
    Route::get('/application', [\App\Http\Controllers\Student\ApplicationController::class, 'create'])->name('student.application');
    Route::post('/application', [\App\Http\Controllers\Student\ApplicationController::class, 'store'])->name('student.application.store');
    Route::get('/my-applications', function () {
        return view('student.myApplication');
    })->name('student.applications.index');
    
    // Document management routes (only for pending applications)
    Route::post('/applications/{id}/documents', [\App\Http\Controllers\Student\ApplicationController::class, 'addDocument'])->name('student.applications.documents.add');
    Route::delete('/applications/{id}/documents/{documentId}', [\App\Http\Controllers\Student\ApplicationController::class, 'deleteDocument'])->name('student.applications.documents.delete');
});

// Admin Dashboard Routes
Route::middleware('auth:admin')->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // View applications
    Route::get('/admin/applications', [\App\Http\Controllers\Admin\ApplicationController::class, 'index'])->name('admin.applications.index');
    Route::get('/admin/applications/{id}', [\App\Http\Controllers\Admin\ApplicationController::class, 'show'])->name('admin.applications.show');
    Route::post('/admin/applications/{id}/verify', [\App\Http\Controllers\Admin\ApplicationController::class, 'verify'])->name('admin.applications.verify');

    // Admin profile routes
    Route::get('/admin/profile/edit', [\App\Http\Controllers\Admin\ProfileController::class, 'edit'])->name('admin.profile.edit');
    Route::post('/admin/profile/update', [\App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('admin.profile.update');

    // Admin user management
    Route::get('/admin/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/users/{role}/{id}/edit', [\App\Http\Controllers\Admin\UserController::class, 'edit'])->name('admin.users.edit');
    Route::post('/admin/users/{role}/{id}/password', [\App\Http\Controllers\Admin\UserController::class, 'updatePassword'])->name('admin.users.updatePassword');
    Route::delete('/admin/users/{role}/{id}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('admin.users.destroy');
});

// Committee Dashboard Routes
Route::middleware('auth:committee')->group(function () {
    Route::get('/committee/dashboard', function () {
        return view('committee.dashboard');
    })->name('committee.dashboard');

    // Committee profile routes
    Route::get('/committee/profile/edit', [\App\Http\Controllers\Committee\ProfileController::class, 'edit'])->name('committee.profile.edit');
    Route::post('/committee/profile/update', [\App\Http\Controllers\Committee\ProfileController::class, 'update'])->name('committee.profile.update');

    // Committee application routes
    Route::get('/committee/applications', [\App\Http\Controllers\Committee\ApplicationController::class, 'index'])->name('committee.applications.index');
    Route::get('/committee/applications/{id}', [\App\Http\Controllers\Committee\ApplicationController::class, 'show'])->name('committee.applications.show');
});

// Logout Route (accessible by students, admins, and committees)
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
