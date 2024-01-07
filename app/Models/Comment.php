<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Comment extends Model
{
    use HasFactory, HasApiTokens;
    protected $fillable = [
        'recipe_id',
        'user_id',
        'content',
        'rating',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }

    public function commentImages()
    {
        return $this->hasMany(CommentImage::class);
    }
}
