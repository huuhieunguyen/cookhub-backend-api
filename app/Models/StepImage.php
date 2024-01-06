<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StepImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'step_id',
        'image_url'
    ];

    public function step()
    {
        return $this->belongsTo(Step::class);
    }
}
