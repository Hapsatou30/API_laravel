<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArticleController;

// Déclare une ressource API pour le contrôleur des articles
Route::apiResource('articles', ArticleController::class);

// Route pour l'inscription d'un utilisateur
Route::post('/register', [AuthController::class, 'register']);

// Route pour la connexion d'un utilisateur
Route::post('/login', [AuthController::class, 'login']);

// Route pour la déconnexion d'un utilisateur, protégée par le middleware 'auth:sanctum'
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

// Route pour obtenir les informations de l'utilisateur authentifié, protégée par le middleware 'auth:sanctum'
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    // Retourne les informations de l'utilisateur actuellement authentifié
    return $request->user();
});
