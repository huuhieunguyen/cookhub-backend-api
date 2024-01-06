<?php

namespace App\Http\Requests\Recipe;

use Illuminate\Foundation\Http\FormRequest;

class AddRecipeRequest extends FormRequest
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
            'cover_url' => 'nullable|string',
            'title' => 'required|string',
            'desc' => 'required|string',
            'rating' => 'numeric',
            'cook_time' => 'integer',
            'level' => 'in:easy,medium,hard,masterchef',
            'review_count' => 'integer',
            'regional' => 'nullable|string',
            'dish_type' => 'nullable|string',
            'serves' => 'integer',

            'steps' => 'required|array',
            'steps.*.number' => 'required|integer',
            'steps.*.content' => 'required|string',
            'steps.*.images' => 'nullable|array',
            'steps.*.images.*' => 'nullable|string',

            'ingredients' => 'required|array',
            'ingredients.*.name' => 'required|string',
            'ingredients.*.amount' => 'required|numeric',
            'ingredients.*.unit' => 'required|string',
            'ingredients.*.status' => 'boolean',
        ];
    }
}
