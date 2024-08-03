<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    // Afficher tous les commentaires d'un article
    public function index($articleId)
    {
        // Récupère tous les commentaires d'un article spécifique avec les informations de l'utilisateur
        $comments = Comment::with('user')->where('article_id', $articleId)->get();
        // Retourne les commentaires en format JSON
        return response()->json($comments);
    }

    // Ajouter un nouveau commentaire
    public function store(Request $request, $articleId)
    {
        // Valide les données de la requête
        $request->validate([
            'comment' => 'required|string',
        ]);

        // Crée un nouveau commentaire et l'associe à l'article et à l'utilisateur authentifié
        $comment = new Comment();
        $comment->article_id = $articleId;
        $comment->user_id = Auth::id();
        $comment->comment = $request->input('comment');
        $comment->save();

        // Retourne le commentaire créé en format JSON avec le code de statut 201
        return response()->json($comment, 201);
    }

    // Mettre à jour un commentaire existant
    public function update(Request $request, $id)
    {
        // Valide les données de la requête
        $request->validate([
            'comment' => 'required|string',
        ]);

        // Trouve le commentaire par son ID
        $comment = Comment::findOrFail($id);
        
        // Vérifie que l'utilisateur est le propriétaire du commentaire
        if ($comment->user_id !== Auth::id()) {
            // Retourne une erreur de non-autorisation si l'utilisateur n'est pas le propriétaire
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        // Met à jour le contenu du commentaire
        $comment->comment = $request->input('comment');
        $comment->save();

        // Retourne le commentaire mis à jour en format JSON
        return response()->json($comment);
    }

    // Supprimer un commentaire
    public function destroy($id)
    {
        // Trouve le commentaire par son ID
        $comment = Comment::findOrFail($id);
        
        // Vérifie que l'utilisateur est le propriétaire du commentaire
        if ($comment->user_id !== Auth::id()) {
            // Retourne une erreur de non-autorisation si l'utilisateur n'est pas le propriétaire
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        // Supprime le commentaire
        $comment->delete();
        
        // Retourne un message de succès en format JSON
        return response()->json(['message' => 'Commentaire supprimé avec succès']);
    }
}
