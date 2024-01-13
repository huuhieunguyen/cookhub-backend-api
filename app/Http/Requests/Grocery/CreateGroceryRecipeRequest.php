<?php

namespace App\Http\Requests\Grocery;

use Illuminate\Foundation\Http\FormRequest;

class CreateGroceryRecipeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'recipe_id' => 'required|integer',
            'cover_url' => 'nullable|url',
            'title' => 'required|string',
            'desc' => 'required|string',
            'cook_time' => 'required|integer',
            'level' => 'in:easy,medium,hard,masterchef',
            'regional' => 'nullable|string',
            'dish_type' => 'nullable|string',
            'serves' => 'required|integer',
            
            'ingredients' => 'required|array',
            'ingredients.*.name' => 'required|string',
            'ingredients.*.amount' => 'required|numeric',
            'ingredients.*.unit' => 'required|string',
            'ingredients.*.status' => 'boolean',
        ];
    }
}
