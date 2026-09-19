<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\ProfileController;
use App\Http\Controllers\Frontend\BookController;
// routes/frontend.php

// Public Book Routes
Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::get('/books/{book:slug}', [BookController::class, 'show'])->name('books.show');

Route::middleware(['auth', 'verified'])
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('frontend.dashboard.index');
        })->name('dashboard');

        Route::get('/profile', [
            ProfileController::class,
            'edit',
        ])->name('profile.edit');

        Route::patch('/profile', [
            ProfileController::class,
            'update',
        ])->name('profile.update');

        Route::delete('/profile', [
            ProfileController::class,
            'destroy',
        ])->name('profile.destroy');
    });