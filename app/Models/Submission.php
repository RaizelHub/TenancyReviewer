<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Submission extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'activity_id',
        'file_path',
        'status',
        'grade',
        'feedback',
        'graded_at',
    ];

    protected $casts = [
        'graded_at' => 'datetime',
        'grade' => 'float',
    ];

    /**
     * Get the student that owns the submission.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the activity that owns the submission.
     */
    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }
}