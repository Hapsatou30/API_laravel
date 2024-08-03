<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $fillable = ['article_id', 'user_id', 'comment'];

    // Définir la relation avec l'article
    public function article()
    {
        return $this->belongsTo(Article::class);
    }
    // Définir la relation avec l'utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
