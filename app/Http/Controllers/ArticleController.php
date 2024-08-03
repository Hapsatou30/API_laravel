<?php
namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
   // Afficher tous les articles
    public function index()
    {
        $articles = Article::with('user')->get(); // Affiche tous les articles avec leurs auteurs
        return response()->json($articles, 200);
    }


    // Afficher les détails d'un article spécifique
    public function show($id)
    {
        $article = Article::with('user')->find($id);
    
        if (!$article) {
            return response()->json(['message' => 'Article not found'], 404);
        }
    
        return response()->json($article, 200);
    }
    

    // Créer un nouvel article
    public function store(Request $request)
    {
        // Valider les données de la requête
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'image_path' => 'nullable|string', 
            'categorie' => 'required|string',
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

    // Mettre à jour un article existant
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
            'image_path' => 'nullable|string', 
            'categorie' => 'required|string',
        ]);

        // Mettre à jour l'article
        $article->update($validatedData);

        // Retourner l'article mis à jour en format JSON
        return response()->json($article, 200);
    }

    // Supprimer un article
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
