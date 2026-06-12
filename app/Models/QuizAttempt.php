<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    protected $fillable = [
        'quiz_id',
        'student_id',
        'answers',
        'score',
        'percentage',
        'passed',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'answers' => 'array',
        'passed' => 'boolean',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the quiz that owns the attempt.
     */
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    /**
     * Get the student that owns the attempt.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Check if the quiz attempt is completed.
     */
    public function isCompleted()
    {
        return !is_null($this->completed_at);
    }

    /**
     * Get the time spent on the quiz in minutes.
     */
    public function getTimeSpentAttribute()
    {
        if (!$this->isCompleted()) {
            return null;
        }

        return $this->started_at->diffInMinutes($this->completed_at);
    }

    /**
     * Calculate the score for this attempt.
     */
    public function calculateScore()
    {
        if (!$this->answers) {
            return 0;
        }

        $score = 0;
        $totalPoints = 0;
        $questions = $this->quiz->questions;

        foreach ($questions as $question) {
            $totalPoints += $question->points;

            if (isset($this->answers[$question->id])) {
                $answer = $this->answers[$question->id];

                if ($question->isCorrect($answer)) {
                    $score += $question->points;
                }
            }
        }

        $this->score = $score;
        $this->percentage = $totalPoints > 0 ? ($score / $totalPoints) * 100 : 0;
        $this->passed = $this->percentage >= $this->quiz->passing_score;

        return $score;
    }
}
