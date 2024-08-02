<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;
use Laravel\Sanctum\Http\Controllers\CsrfCookieController;

Route::get('/', function () {
    return view('welcome');
});

// Route::middleware('api')->group(function () {
//     Route::apiResource('articles', ArticleController::class);
// });
// Route::get('/sanctum/csrf-cookie', [CsrfCookieController::class, 'show']);