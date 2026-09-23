<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;

//error
Route::get('/404', function () {
    return view('admin.errors.404');
});