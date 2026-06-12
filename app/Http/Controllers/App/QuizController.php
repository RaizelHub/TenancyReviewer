<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    /**
     * Display a listing of quizzes for a student.
     */
    public function index()
    {
        $student = Auth::guard('student')->user();

        // Check if student has premium access
        if (!$student->hasPremiumAccess()) {
            return redirect()->route('student.plan')
                ->with('info', 'You need a Premium plan to access quizzes. Please upgrade your plan.');
        }

        // Get all subjects the student is enrolled in
        $subjects = $student->subjects;

        // Get all activities with quizzes from these subjects
        $activitiesWithQuizzes = collect();

        foreach ($subjects as $subject) {
            $activities = $subject->activities()
                ->where('type', 'material')
                ->where('is_published', true)
                ->whereHas('quiz', function($query) {
                    $query->where('is_published', true);
                })
                ->with('quiz')
                ->get();

            $activitiesWithQuizzes = $activitiesWithQuizzes->concat($activities);
        }

        // Get student's attempts for these quizzes
        $quizIds = $activitiesWithQuizzes->pluck('quiz.id')->filter();
        $attempts = QuizAttempt::where('student_id', $student->id)
            ->whereIn('quiz_id', $quizIds)
            ->get()
            ->groupBy('quiz_id');

        return view('app.quizzes.index', compact('activitiesWithQuizzes', 'attempts'));
    }

    /**
     * Display the specified quiz.
     */
    public function show($id)
    {
        $student = Auth::guard('student')->user();

        // Check if student has premium access
        if (!$student->hasPremiumAccess()) {
            return redirect()->route('student.plan')
                ->with('info', 'You need a Premium plan to access quizzes. Please upgrade your plan.');
        }

        $quiz = Quiz::with(['activity', 'questions' => function($query) {
            $query->orderBy('order');
        }])->findOrFail($id);

        // Check if the quiz is published
        if (!$quiz->is_published) {
            return redirect()->route('quizzes.index')
                ->with('error', 'This quiz is not available yet.');
        }

        // Check if the student is enrolled in the subject
        $subject = $quiz->activity->subject;
        if (!$student->subjects->contains($subject->id)) {
            return redirect()->route('quizzes.index')
                ->with('error', 'You are not enrolled in this subject.');
        }

        // Get previous attempts
        $attempts = QuizAttempt::where('student_id', $student->id)
            ->where('quiz_id', $quiz->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('app.quizzes.show', compact('quiz', 'attempts'));
    }

    /**
     * Start a new quiz attempt.
     */
    public function start($id)
    {
        $student = Auth::guard('student')->user();

        // Check if student has premium access
        if (!$student->hasPremiumAccess()) {
            return redirect()->route('student.plan')
                ->with('info', 'You need a Premium plan to access quizzes. Please upgrade your plan.');
        }

        $quiz = Quiz::findOrFail($id);

        // Check if the quiz is published
        if (!$quiz->is_published) {
            return redirect()->route('quizzes.index')
                ->with('error', 'This quiz is not available yet.');
        }

        // Create a new attempt
        $attempt = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'student_id' => $student->id,
            'started_at' => now(),
        ]);

        // Load questions in random order if specified
        $questions = $quiz->questions()
            ->when($quiz->randomize_questions, function($query) {
                return $query->inRandomOrder();
            })
            ->when(!$quiz->randomize_questions, function($query) {
                return $query->orderBy('order');
            })
            ->get();

        return view('app.quizzes.take', compact('quiz', 'attempt', 'questions'));
    }

    /**
     * Submit a quiz attempt.
     */
    public function submit(Request $request, $attemptId)
    {
        $student = Auth::guard('student')->user();
        $attempt = QuizAttempt::where('id', $attemptId)
            ->where('student_id', $student->id)
            ->firstOrFail();

        // Check if the attempt is already completed
        if ($attempt->isCompleted()) {
            return redirect()->route('quizzes.results', $attempt->id)
                ->with('error', 'This quiz attempt has already been submitted.');
        }

        // Get the quiz and questions
        $quiz = $attempt->quiz;
        $questions = $quiz->questions;

        // Process answers
        $answers = [];
        foreach ($questions as $question) {
            $questionId = $question->id;
            if ($request->has("answer_{$questionId}")) {
                $answers[$questionId] = $request->input("answer_{$questionId}");
            }
        }

        // Save answers and calculate score
        $attempt->answers = $answers;
        $attempt->completed_at = now();
        $attempt->calculateScore();
        $attempt->save();

        return redirect()->route('quizzes.results', $attempt->id);
    }

    /**
     * Display the results of a quiz attempt.
     */
    public function results($attemptId)
    {
        $student = Auth::guard('student')->user();
        $attempt = QuizAttempt::where('id', $attemptId)
            ->where('student_id', $student->id)
            ->with(['quiz.questions' => function($query) {
                $query->orderBy('order');
            }])
            ->firstOrFail();

        // Check if the attempt is completed
        if (!$attempt->isCompleted()) {
            return redirect()->route('quizzes.take', $attempt->quiz_id)
                ->with('error', 'This quiz attempt is not completed yet.');
        }

        $quiz = $attempt->quiz;

        return view('app.quizzes.results', compact('attempt', 'quiz'));
    }
}
