<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Student extends Authenticatable
{
    use BelongsToTenant;

    protected $fillable = [
        'name',
        'email',
        'student_id',
        'profile_photo',
        'notes',
        'plan',
        'password',
        'tenant_id',
    ];

    /**
     * Get the subjects the student is enrolled in.
     */
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'student_subjects')
            ->withPivot('status')
            ->withTimestamps();
    }

    /**
     * Get the activity submissions for the student.
     */
    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    /**
     * Get the subscriptions for the student.
     */
    public function subscriptions()
    {
        return $this->hasMany(StudentSubscription::class);
    }

    /**
     * Get the active subscription for the student.
     */
    public function activeSubscription()
    {
        return $this->subscriptions()
            ->where('status', 'active')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->latest()
            ->first();
    }

    /**
     * Get the quiz attempts for the student.
     */
    public function quizAttempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    /**
     * Check if the student has attempted a specific quiz.
     */
    public function hasAttemptedQuiz($quizId)
    {
        return $this->quizAttempts()->where('quiz_id', $quizId)->exists();
    }

    /**
     * Get the latest attempt for a specific quiz.
     */
    public function getLatestQuizAttempt($quizId)
    {
        return $this->quizAttempts()
            ->where('quiz_id', $quizId)
            ->latest()
            ->first();
    }

    /**
     * Convert percentage grade to college grade scale.
     *
     * @param float $percentage
     * @return array Returns an array with college_grade and remarks
     */
    public static function percentageToCollegeGrade($percentage)
    {
        if ($percentage >= 96) {
            return ['college_grade' => '1.0', 'remarks' => 'Excellent'];
        } elseif ($percentage >= 94) {
            return ['college_grade' => '1.25', 'remarks' => 'Outstanding'];
        } elseif ($percentage >= 92) {
            return ['college_grade' => '1.5', 'remarks' => 'Very Good'];
        } elseif ($percentage >= 89) {
            return ['college_grade' => '1.75', 'remarks' => 'Good'];
        } elseif ($percentage >= 86) {
            return ['college_grade' => '2.0', 'remarks' => 'Very Satisfactory'];
        } elseif ($percentage >= 83) {
            return ['college_grade' => '2.25', 'remarks' => 'Satisfactory'];
        } elseif ($percentage >= 80) {
            return ['college_grade' => '2.5', 'remarks' => 'Satisfactory'];
        } elseif ($percentage >= 77) {
            return ['college_grade' => '2.75', 'remarks' => 'Fair'];
        } elseif ($percentage >= 75) {
            return ['college_grade' => '3.0', 'remarks' => 'Passed'];
        } else {
            return ['college_grade' => '5.0', 'remarks' => 'Failed'];
        }
    }

    /**
     * Calculate the final grade for a specific subject.
     *
     * @param int $subjectId
     * @return array Returns an array with grade, college_grade, remarks, total_activities, and completed_activities
     */
    public function calculateFinalGradeForSubject($subjectId)
    {
        // Get all activities for this subject
        $subject = \App\Models\Subject::find($subjectId);
        if (!$subject) {
            return [
                'grade' => 0,
                'college_grade' => '5.0',
                'remarks' => 'No Data',
                'total_activities' => 0,
                'completed_activities' => 0,
                'percentage' => 0
            ];
        }

        $activities = $subject->activities;
        $totalActivities = $activities->count();

        if ($totalActivities === 0) {
            return [
                'grade' => 0,
                'college_grade' => '5.0',
                'remarks' => 'No Activities',
                'total_activities' => 0,
                'completed_activities' => 0,
                'percentage' => 0
            ];
        }

        // Initialize variables
        $totalPoints = 0;
        $earnedPoints = 0;
        $completedActivities = 0;

        // Calculate grades from assignments/submissions
        $submissions = $this->submissions()
            ->whereIn('activity_id', $activities->pluck('id'))
            ->whereNotNull('grade')
            ->get();

        foreach ($submissions as $submission) {
            $activity = $activities->firstWhere('id', $submission->activity_id);
            if ($activity) {
                $activityPoints = $activity->points ?? 100; // Default to 100 if not specified
                $totalPoints += $activityPoints;
                $earnedPoints += ($submission->grade / 100) * $activityPoints;
                $completedActivities++;
            }
        }

        // Calculate grades from quizzes
        foreach ($activities as $activity) {
            if ($activity->quiz) {
                $latestAttempt = $this->getLatestQuizAttempt($activity->quiz->id);
                if ($latestAttempt) {
                    $quizPoints = $activity->points ?? 100; // Default to 100 if not specified
                    $totalPoints += $quizPoints;
                    $earnedPoints += ($latestAttempt->percentage / 100) * $quizPoints;
                    $completedActivities++;
                }
            }
        }

        // Calculate final grade percentage
        $finalGrade = $totalPoints > 0 ? round(($earnedPoints / $totalPoints) * 100, 1) : 0;

        // Convert to college grade
        $collegeGradeData = self::percentageToCollegeGrade($finalGrade);

        return [
            'grade' => $finalGrade,
            'college_grade' => $collegeGradeData['college_grade'],
            'remarks' => $collegeGradeData['remarks'],
            'total_activities' => $totalActivities,
            'completed_activities' => $completedActivities,
            'percentage' => $totalPoints > 0 ? round(($completedActivities / $totalActivities) * 100) : 0
        ];
    }

    /**
     * Check if the student has premium access.
     */
    public function hasPremiumAccess()
    {
        return $this->plan === 'premium';
    }
}
