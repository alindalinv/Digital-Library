<?php

use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\AuthorController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\BorrowingController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EbookFileController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\PublisherController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Authentication
|--------------------------------------------------------------------------
|
| These routes use the dedicated "admin" guard.
|
*/

Route::middleware('guest:admin')->group(function () {

    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
        ->name('admin.login');

    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->name('admin.login.store');
});


/*
|--------------------------------------------------------------------------
| Admin Logout
|--------------------------------------------------------------------------
|
| Logout only requires authentication with the admin guard.
| Do not use the "admin" authorization middleware here.
|
*/

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth:admin')
    ->name('admin.logout');


/*
|--------------------------------------------------------------------------
| Protected Admin Panel
|--------------------------------------------------------------------------
|
| Everything here requires:
|
| 1. Authentication using the admin guard
| 2. Admin authorization
|
*/

Route::middleware(['auth:admin', 'admin'])
    ->name('admin.')
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

        Route::patch('/profile/header', [ProfileController::class, 'updateHeader'])
            ->name('profile.header.update');

        Route::put('/profile/password', [ProfileController::class, 'updatePassword'])
            ->name('profile.password.update');

        Route::delete('/profile', [ProfileController::class, 'destroy'])
            ->name('profile.destroy');


        /*
        |--------------------------------------------------------------------------
        | Categories
        |--------------------------------------------------------------------------
        */

        Route::resource('categories', CategoryController::class);


        /*
        |--------------------------------------------------------------------------
        | Books
        |--------------------------------------------------------------------------
        */

        Route::post('books/upload-image', [BookController::class, 'uploadImage'])
            ->name('books.upload-image');

        Route::get('books/trashed', [BookController::class, 'trashed'])
            ->name('books.trashed');

        Route::post('books/{id}/restore', [BookController::class, 'restore'])
            ->name('books.restore');

        Route::delete('books/{id}/force-delete', [BookController::class, 'forceDelete'])
            ->name('books.force-delete');

        Route::resource('books', BookController::class);


        /*
        |--------------------------------------------------------------------------
        | Authors
        |--------------------------------------------------------------------------
        */

        Route::resource('authors', AuthorController::class);


        /*
        |--------------------------------------------------------------------------
        | Publishers
        |--------------------------------------------------------------------------
        */

        Route::resource('publishers', PublisherController::class);


        /*
        |--------------------------------------------------------------------------
        | E-book Files
        |--------------------------------------------------------------------------
        */

        Route::resource('ebook-files', EbookFileController::class)
            ->parameters([
                'ebook-files' => 'ebookFile',
            ]);

        Route::get('ebook-files/{ebookFile}/view', [EbookFileController::class, 'view'])
            ->name('ebook-files.view');

        Route::get('ebook-files/{ebookFile}/stream', [EbookFileController::class, 'stream'])
            ->name('ebook-files.stream');

        Route::get('ebook-files/{ebookFile}/download', [EbookFileController::class, 'download'])
            ->name('ebook-files.download');

        Route::get('ebook-files/{ebookFile}/embed', [EbookFileController::class, 'embed'])
            ->name('ebook-files.embed');


        /*
        |--------------------------------------------------------------------------
        | Borrowings
        |--------------------------------------------------------------------------
        */

        Route::post('borrowings/{borrowing}/approve', [BorrowingController::class, 'approve'])
            ->name('borrowings.approve');

        Route::get('borrowings/pending', [BorrowingController::class, 'pending'])
            ->name('borrowings.pending');

        Route::resource('borrowings', BorrowingController::class);


        /*
        |--------------------------------------------------------------------------
        | Reviews
        |--------------------------------------------------------------------------
        */

        Route::resource('reviews', ReviewController::class)
            ->only([
                'index',
                'show',
                'update',
                'destroy',
            ]);


        /*
        |--------------------------------------------------------------------------
        | Roles
        |--------------------------------------------------------------------------
        */

        Route::get('roles/{role}/permissions', [RoleController::class, 'editPermissions'])
            ->name('roles.permissions.edit');

        Route::put('roles/{role}/permissions', [RoleController::class, 'updatePermissions'])
            ->name('roles.permissions.update');

        Route::resource('roles', RoleController::class);


        /*
        |--------------------------------------------------------------------------
        | Permissions
        |--------------------------------------------------------------------------
        */

        Route::resource('permissions', PermissionController::class);


        /*
        |--------------------------------------------------------------------------
        | Users
        |--------------------------------------------------------------------------
        */

        Route::get('users/{user}/roles', [UserController::class, 'editRoles'])
            ->name('users.roles.edit');

        Route::put('users/{user}/roles', [UserController::class, 'updateRoles'])
            ->name('users.roles.update');

        Route::resource('users', UserController::class);


        /*
        |--------------------------------------------------------------------------
        | Reports
        |--------------------------------------------------------------------------
        */

        Route::get('reports', [ReportController::class, 'index'])
            ->name('reports.index');


        /*
        |--------------------------------------------------------------------------
        | Audit Logs
        |--------------------------------------------------------------------------
        */

        Route::get('audit-logs', [AuditLogController::class, 'index'])
            ->name('audit-logs.index');
    });