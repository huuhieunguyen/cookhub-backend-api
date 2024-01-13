<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Grocery\CreateGroceryRecipeRequest;
use App\Models\GroceryRecipe;
use App\Models\GroceryIngredient;
use Carbon\Carbon;

class GroceryRecipeController extends Controller
{
    public function addNewRecipeToGrocery(CreateGroceryRecipeRequest $request)
    {
        // Get the authenticated user
        $user = auth()->user();
        if (!$user) {
            return response()->json([
                'message' => 'Unauthorized to create recipe'
            ], 403);
        }

        // Create the grocery recipe
        $groceryRecipe = GroceryRecipe::create([
            'owner_id' => $user->id,
            'recipe_id' => $request->input('recipe_id'),
            'date_saved' => Carbon::now(),
            'day_of_week' => Carbon::now()->dayOfWeek,
            'cover_url' => $request->input('cover_url'),
            'title' => $request->input('title'),
            'desc' => $request->input('desc'),
            'cook_time' => $request->input('cook_time'),
            'level' => $request->input('level'),
            'regional' => $request->input('regional'),
            'dish_type' => $request->input('dish_type'),
            'serves' => $request->input('serves'),
        ]);

        // Create the ingredients for the grocery recipe
        foreach ($request->input('ingredients') as $ingredientData) {
            $ingredient = new GroceryIngredient($ingredientData);
            $groceryRecipe->ingredients()->save($ingredient);
        }

        // Load the relationships with the grocery_ingredients
        $groceryRecipe->load('ingredients'); 

        return response()->json([
            'message' => 'Grocery recipe created successfully',
            'grocery_recipe' => $groceryRecipe,
        ], 201);
    }

    public function getAllRecipesInGrocery(Request $request)
    {
        $perPage = $request->query('perPage', 20);

        $groceryRecipes = GroceryRecipe::orderBy('created_at', 'desc')->paginate($perPage);

        $groceryRecipes->load('ingredients'); 
        
        return response()->json($groceryRecipes);
    }

    public function getRecipeByIdInGrocery($id)
    {
        $groceryRecipe = GroceryRecipe::find($id);

        if (!$groceryRecipe) {
            return response()->json(['error' => 'Grocery Recipe not found'], 404);
        }

        $groceryRecipe->load('ingredients'); // Load the relationships with the ingredient

        return response()->json($groceryRecipe);
    }

    public function updateRecipeInGrocery(Request $request, $id)
    {
        // Get the authenticated user
        $user = auth()->user();
        if (!$user) {
            return response()->json([
                'message' => 'Unauthorized to create recipe'
            ], 403);
        }

        // Find the grocery recipe owned by the user
        $groceryRecipe = GroceryRecipe::where('id', $id)
            ->where('owner_id', $user->id)
            ->first();

        if (!$groceryRecipe) {
            return response()->json([
                'message' => 'Grocery recipe not found',
            ], 404);
        }

        // Update the status of the ingredients
        $ingredients = $request->input('ingredients', []);

        foreach ($ingredients as $ingredient) {
            $ingredientId = $ingredient['id'];
            $status = $ingredient['status'];

            $groceryIngredient = GroceryIngredient::where('id', $ingredientId)
                ->where('grocery_recipe_id', $groceryRecipe->id)
                ->first();

            if ($groceryIngredient) {
                $groceryIngredient->status = $status;
                $groceryIngredient->save();
            }
        }

        // Load the relationships with the grocery_ingredients
        $groceryRecipe->load('ingredients'); 

        return response()->json([
            'message' => 'Ingredients status updated successfully',
            'grocery_recipe' => $groceryRecipe,
        ]);
    }

    public function destroy($id)
    {
        // Get the authenticated user
        $user = Auth::user();

        // Find the grocery recipe owned by the user
        $groceryRecipe = GroceryRecipe::where('id', $id)
            ->where('owner_id', $user->id)
            ->first();

        if (!$groceryRecipe) {
            return response()->json([
                'message' => 'Grocery recipe not found',
            ], 404);
        }

        // Delete the grocery recipe
        $groceryRecipe->delete();

        return response()->json([
            'message' => 'Grocery recipe deleted successfully',
        ]);
    }
}
