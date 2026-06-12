<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $fillable = [
        'activity_id',
        'title',
        'description',
        'time_limit',
        'passing_score',
        'is_published',
        'show_results',
        'randomize_questions',
    ];

    /**
     * Get the activity that owns the quiz.
     */
    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    /**
     * Get the questions for the quiz.
     */
    public function questions()
    {
        return $this->hasMany(QuizQuestion::class);
    }

    /**
     * Get the attempts for the quiz.
     */
    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    /**
     * Get the total points possible for this quiz.
     */
    public function getTotalPointsAttribute()
    {
        return $this->questions->sum('points');
    }

    /**
     * Get the number of questions in this quiz.
     */
    public function getQuestionCountAttribute()
    {
        return $this->questions->count();
    }
}
