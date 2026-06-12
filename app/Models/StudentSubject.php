<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentSubject extends Model
{
    protected $fillable = [
        'student_id',
        'subject_id',
        'status',
    ];

    /**
     * Get the student that belongs to the pivot.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the subject that belongs to the pivot.
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
