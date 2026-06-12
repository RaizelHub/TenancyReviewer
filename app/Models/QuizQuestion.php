<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    protected $fillable = [
        'quiz_id',
        'question',
        'type',
        'options',
        'correct_answer',
        'points',
        'explanation',
        'order',
    ];

    protected $casts = [
        'options' => 'array',
        'correct_answer' => 'array',
    ];

    /**
     * Get the quiz that owns the question.
     */
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    /**
     * Check if the provided answer is correct.
     *
     * @param mixed $answer
     * @return bool
     */
    public function isCorrect($answer)
    {
        if ($this->type === 'multiple_choice') {
            return in_array($answer, $this->correct_answer);
        } elseif ($this->type === 'true_false') {
            return $answer === $this->correct_answer[0];
        } elseif ($this->type === 'short_answer') {
            // Case insensitive comparison for short answers
            return strtolower(trim($answer)) === strtolower(trim($this->correct_answer[0]));
        }

        return false;
    }
}
