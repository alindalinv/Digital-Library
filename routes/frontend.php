<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\BookController;
use App\Http\Controllers\Frontend\BorrowingController;
use App\Http\Controllers\Frontend\DashboardController;
use App\Http\Controllers\Frontend\ProfileController;
use App\Http\Controllers\Frontend\ReviewController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
|
| These routes can be accessed without authentication.
|
*/

Route::get('/', [HomeController::class, 'index'])
    ->name('home');

Route::get('/books', [BookController::class, 'index'])
    ->name('books.index');

Route::get('/books/{book:slug}', [BookController::class, 'show'])
    ->name('books.show');


/*
|--------------------------------------------------------------------------
| Authenticated Member Routes
|--------------------------------------------------------------------------
|
| These routes use the normal "web" guard through Laravel's "auth"
| middleware.
|
| "verified" additionally requires the user's email to be verified.
|
*/

Route::middleware(['auth:web', 'verified'])
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');


        /*
        |--------------------------------------------------------------------------
        | Profile
        |--------------------------------------------------------------------------
        */

        Route::get('/profile', [ProfileController::class, 'edit'])
            ->name('profile.edit');

        Route::patch('/profile', [ProfileController::class, 'update'])
            ->name('profile.update');

        Route::patch('/profile/social', [ProfileController::class, 'updateSocial'])
            ->name('profile.social.update');

        Route::put('/profile/password', [ProfileController::class, 'updatePassword'])
            ->name('profile.password.update');

        Route::delete('/profile', [ProfileController::class, 'destroy'])
            ->name('profile.destroy');


        /*
        |--------------------------------------------------------------------------
        | Borrowings
        |--------------------------------------------------------------------------
        */

        Route::get('/borrowings', [BorrowingController::class, 'index'])
            ->name('borrowings.index');

        Route::post('/borrowings/{book}', [BorrowingController::class, 'borrowBook'])
            ->name('borrowings.store');

        Route::delete('/borrowings/{borrowing}', [BorrowingController::class, 'cancel'])
            ->name('borrowings.cancel');


        /*
        |--------------------------------------------------------------------------
        | Reviews
        |--------------------------------------------------------------------------
        */

        Route::post('/books/{book}/reviews', [ReviewController::class, 'store'])
            ->name('reviews.store');
    });