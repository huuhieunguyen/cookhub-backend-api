<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroceryIngredient extends Model
{
    use HasFactory;

    protected $fillable = [
        'grocery_recipe_id',
        'name',
        'amount',
        'unit',
        'status',
    ];

    public function groceryRecipe()
    {
        return $this->belongsTo(GroceryRecipe::class);
    }
}
