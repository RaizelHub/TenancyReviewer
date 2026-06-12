<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Student;
use App\Models\Activity;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;

class GradeReportController extends Controller
{
    /**
     * Generate a PDF report of student grades for a subject
     */
    public function generateReport(Request $request, Subject $subject)
    {
        // Get all students enrolled in this subject
        $students = $subject->students;

        if ($students->isEmpty()) {
            return back()->with('error', 'No students enrolled in this subject.');
        }

        // Calculate grades for all students
        $studentGrades = [];
        $topPerformers = [];
        $excellentStudents = [];
        $goodStudents = [];
        $lowPerformingStudents = [];
        $premiumStudents = [];
        $premiumQuizPerformance = [];

        foreach ($students as $student) {
            $gradeData = $student->calculateFinalGradeForSubject($subject->id);
            $studentGrades[$student->id] = $gradeData;

            // Categorize students based on their grades
            if ($gradeData['college_grade'] === '1.0' || $gradeData['college_grade'] === '1.25') {
                $excellentStudents[] = [
                    'student' => $student,
                    'grade' => $gradeData
                ];
            } elseif (in_array($gradeData['college_grade'], ['1.5', '1.75', '2.0'])) {
                $goodStudents[] = [
                    'student' => $student,
                    'grade' => $gradeData
                ];
            } elseif ($gradeData['college_grade'] === '5.0') {
                $lowPerformingStudents[] = [
                    'student' => $student,
                    'grade' => $gradeData
                ];
            }

            // Track premium students and their quiz performance
            if ($student->hasPremiumAccess()) {
                $premiumStudents[] = [
                    'student' => $student,
                    'grade' => $gradeData
                ];

                // Get quiz performance data for this premium student
                $quizPerformance = $this->getStudentQuizPerformance($student, $subject);
                if ($quizPerformance) {
                    $premiumQuizPerformance[] = [
                        'student' => $student,
                        'quiz_performance' => $quizPerformance
                    ];
                }
            }
        }

        // Sort students by grade (highest first) to find top performers
        usort($excellentStudents, function($a, $b) {
            return $b['grade']['grade'] <=> $a['grade']['grade'];
        });

        usort($goodStudents, function($a, $b) {
            return $b['grade']['grade'] <=> $a['grade']['grade'];
        });

        // Sort premium students by quiz performance (highest average score first)
        usort($premiumQuizPerformance, function($a, $b) {
            return $b['quiz_performance']['average_score'] <=> $a['quiz_performance']['average_score'];
        });

        // Get top 5 performers overall
        $allStudentsWithGrades = array_merge($excellentStudents, $goodStudents, $lowPerformingStudents);
        usort($allStudentsWithGrades, function($a, $b) {
            return $b['grade']['grade'] <=> $a['grade']['grade'];
        });

        $topPerformers = array_slice($allStudentsWithGrades, 0, 5);

        // Return the view with a flag to indicate it's for download
        return view('app.subjects.grade_report', [
            'subject' => $subject,
            'topPerformers' => $topPerformers,
            'excellentStudents' => $excellentStudents,
            'goodStudents' => $goodStudents,
            'lowPerformingStudents' => $lowPerformingStudents,
            'allStudents' => $allStudentsWithGrades,
            'premiumStudents' => $premiumStudents,
            'premiumQuizPerformance' => $premiumQuizPerformance,
            'generatedAt' => now()->format('F j, Y g:i A'),
            'downloadable' => true
        ]);
    }

    /**
     * Get quiz performance data for a student in a specific subject
     *
     * @param Student $student
     * @param Subject $subject
     * @return array|null
     */
    private function getStudentQuizPerformance(Student $student, Subject $subject)
    {
        // Get all activities with quizzes for this subject
        $quizActivities = Activity::where('subject_id', $subject->id)
            ->whereHas('quiz')
            ->with('quiz')
            ->get();

        if ($quizActivities->isEmpty()) {
            return null;
        }

        $quizIds = $quizActivities->pluck('quiz.id')->filter()->toArray();

        if (empty($quizIds)) {
            return null;
        }

        // Get all quiz attempts for this student
        $quizAttempts = QuizAttempt::where('student_id', $student->id)
            ->whereIn('quiz_id', $quizIds)
            ->get();

        if ($quizAttempts->isEmpty()) {
            return [
                'total_quizzes' => count($quizIds),
                'attempted_quizzes' => 0,
                'total_attempts' => 0,
                'average_score' => 0,
                'highest_score' => 0,
                'passed_quizzes' => 0,
                'completion_rate' => 0,
                'attempts' => []
            ];
        }

        // Calculate quiz performance metrics
        $attemptedQuizIds = $quizAttempts->pluck('quiz_id')->unique()->toArray();
        $totalAttempts = $quizAttempts->count();
        $averageScore = $quizAttempts->avg('percentage');
        $highestScore = $quizAttempts->max('percentage');
        $passedQuizzes = $quizAttempts->where('passed', true)->pluck('quiz_id')->unique()->count();
        $completionRate = (count($attemptedQuizIds) / count($quizIds)) * 100;

        // Get detailed attempt data
        $attemptDetails = [];
        foreach ($quizActivities as $activity) {
            if ($activity->quiz) {
                $latestAttempt = $quizAttempts->where('quiz_id', $activity->quiz->id)
                    ->sortByDesc('created_at')
                    ->first();

                if ($latestAttempt) {
                    $attemptDetails[] = [
                        'quiz_title' => $activity->quiz->title,
                        'score' => $latestAttempt->percentage,
                        'passed' => $latestAttempt->passed,
                        'attempts_count' => $quizAttempts->where('quiz_id', $activity->quiz->id)->count(),
                        'time_spent' => $latestAttempt->getTimeSpentAttribute(),
                        'completed_at' => $latestAttempt->completed_at
                    ];
                }
            }
        }

        return [
            'total_quizzes' => count($quizIds),
            'attempted_quizzes' => count($attemptedQuizIds),
            'total_attempts' => $totalAttempts,
            'average_score' => round($averageScore, 1),
            'highest_score' => round($highestScore, 1),
            'passed_quizzes' => $passedQuizzes,
            'completion_rate' => round($completionRate, 1),
            'attempts' => $attemptDetails
        ];
    }
}
