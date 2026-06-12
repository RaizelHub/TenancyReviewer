<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TeacherQuizController extends Controller
{
    /**
     * Display a listing of quizzes.
     */
    public function index()
    {
        $quizzes = Quiz::with(['activity.subject'])->latest()->paginate(10);
        return view('app.teacher-quizzes.index', compact('quizzes'));
    }

    /**
     * Show the form for creating a new quiz.
     */
    public function create(Request $request)
    {
        $activityId = $request->query('activity_id');
        $activity = null;

        if ($activityId) {
            $activity = Activity::findOrFail($activityId);
            // Check if activity already has a quiz
            if ($activity->hasQuiz()) {
                return redirect()->route('teacher-quizzes.edit', $activity->quiz->id)
                    ->with('info', 'This activity already has a quiz. You can edit it here.');
            }
        }

        $activities = Activity::where('type', 'material')->whereDoesntHave('quiz')->get();
        return view('app.teacher-quizzes.create', compact('activities', 'activity'));
    }

    /**
     * Store a newly created quiz in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'activity_id' => 'required|exists:activities,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'time_limit' => 'nullable|integer|min:1|max:180',
            'passing_score' => 'required|integer|min:1|max:100',
            'is_published' => 'boolean',
            'show_results' => 'boolean',
            'randomize_questions' => 'boolean',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Check if activity already has a quiz
        $activity = Activity::findOrFail($request->activity_id);
        if ($activity->hasQuiz()) {
            return back()->withErrors(['activity_id' => 'This activity already has a quiz.'])->withInput();
        }

        // Create the quiz
        $quiz = Quiz::create([
            'activity_id' => $request->activity_id,
            'title' => $request->title,
            'description' => $request->description,
            'time_limit' => $request->time_limit,
            'passing_score' => $request->passing_score,
            'is_published' => $request->has('is_published'),
            'show_results' => $request->has('show_results'),
            'randomize_questions' => $request->has('randomize_questions'),
        ]);

        return redirect()->route('teacher-quizzes.questions.create', $quiz->id)
            ->with('success', 'Quiz created successfully. Now add some questions to your quiz.');
    }

    /**
     * Display the specified quiz.
     */
    public function show(Quiz $quiz)
    {
        $quiz->load(['activity.subject', 'questions' => function($query) {
            $query->orderBy('order');
        }]);

        return view('app.teacher-quizzes.show', compact('quiz'));
    }

    /**
     * Show the form for editing the specified quiz.
     */
    public function edit(Quiz $quiz)
    {
        $quiz->load('activity');
        return view('app.teacher-quizzes.edit', compact('quiz'));
    }

    /**
     * Update the specified quiz in storage.
     */
    public function update(Request $request, Quiz $quiz)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'time_limit' => 'nullable|integer|min:1|max:180',
            'passing_score' => 'required|integer|min:1|max:100',
            'is_published' => 'boolean',
            'show_results' => 'boolean',
            'randomize_questions' => 'boolean',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Update the quiz
        $quiz->update([
            'title' => $request->title,
            'description' => $request->description,
            'time_limit' => $request->time_limit,
            'passing_score' => $request->passing_score,
            'is_published' => $request->has('is_published'),
            'show_results' => $request->has('show_results'),
            'randomize_questions' => $request->has('randomize_questions'),
        ]);

        return redirect()->route('teacher-quizzes.show', $quiz->id)
            ->with('success', 'Quiz updated successfully.');
    }

    /**
     * Remove the specified quiz from storage.
     */
    public function destroy(Quiz $quiz)
    {
        // Delete the quiz and all associated questions and attempts
        $quiz->delete();

        return redirect()->route('teacher-quizzes.index')
            ->with('success', 'Quiz deleted successfully.');
    }

    /**
     * Show the form for creating a new question.
     */
    public function createQuestion(Quiz $quiz)
    {
        $quiz->load('activity');
        $nextOrder = $quiz->questions()->count() + 1;

        return view('app.teacher-quizzes.questions.create', compact('quiz', 'nextOrder'));
    }

    /**
     * Show the form for creating multiple questions at once.
     */
    public function createMultipleQuestions(Quiz $quiz)
    {
        $quiz->load('activity');

        return view('app.teacher-quizzes.questions.create-multiple', compact('quiz'));
    }

    /**
     * Store a newly created question in storage.
     */
    public function storeQuestion(Request $request, Quiz $quiz)
    {
        $validator = Validator::make($request->all(), [
            'question' => 'required|string',
            'type' => 'required|in:multiple_choice,true_false,short_answer',
            'options' => 'required_if:type,multiple_choice|array|min:2',
            'options.*' => 'required_if:type,multiple_choice|string',
            'correct_option' => 'required_if:type,multiple_choice',
            'correct_answer' => 'required_if:type,true_false,short_answer',
            'points' => 'required|integer|min:1',
            'explanation' => 'nullable|string',
            'order' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Process options and correct answers based on question type
        $options = null;
        $correctAnswer = [];

        if ($request->type === 'multiple_choice') {
            $options = $request->options;
            $correctAnswer = [$request->correct_option]; // Store as array with the selected option index
        } elseif ($request->type === 'true_false') {
            $options = ['True', 'False'];
            $correctAnswer = [$request->correct_answer === 'true' ? 0 : 1];
        } elseif ($request->type === 'short_answer') {
            $correctAnswer = [$request->correct_answer]; // Store as array with the correct answer
        }

        // Create the question
        $question = QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'question' => $request->question,
            'type' => $request->type,
            'options' => $options,
            'correct_answer' => $correctAnswer,
            'points' => $request->points,
            'explanation' => $request->explanation,
            'order' => $request->order,
        ]);

        if ($request->has('add_another')) {
            return redirect()->route('teacher-quizzes.questions.create', $quiz->id)
                ->with('success', 'Question added successfully. Add another question.');
        }

        return redirect()->route('teacher-quizzes.show', $quiz->id)
            ->with('success', 'Question added successfully.');
    }

    /**
     * Show the form for editing a question.
     */
    public function editQuestion(Quiz $quiz, QuizQuestion $question)
    {
        if ($question->quiz_id !== $quiz->id) {
            abort(404);
        }

        return view('app.teacher-quizzes.questions.edit', compact('quiz', 'question'));
    }

    /**
     * Update the specified question in storage.
     */
    public function updateQuestion(Request $request, Quiz $quiz, QuizQuestion $question)
    {
        if ($question->quiz_id !== $quiz->id) {
            abort(404);
        }

        $validator = Validator::make($request->all(), [
            'question' => 'required|string',
            'type' => 'required|in:multiple_choice,true_false,short_answer',
            'options' => 'required_if:type,multiple_choice|array|min:2',
            'options.*' => 'required_if:type,multiple_choice|string',
            'correct_option' => 'required_if:type,multiple_choice',
            'correct_answer' => 'required_if:type,true_false,short_answer',
            'points' => 'required|integer|min:1',
            'explanation' => 'nullable|string',
            'order' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Process options and correct answers based on question type
        $options = null;
        $correctAnswer = [];

        if ($request->type === 'multiple_choice') {
            $options = $request->options;
            $correctAnswer = [$request->correct_option]; // Store as array with the selected option index
        } elseif ($request->type === 'true_false') {
            $options = ['True', 'False'];
            $correctAnswer = [$request->correct_answer === 'true' ? 0 : 1];
        } elseif ($request->type === 'short_answer') {
            $correctAnswer = [$request->correct_answer]; // Store as array with the correct answer
        }

        // Update the question
        $question->update([
            'question' => $request->question,
            'type' => $request->type,
            'options' => $options,
            'correct_answer' => $correctAnswer,
            'points' => $request->points,
            'explanation' => $request->explanation,
            'order' => $request->order,
        ]);

        return redirect()->route('teacher-quizzes.show', $quiz->id)
            ->with('success', 'Question updated successfully.');
    }

    /**
     * Remove the specified question from storage.
     */
    public function destroyQuestion(Quiz $quiz, QuizQuestion $question)
    {
        if ($question->quiz_id !== $quiz->id) {
            abort(404);
        }

        $question->delete();

        return redirect()->route('teacher-quizzes.show', $quiz->id)
            ->with('success', 'Question deleted successfully.');
    }

    /**
     * Store multiple questions at once.
     */
    public function storeMultipleQuestions(Request $request, Quiz $quiz)
    {
        $questions = $request->input('questions', []);

        if (empty($questions)) {
            return back()->with('error', 'No questions were submitted.');
        }

        $nextOrder = $quiz->questions()->count() + 1;
        $successCount = 0;

        foreach ($questions as $index => $questionData) {
            // Skip empty questions
            if (empty($questionData['question'])) {
                continue;
            }

            // Process options and correct answers based on question type
            $options = null;
            $correctAnswer = [];

            if ($questionData['type'] === 'multiple_choice') {
                $options = $questionData['options'] ?? [];
                $correctAnswer = isset($questionData['correct_option']) ? [$questionData['correct_option']] : [0];
            } elseif ($questionData['type'] === 'true_false') {
                $options = ['True', 'False'];
                $correctAnswer = [($questionData['correct_answer'] ?? 'true') === 'true' ? 0 : 1];
            } elseif ($questionData['type'] === 'short_answer') {
                $correctAnswer = [($questionData['correct_answer'] ?? '')];
            }

            // Create the question
            QuizQuestion::create([
                'quiz_id' => $quiz->id,
                'question' => $questionData['question'],
                'type' => $questionData['type'],
                'options' => $options,
                'correct_answer' => $correctAnswer,
                'points' => $questionData['points'] ?? 1,
                'explanation' => null,
                'order' => $nextOrder++,
            ]);

            $successCount++;
        }

        if ($successCount > 0) {
            return redirect()->route('teacher-quizzes.show', $quiz->id)
                ->with('success', "{$successCount} questions added successfully.");
        } else {
            return back()->with('error', 'No valid questions were submitted. Please check your input.');
        }
    }

    /**
     * Toggle the published status of a quiz.
     */
    public function togglePublished(Quiz $quiz)
    {
        $quiz->update(['is_published' => !$quiz->is_published]);

        $status = $quiz->is_published ? 'published' : 'unpublished';
        return back()->with('success', "Quiz {$status} successfully.");
    }
}
