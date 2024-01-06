<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Recipe\AddRecipeRequest;
use App\Http\Requests\Recipe\UpdateRecipeRequest;
use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\Step;
use App\Models\StepImage;

class RecipeController extends Controller
{
    public function addNewRecipe(AddRecipeRequest $request)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json([
                'message' => 'Unauthorized to create recipe'
            ], 403);
        }

        // Create a new recipe with the validated data
        $recipe = new Recipe($request->validated());
        $recipe->author_id = $user->id;
        $recipe->save();

        // Create steps and images for the recipe
        foreach ($request->input('steps', []) as $stepData) {
            $step = new Step([
                'number' => $stepData['number'],
                'content' => $stepData['content'],
            ]);

            $recipe->steps()->save($step);

            // Create images for the step
            if (isset($stepData['images'])) {
                foreach ($stepData['images'] as $imageUrl) {
                    $stepImage = new StepImage([
                        'image_url' => $imageUrl
                    ]);

                    $step->images()->save($stepImage);
                }
            }
        }

        // Create ingredients for the recipe
        foreach ($request->input('ingredients', []) as $ingredientData) {
            $ingredient = new Ingredient([
                'name' => $ingredientData['name'],
                'amount' => $ingredientData['amount'],
                'unit' => $ingredientData['unit'],
                'status' => $ingredientData['status'] ?? false,
            ]);

            $recipe->ingredients()->save($ingredient);
        }
        
        $recipe->load('steps.images'); // Load the relationships with the recipe
        $recipe->load('ingredients'); // Load the relationships with the ingredient

        return response()->json([
            'message' => 'Recipe created successfully',
            'recipe' => $recipe
        ], 201);
    }

    public function getAllRecipes(Request $request)
    {
        $perPage = $request->query('perPage', 10);

        $recipes = Recipe::orderBy('created_at', 'desc')->paginate($perPage);

        $recipes->load(['author:id,name,avatar_url,count_followers']);
        
        return response()->json($recipes);
    }

    public function getMyRecipes(Request $request)
    {
        $perPage = $request->query('perPage', 10);
        $user = auth()->user();
        $recipes = Recipe::where('author_id', $user->id)
                            ->orderBy('created_at', 'desc')
                            ->paginate($perPage);

        $recipes->load(['author:id,name,avatar_url,count_followers']);
        return response()->json($recipes);
    }

    public function getRecipesByAuthorId(Request $request, $authorId)
    {
        $perPage = $request->query('perPage', 10);

        $recipes = Recipe::where('author_id', $authorId)
                            ->orderBy('created_at', 'desc')
                            ->paginate($perPage);

        $recipes->load(['author:id,name,avatar_url,count_followers']);

        return response()->json($recipes);
    }
    
    public function getRecipeById($id)
    {
        $recipe = Recipe::find($id);

        if (!$recipe) {
            return response()->json(['error' => 'Recipe not found'], 404);
        }

        $recipe->load(['author:id,name,avatar_url,count_followers']);
        $recipe->load('steps.images'); // Load the relationships with the recipe
        $recipe->load('ingredients'); // Load the relationships with the ingredient

        return response()->json($recipe);
    }

    public function updateRecipe(UpdateRecipeRequest $request, $id)
    {
        $recipe = Recipe::find($id);

        if (!$recipe) {
            return response()->json(['error' => 'Recipe not found'], 404);
        }

        if ($recipe->author_id !== auth()->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $recipe->update($request->validated());

        return response()->json([
            'message' => 'Recipe updated successfully',
            'recipe' => $recipe
        ]);
    }

    public function deleteRecipe($id)
    {
        $recipe = Recipe::find($id);

        if (!$recipe) {
            return response()->json(['error' => 'Recipe not found'], 404);
        }

        if ($recipe->author_id !== auth()->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $recipe->delete();

        return response()->json(['message' => 'Recipe deleted successfully']);
    }
}
