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
        $comments = Comment::with('user')->where('article_id', $articleId)->get();
        return response()->json($comments);
    }
      // Ajouter un nouveau commentaire
      public function store(Request $request, $articleId)
      {
          $request->validate([
              'comment' => 'required|string',
          ]);
  
          $comment = new Comment();
          $comment->article_id = $articleId;
          $comment->user_id = Auth::id();
          $comment->comment = $request->input('comment');
          $comment->save();
  
          return response()->json($comment, 201);
      }


    // Mettre à jour un commentaire existant
    public function update(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required|string',
        ]);

        $comment = Comment::findOrFail($id);
        // Vérifie que l'utilisateur est le propriétaire du commentaire
        if ($comment->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $comment->comment = $request->input('comment');
        $comment->save();

        return response()->json($comment);
    }

    // Supprimer un commentaire
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        // Vérifie que l'utilisateur est le propriétaire du commentaire
        if ($comment->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $comment->delete();
        return response()->json(['message' => 'Comment deleted successfully']);
    }
  
}
