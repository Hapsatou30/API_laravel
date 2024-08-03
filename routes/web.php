<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;
use Laravel\Sanctum\Http\Controllers\CsrfCookieController;

Route::get('/', function () {
    return view('welcome');
});

