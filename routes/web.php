<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\EbookFileController;
use App\Http\Controllers\Frontend\AuthorController;
//error
Route::get('/404', function () {
    return view('admin.errors.404');
});
Route::get('/authors/{author}', [AuthorController::class, 'show']) ->name('authors.show');
Route::middleware(['permission:ebook-files.view'])
    ->prefix('ebooks/{ebookFile}')
    ->name('ebooks.')
    ->group(function () {
        Route::get('/embed',    [EbookFileController::class, 'embed'])->name('embed');
        Route::get('/stream',   [EbookFileController::class, 'stream'])->name('stream');
        Route::get('/view',     [EbookFileController::class, 'view'])->name('view');
    });

Route::middleware(['permission:ebook-files.download'])
    ->get('/ebooks/{ebookFile}/download', [EbookFileController::class, 'download'])
    ->name('ebooks.download');