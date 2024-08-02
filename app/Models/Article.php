<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Article extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'body', 'user_id'];

    // Définir la relation avec l'utilisateur
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
