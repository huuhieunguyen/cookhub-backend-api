<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SavedRecipe;
use App\Models\Recipe;

class SavedRecipeController extends Controller
{
    public function saveRecipe(Request $request)
    {
        $user = Auth::user();
        $recipeId = $request->input('recipe_id');

        // Check if the recipe exists
        $recipe = Recipe::find($recipeId);
        if (!$recipe) {
            return response()->json([
                'message' => 'Recipe not found',
            ], 404);
        }

        // Check if the user already saved the recipe
        $existingSavedRecipe = SavedRecipe::where('owner_id', $user->id)
            ->where('recipe_id', $recipeId)
            ->first();

        if ($existingSavedRecipe) {
            return response()->json([
                'message' => 'Recipe already saved by the user',
            ], 400);
        }

        // Create a new saved recipe
        $savedRecipe = new SavedRecipe();
        $savedRecipe->owner_id = $user->id;
        $savedRecipe->recipe_id = $recipeId;
        $savedRecipe->save();

        return response()->json([
            'message' => 'Recipe saved successfully',
        ]);
    }

    public function getSavedRecipes()
    {
        $user = Auth::user();

        $savedRecipes = SavedRecipe::where('owner_id', $user->id)
            ->with(['recipe', 'recipe.author:id,name,avatar_url,count_followers'])
            ->get();

        return response()->json([
            'saved_recipes' => $savedRecipes,
        ]);
    }

    public function deleteSavedRecipe($savedRecipeId)
    {
        $user = Auth::user();

        // Check if the saved recipe exists
        $savedRecipe = SavedRecipe::find($savedRecipeId);
        if (!$savedRecipe) {
            return response()->json([
                'message' => 'Saved recipe not found',
            ], 404);
        }

        // Check if the saved recipe belongs to the authenticated user
        if ($savedRecipe->owner_id !== $user->id) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }

        // Delete the saved recipe
        $savedRecipe->delete();

        return response()->json([
            'message' => 'Saved recipe deleted successfully',
        ]);
    }
}