<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'score',
        'category',
        'question_ids',
    ];

    // Cast JSON database string to a clean PHP array automatically
    protected $casts = [
        'question_ids' => 'array',
    ];

    // --- RELATIONSHIP: TEST ATTEMPT BELONGS TO ONE SPECIFIC USER ---
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // --- CHECK IF PASSING GRADE (THRESHOLD >= 70) ---
    public function isPassed(): bool
    {
        return $this->score >= 70;
    }

    // --- GET STATUS AS A STRING ---
    public function getStatusAttribute(): string
    {
        return $this->isPassed() ? 'Pass' : 'Fail';
    }

    // --- FETCH QUESTION MODELS FROM STORED ARRAY IDS ---
    public function questions()
    {
        return Question::whereIn('id', $this->question_ids)->get();
    }
}
