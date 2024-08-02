<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // Méthode pour enregistrer un nouvel utilisateur
    public function register(Request $request)
    {
        // Valider les données de la requête
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        // Si la validation échoue, retourner les erreurs de validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Créer un nouvel utilisateur avec les données validées
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Hasher le mot de passe
        ]);

        // Générer un jeton d'authentification pour l'utilisateur
        $token = $user->createToken('auth_token')->plainTextToken;

        // Retourner une réponse JSON avec le jeton d'authentification
        return response()->json(['access_token' => $token, 'token_type' => 'Bearer']);
    }

    // Méthode pour connecter un utilisateur
    public function login(Request $request)
    {
        // Tenter de se connecter avec les informations fournies
        if (!auth()->attempt($request->only('email', 'password'))) {
            // Si la connexion échoue, retourner un message d'erreur
            return response()->json(['message' => 'Invalid login details'], 401);
        }

        // Récupérer l'utilisateur correspondant à l'email fourni
        $user = User::where('email', $request['email'])->firstOrFail();

        // Générer un jeton d'authentification pour l'utilisateur
        $token = $user->createToken('auth_token')->plainTextToken;

        // Retourner une réponse JSON avec le jeton d'authentification
        return response()->json(['access_token' => $token, 'token_type' => 'Bearer']);
    }

    // Méthode pour déconnecter un utilisateur
    public function logout(Request $request)
    {
        // Supprimer le jeton d'authentification actuel de l'utilisateur
        $request->user()->currentAccessToken()->delete();

        // Retourner une réponse JSON confirmant la déconnexion
        return response()->json(['message' => 'Successfully logged out']);
    }
}
