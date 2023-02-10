<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Client\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Client\CourseController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\RegisterController;
use App\Http\Controllers\Client\VerificationController;
use Illuminate\Support\Facades\Route;
use Spatie\Honeypot\ProtectAgainstSpam;

Route::controller(HomeController::class)
    ->group(function () {
        Route::get('', 'index')->name('home');
        Route::get('/locale/{locale}', 'language')->name('language')->where('locale', '[a-z]+');
    });

Route::controller(CourseController::class)
    ->group(function () {
        Route::get('/course/{slug}', 'course')->name('course')->where('slug', '[A-Za-z0-9-]+');
        Route::get('/category/{slug}', 'category')->name('category')->where('slug', '[A-Za-z0-9-]+');
    });



Route::controller(CartController::class)
    ->prefix('/cart')
    ->name('cart.')
    ->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('/add/{id}', 'add')->name('add')->where('id', '[0-9]+');
        Route::get('/remove/{id}', 'remove')->name('remove')->where('id', '[0-9]+');
        Route::get('/clear', 'clear')->name('clear');
    });

Route::controller(VerificationController::class)
    ->middleware(['guest:customer_web', 'throttle:3,1'])
    ->group(function () {
        Route::get('/verification', 'create')->name('verification');
        Route::post('/verification', 'store');
    });

Route::controller(RegisteredUserController::class)
    ->middleware('guest')
    ->group(function () {
        Route::get('/register', 'create')->name('client.register');
        Route::post('/register', 'store')->middleware(ProtectAgainstSpam::class);
    });

Route::controller(AuthenticatedSessionController::class)
    ->middleware('guest')
    ->group(function () {
        Route::get('/login', 'create')->name('client.login');
        Route::post('/login', 'store')->middleware(ProtectAgainstSpam::class);
    });

Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')->name('logout');

Route::middleware('auth')
    ->prefix('/client')
    ->name('client.')
    ->group(function () {
        Route::get('/', [HomeController::class, 'index'])->name('home');

        Route::resource('verifications', VerificationController::class)->only(['index'])->middleware('can:verifications');
        Route::resource('courses', CourseController::class)->middleware('can:courses');
        Route::resource('categories', CategoryController::class)->except(['show'])->middleware('can:categories');
        Route::resource('attributes', AttributeController::class)->except(['show'])->middleware('can:attributes');
        Route::resource('attributeValues', AttributeValueController::class)->except(['index', 'show'])->middleware('can:attributes');
        Route::resource('users', UserController::class)->except(['show'])->middleware('can:users');
    });