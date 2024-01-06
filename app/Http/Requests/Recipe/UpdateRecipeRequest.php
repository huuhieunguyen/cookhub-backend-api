<?php

namespace App\Http\Requests\Recipe;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRecipeRequest extends FormRequest
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
            'title' => 'string',
            'desc' => 'string',
            'rating' => 'numeric',
            'cook_time' => 'integer',
            'level' => 'in:easy,medium,hard,masterchef',
            'review_count' => 'integer',
            'regional' => 'string',
            'dish_type' => 'string',
            'serves' => 'integer',
        ];
    }
}
