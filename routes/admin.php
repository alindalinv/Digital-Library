<?php

use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Login
|--------------------------------------------------------------------------
*/

Route::get('/login', [
    AuthenticatedSessionController::class,
    'create'
])->name('admin.login');

Route::post('/login', [
    AuthenticatedSessionController::class,
    'store'
])->name('admin.login.store');
Route::post('/logout', [
    AuthenticatedSessionController::class,
    'destroy'
])->name('admin.logout');

/*
|--------------------------------------------------------------------------
| Admin Dashboard
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [
            DashboardController::class,
            'index'
        ])->name('dashboard');
        Route::get('/profile', [
            ProfileController::class,
            'index'
        ])->name('profile.index');

    });