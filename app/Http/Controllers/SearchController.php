<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Recipe;

class SearchController extends Controller
{
    // quey with case-sensitive
    // public function quickSearch(Request $request)
    // {
    //     $query = $request->input('query');
        
    //     $recipes = DB::table('recipes')
    //         ->where('regional', 'like', "%$query%")
    //         ->orWhere('dish_type', 'like', "%$query%")
    //         ->get();
            
    //     return response()->json($recipes);
    // }

    // quey with case-insensitive
    // public function quickSearch(Request $request)
    // {
    //     $query = $request->input('query');
        
    //     $recipes = DB::table('recipes')
    //         ->where(DB::raw('lower(regional)'), 'like', "%".strtolower($query)."%")
    //         ->orWhere(DB::raw('lower(dish_type)'), 'like', "%".strtolower($query)."%")
    //         ->get();
        
    //     return response()->json($recipes);
    // }

    public function quickSearch(Request $request)
    {
        $query = $request->input('query');
        
        $recipes = DB::table('recipes')
            ->join('users', 'recipes.author_id', '=', 'users.id')
            ->select('recipes.*', 'users.id as author_id', 'users.name', 'users.avatar_url')
            ->where(DB::raw('lower(regional)'), 'like', "%".strtolower($query)."%")
            ->orWhere(DB::raw('lower(dish_type)'), 'like', "%".strtolower($query)."%")
            ->get();
            
        return response()->json($recipes);
    }

    // quey with case-sensitive
    // public function textSearch(Request $request)
    // {
    //     $query = $request->input('query');
        
    //     $recipes = DB::table('recipes')
    //         ->where('title', 'like', "%$query%")
    //         ->orWhereExists(function ($query) use ($request) {
    //             $ingredientQuery = $request->input('query');
    //             $query->select(DB::raw(1))
    //                 ->from('ingredients')
    //                 ->whereRaw('recipes.id = ingredients.recipe_id')
    //                 ->where('name', 'like', "%$ingredientQuery%");
    //         })
    //         ->get();
            
    //     return response()->json($recipes);
    // }

    // quey with case-insensitive
    // public function textSearch(Request $request)
    // {
    //     $query = $request->input('query');
        
    //     $recipes = DB::table('recipes')
    //         ->where(DB::raw('lower(title)'), 'like', "%".strtolower($query)."%")
    //         ->orWhereExists(function ($query) use ($request) {
    //             $ingredientQuery = $request->input('query');
    //             $query->select(DB::raw(1))
    //                 ->from('ingredients')
    //                 ->whereRaw('recipes.id = ingredients.recipe_id')
    //                 ->where(DB::raw('lower(name)'), 'like', "%".strtolower($ingredientQuery)."%");
    //         })
    //         ->get();
                    
    //     return response()->json($recipes);
    // }

    public function textSearch(Request $request)
    {
        $query = $request->input('query');
        
        $recipes = DB::table('recipes')
            ->join('users', 'recipes.author_id', '=', 'users.id')
            ->select('recipes.*', 'users.name', 'users.avatar_url')
            ->where(DB::raw('lower(title)'), 'like', "%".strtolower($query)."%")
            ->orWhereExists(function ($query) use ($request) {
                $ingredientQuery = $request->input('query');
                $query->select(DB::raw(1))
                    ->from('ingredients')
                    ->whereRaw('recipes.id = ingredients.recipe_id')
                    ->where(DB::raw('lower(name)'), 'like', "%".strtolower($ingredientQuery)."%");
            })
            ->get();
            
        return response()->json($recipes);
    }

    // public function filterSearch(Request $request)
    // {
    //     $cookTime = $request->input('cook_time');
    //     $level = $request->input('level');
    //     $regional = $request->input('regional');
    //     $dishType = $request->input('dish_type');
        
    //     $query = Recipe::query();

    //     if ($cookTime !== null) {
    //         $query->where('cook_time', '<=', $cookTime);
    //     }

    //     if ($level !== null) {
    //         $levelsArray = explode(',', $level);
    //         $query->whereIn('level', $levelsArray);
    //     }

    //     if ($regional !== null) {
    //         $regionalsArray = explode(',', $regional);
    //         $query->whereIn('regional', $regionalsArray);
    //     }

    //     if ($dishType !== null) {
    //         $dishTypesArray = explode(',', $dishType);
    //         $query->whereIn('dish_type', $dishTypesArray);
    //     }

    //     $recipes = $query->get();
            
    //     return response()->json($recipes);
    // }

    public function filterSearch(Request $request)
    {
        $cookTime = $request->input('cook_time');
        $level = $request->input('level');
        $regional = $request->input('regional');
        $dishType = $request->input('dish_type');
        
        $query = Recipe::query();

        if ($cookTime !== null) {
            $query->where('cook_time', '<=', $cookTime);
        }

        if ($level !== null) {
            $levelsArray = explode(',', $level);
            $query->whereIn(DB::raw('lower(level)'), array_map('strtolower', $levelsArray));
        }

        if ($regional !== null) {
            $regionalsArray = explode(',', $regional);
            $query->whereIn(DB::raw('lower(regional)'), array_map('strtolower', $regionalsArray));
        }

        if ($dishType !== null) {
            $dishTypesArray = explode(',', $dishType);
            $query->whereIn(DB::raw('lower(dish_type)'), array_map('strtolower', $dishTypesArray));
        }

        $recipes = $query->with('author:id,name,avatar_url')->get();
            
        return response()->json($recipes);
    }
}
