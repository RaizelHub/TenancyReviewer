<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Student;
use App\Models\Activity;
use App\Models\Submission;
use Illuminate\Support\Facades\Auth;

class StudentDashboardController extends Controller
{
    /**
     * Show the student dashboard.
     */
    public function dashboard()
    {
        $student = Auth::guard('student')->user();
        $subjects = $student ? $student->subjects()->with(['activities', 'user'])->get() : [];
        return view('app.student-dashboard', compact('subjects'));
    }

    /**
     * Show student's enrolled subjects list.
     */
    public function subjects()
    {
        $student = Auth::guard('student')->user();
        $subjects = $student ? $student->subjects()->with(['activities', 'user'])->get() : [];
        return view('app.my-subjects', compact('subjects'));
    }

    /**
     * Show student's subject details.
     */
    public function subjectShow(Subject $subject)
    {
        $student = Auth::guard('student')->user();

        // Check if the student is enrolled in this subject
        if (!$student->subjects()->where('subjects.id', $subject->id)->exists()) {
            return redirect()->route('student.subjects')->with('error', 'You are not enrolled in this subject.');
        }

        // Load the subject with its teacher, activities, and classmates
        $subject->load(['user', 'activities.quiz']);
        $classmates = $subject->students()->where('students.id', '!=', $student->id)->get();

        // Calculate the final grade for this subject
        $gradeData = $student->calculateFinalGradeForSubject($subject->id);

        return view('app.my-subject-details', compact('subject', 'classmates', 'student', 'gradeData'));
    }

    /**
     * Show student's assignments list.
     */
    public function assignments()
    {
        if (!Auth::guard('student')->check()) {
            return redirect()->route('student.login');
        }

        $student = Auth::guard('student')->user();
        $submissions = $student->submissions()->with('activity')->get();

        // Use the Submission model for the query
        $activeActivities = $student->subjects()
            ->with(['activities' => function($query) use ($student) {
                $query->where('due_date', '>', now())
                    ->whereDoesntHave('submissions', function($q) use ($student) {
                        $q->where('student_id', $student->id);
                    });
            }])
            ->get()
            ->pluck('activities')
            ->flatten();

        // Get activities that have submissions but are not graded yet
        $pendingGradedActivities = $student->subjects()
            ->with(['activities' => function($query) use ($student) {
                $query->whereHas('submissions', function($q) use ($student) {
                    $q->where('student_id', $student->id)
                      ->whereNull('grade');
                });
            }])
            ->get()
            ->pluck('activities')
            ->flatten();

        // Merge the active activities with pending graded activities
        $activeActivities = $activeActivities->merge($pendingGradedActivities)->unique('id');

        return view('app.my-assignments', compact('submissions', 'activeActivities'));
    }

    /**
     * Show student's study materials.
     */
    public function materials()
    {
        if (!Auth::guard('student')->check()) {
            return redirect()->route('student.login');
        }

        $student = Auth::guard('student')->user();
        $materials = $student->subjects()
            ->with(['activities' => function($query) {
                $query->where('type', 'material')
                      ->where('is_published', true)
                      ->whereNotNull('attachment'); // Only get materials with attachments
            }])
            ->get()
            ->pluck('activities')
            ->flatten();

        return view('app.my-materials', compact('materials', 'student'));
    }

    /**
     * Show student's recent announcement alerts.
     */
    public function announcements()
    {
        if (!Auth::guard('student')->check()) {
            return redirect()->route('student.login');
        }

        $student = Auth::guard('student')->user();
        $announcements = $student->subjects()
            ->with(['activities' => function($query) {
                $query->where('type', 'announcement')
                      ->where('is_published', true)
                      ->orderBy('created_at', 'desc');
            }])
            ->get()
            ->pluck('activities')
            ->flatten();

        return view('app.my-announcements', compact('announcements'));
    }
}
