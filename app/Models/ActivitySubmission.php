<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivitySubmission extends Model
{
    protected $fillable = [
        'activity_id',
        'student_id',
        'content',
        'attachment',
        'score',
        'feedback',
        'status',
    ];

    /**
     * Get the activity that owns the submission.
     */
    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    /**
     * Get the student that owns the submission.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
