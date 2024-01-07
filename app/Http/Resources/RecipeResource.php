<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RecipeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'author_id' => $this->author_id,
            'title' => $this->title,
            'rating' => $this->rating,
            'review_count' => $this->review_count,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            // Add any other relevant post data here...
        ];
    }
}
