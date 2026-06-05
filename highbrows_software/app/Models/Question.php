<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    // Fillable fields for mass assignment
    protected $fillable = [
        'subject',
        'grade',
        'question',
        'question_image',
        'options',
        'correct_answer',
        'option_images',
    ];

    // Casting options to arrays
    protected $casts = [
        'options' => 'array',
        'option_images' => 'array',  // Casting the option images as arrays
    ];

    // Accessor to get the full URL of the question image
    public function getQuestionImageUrlAttribute()
    {
        return $this->question_image ? asset('storage/' . $this->question_image) : null;
    }

    // Accessor to get the full URLs of the option images
    public function getOptionImageUrlAttribute()
    {
        return $this->option_images ? array_map(function ($path) {
            return asset('storage/' . $path);
        }, $this->option_images) : [];
    }
}

