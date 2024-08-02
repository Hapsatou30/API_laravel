<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    public function index()
    {
        $user = Auth::user();
    
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
    
        // Accéder à tous les articles de l'utilisateur authentifié
        $articles = $user->articles;
    
        // Retourner les articles en format JSON
        return response()->json($articles, 200);
    }
    
    public function store(Request $request)
    {
        // Valider les données de la requête
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);
    
        // Récupérer l'utilisateur authentifié
        $user = Auth::user();
    
        // Créer un nouvel article associé à l'utilisateur authentifié
        $article = new Article($validatedData);
        $article->user_id = $user->id;
        $article->save();
    
        // Retourner la réponse en format JSON
        return response()->json($article, 201);
    }
    
    

    public function show($id)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Trouver l'article par ID
        $article = Article::find($id);

        // Vérifier si l'article appartient à l'utilisateur authentifié
        if (!$article || $article->user_id !== $user->id) {
            return response()->json(['message' => 'Article not found or access denied'], 404);
        }

        // Retourner l'article en format JSON
        return response()->json($article, 200);
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Trouver l'article par ID
        $article = Article::find($id);

        // Vérifier si l'article appartient à l'utilisateur authentifié
        if (!$article || $article->user_id !== $user->id) {
            return response()->json(['message' => 'Article not found or access denied'], 404);
        }

        // Valider les données de la requête
        $validatedData = $request->validate([
            'title' => 'sometimes|string|max:255',
            'body' => 'sometimes|string',
        ]);

        // Mettre à jour l'article
        $article->update($validatedData);

        // Retourner l'article mis à jour en format JSON
        return response()->json($article, 200);
    }

    public function destroy($id)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Trouver l'article par ID
        $article = Article::find($id);

        // Vérifier si l'article appartient à l'utilisateur authentifié
        if (!$article || $article->user_id !== $user->id) {
            return response()->json(['message' => 'Article not found or access denied'], 404);
        }

        // Supprimer l'article
        $article->delete();

        // Retourner un message de succès en format JSON
        return response()->json(['message' => 'Article deleted successfully'], 200);
    }
}
