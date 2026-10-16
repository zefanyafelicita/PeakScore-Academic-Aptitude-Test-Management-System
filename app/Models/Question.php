<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    // Fillable list
    protected $fillable = [
        'category',
        'content',
        'option_a',
        'option_b',
        'option_c',
        'option_d',
        'image_path',
        'correct_answer'
    ];

    // --- CHECK IF SUBMITTED ANSWER MATCHES KEY ---
    public function isCorrect(string $answer): bool
    {
        return $this->correct_answer === $answer;
    }

    // --- LOCAL SCOPE: FILTER QUESTIONS BY CATEGORY ---
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}
