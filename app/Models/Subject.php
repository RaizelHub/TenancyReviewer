<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'user_id',
        'color',
        'banner_image',
    ];

    /**
     * Get the teacher/user that owns the subject.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the activities for the subject.
     */
    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    /**
     * Get the students enrolled in this subject.
     */
    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_subjects')
            ->withPivot('status')
            ->withTimestamps();
    }
}
