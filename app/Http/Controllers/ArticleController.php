<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   
     public function index()
     {
         return Article::all();
     }
 
     public function store(Request $request)
     {
         $request->validate([
             'title' => 'required|string|max:255',
             'body' => 'required|string',
         ]);
 
         return Article::create($request->all());
     }
 
     public function show($id)
     {
         $article = Article::find($id);
 
         if (!$article) {
             return response()->json(['message' => 'Article not found'], 404);
         }
 
         return $article;
     }
 
     public function update(Request $request, $id)
     {
         $article = Article::find($id);
 
         if (!$article) {
             return response()->json(['message' => 'Article not found'], 404);
         }
 
         $request->validate([
             'title' => 'sometimes|string|max:255',
             'body' => 'sometimes|string',
         ]);
 
         $article->update($request->all());
 
         return $article;
     }
 
     public function destroy($id)
     {
         $article = Article::find($id);
 
         if (!$article) {
             return response()->json(['message' => 'Article not found'], 404);
         }
 
         $article->delete();
 
         return response()->json(['message' => 'Article deleted successfully']);
     }
}
