<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'author_id',
        'cover_url',
        'title',
        'desc',
        'rating',
        'cook_time',
        'level',
        'review_count',
        'regional',
        'dish_type',
        'serves',
    ];
    
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function steps()
    {
        return $this->hasMany(Step::class);
    }
    
    public function ingredients()
    {
        return $this->hasMany(Ingredient::class);
    }

    public function savedByUsers()
    {
        return $this->hasMany(SavedRecipe::class, 'recipe_id');
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // public function likesWithUsers()
    // {
    //     return $this->hasMany(Like::class)->with('user');
    // }

    public function likesWithUsers()
    {
        return $this->hasMany(Like::class)->with('user:id,name,avatar_url,cover_image_url,is_active');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function calculateAverageRating()
    {
        $averageRating = $this->comments->avg('rating');
        
        // Round the average rating to 1 decimal place
        $averageRating = round($averageRating, 1);

        $this->rating = $averageRating;
        return $this->rating;
    }

    public function reviews_count()
    {        
        $this->review_count = $this->comments->count('review_count');

        return $this->review_count;
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
