<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    public function index()
    {
        // Retourner tous les articles de l'utilisateur authentifié en format JSON
        return response()->json(Auth::user()->articles, 200);
    }

    public function store(Request $request)
    {
        // Valider les données de la requête
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        // Créer un nouvel article associé à l'utilisateur authentifié
        $article = Auth::user()->articles()->create($validatedData);

        // Retourner la réponse en format JSON
        return response()->json($article, 201);
    }

    public function show($id)
    {
        // Trouver l'article par ID
        $article = Article::find($id);

        // Vérifier si l'article appartient à l'utilisateur authentifié
        if (!$article || $article->user_id !== Auth::id()) {
            return response()->json(['message' => 'Article not found or access denied'], 404);
        }

        // Retourner l'article en format JSON
        return response()->json($article, 200);
    }

    public function update(Request $request, $id)
    {
        // Trouver l'article par ID
        $article = Article::find($id);

        // Vérifier si l'article appartient à l'utilisateur authentifié
        if (!$article || $article->user_id !== Auth::id()) {
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
        // Trouver l'article par ID
        $article = Article::find($id);

        // Vérifier si l'article appartient à l'utilisateur authentifié
        if (!$article || $article->user_id !== Auth::id()) {
            return response()->json(['message' => 'Article not found or access denied'], 404);
        }

        // Supprimer l'article
        $article->delete();

        // Retourner un message de succès en format JSON
        return response()->json(['message' => 'Article deleted successfully'], 200);
    }
}
