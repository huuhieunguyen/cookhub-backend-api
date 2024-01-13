<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroceryRecipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'recipe_id',
        'date_saved',
        'day_of_week',
        'cover_url',
        'title',
        'desc',
        'cook_time',
        'level',
        'regional',
        'dish_type',
        'serves',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }

    public function ingredients()
    {
        return $this->hasMany(GroceryIngredient::class);
    }
}
