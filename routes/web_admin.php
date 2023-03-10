<?php

use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\AttributeValueController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ClientLoginController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VerificationController;
use Illuminate\Support\Facades\Route;
use Spatie\Honeypot\ProtectAgainstSpam;

Route::controller(LoginController::class)
    ->middleware('guest')
    ->group(function () {
        Route::get('/a-login', 'create')->name('admin.login');
        Route::post('/a-login', 'store')->middleware(ProtectAgainstSpam::class);
    });

Route::controller(LoginController::class)
    ->middleware('auth')
    ->group(function () {
        Route::post('/a-logout', 'destroy')->name('admin.logout');
    });

Route::middleware('auth')
    ->prefix('/admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('customers', CustomerController::class)->except(['create', 'store'])->middleware('can:customers');
        Route::resource('verifications', VerificationController::class)->only(['index'])->middleware('can:verifications');
        Route::resource('courses', CourseController::class)->middleware('can:courses');
        Route::resource('categories', CategoryController::class)->except(['show'])->middleware('can:categories');
        Route::resource('attributes', AttributeController::class)->except(['show'])->middleware('can:attributes');
        Route::resource('attributeValues', AttributeValueController::class)->except(['index', 'show'])->middleware('can:attributes');
        Route::resource('users', UserController::class)->except(['show'])->middleware('can:users');
    });