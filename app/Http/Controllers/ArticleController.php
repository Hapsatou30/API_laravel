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
        // Récupérer tous les articles avec leurs auteurs associés
        $articles = Article::with('user')->get();
        // Retourner les articles en format JSON avec un code de statut 200
        return response()->json($articles, 200);
    }

    // Afficher les détails d'un article spécifique
    public function show($id)
    {
        // Trouver l'article par ID avec son auteur associé
        $article = Article::with('user')->find($id);

        // Vérifier si l'article existe
        if (!$article) {
            // Si l'article n'existe pas, retourner un message d'erreur avec un code de statut 404
            return response()->json(['message' => 'Article non trouvé'], 404);
        }

        // Retourner l'article en format JSON avec un code de statut 200
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

        // Créer un nouvel article avec les données validées
        $article = new Article($validatedData);
        $article->user_id = $user->id; // Associer l'article à l'utilisateur authentifié
        $article->save(); 

        // Retourner l'article créé en format JSON avec un code de statut 201 (créé)
        return response()->json($article, 201);
    }

    // Mettre à jour un article existant
    public function update(Request $request, $id)
    {
        // Récupérer l'utilisateur authentifié
        $user = Auth::user();

        // Vérifier si l'utilisateur est authentifié
        if (!$user) {
            // Si l'utilisateur n'est pas authentifié, retourner un message d'erreur avec un code de statut 401 (non autorisé)
            return response()->json(['message' => 'Non autorisé'], 401);
        }

        // Trouver l'article par ID
        $article = Article::find($id);

        // Vérifier si l'article existe et appartient à l'utilisateur authentifié
        if (!$article || $article->user_id !== $user->id) {
            // Si l'article n'existe pas ou n'appartient pas à l'utilisateur, retourner un message d'erreur avec un code de statut 404
            return response()->json(['message' => 'Article non trouvé ou accès refusé'], 404);
        }

        // Valider les données de la requête (les champs sont optionnels)
        $validatedData = $request->validate([
            'title' => 'required|string|max:255', 
            'body' => 'required|string', 
            'image_path' => 'nullable|string', 
            'categorie' => 'required|string', 
        ]);

        // Mettre à jour l'article avec les données validées
        $article->update($validatedData);

        // Retourner l'article mis à jour en format JSON avec un code de statut 200
        return response()->json($article, 200);
    }

    // Supprimer un article
    public function destroy($id)
    {
        // Récupérer l'utilisateur authentifié
        $user = Auth::user();

        // Vérifier si l'utilisateur est authentifié
        if (!$user) {
            // Si l'utilisateur n'est pas authentifié, retourner un message d'erreur avec un code de statut 401 (non autorisé)
            return response()->json(['message' => 'Non autorisé'], 401);
        }

        // Trouver l'article par ID
        $article = Article::find($id);

        // Vérifier si l'article existe et appartient à l'utilisateur authentifié
        if (!$article || $article->user_id !== $user->id) {
            // Si l'article n'existe pas ou n'appartient pas à l'utilisateur, retourner un message d'erreur avec un code de statut 404
            return response()->json(['message' => 'Article non trouvé ou accès refusé'], 404);
        }

        // Supprimer l'article
        $article->delete();

        // Retourner un message de succès en format JSON avec un code de statut 200
        return response()->json(['message' => 'Article supprimer avec succès'], 200);
    }
}
