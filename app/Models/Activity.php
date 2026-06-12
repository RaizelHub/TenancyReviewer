<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'due_date',
        'subject_id',
        'type',
        'points',
        'is_published',
        'activity_document_path',  // For teacher's activity document
        'reviewer_attachment_path', // For reviewer's PDF attachment
        'google_docs_url',
        'attachment',         // For Google Docs integration
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'is_published' => 'boolean',
    ];

    /**
     * Get the subject that owns the activity.
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the submissions for the activity.
     */
    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }

    /**
     * Get the quiz for the activity.
     */
    public function quiz()
    {
        return $this->hasOne(Quiz::class);
    }

    /**
     * Check if the activity has a quiz.
     */
    public function hasQuiz()
    {
        try {
            return $this->quiz()->exists();
        } catch (\Exception $e) {
            \Log::error('Error checking if activity has quiz', [
                'activity_id' => $this->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}
