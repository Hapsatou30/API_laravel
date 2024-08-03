<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class Article extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'body', 'user_id','image_path','categorie',];

    // Définir la relation avec l'utilisateur
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function comments()
{
    return $this->hasMany(Comment::class);
}
}
