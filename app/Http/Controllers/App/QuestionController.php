<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\QuestionOption;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    public function index(Quiz $quiz)
    {
        $questions = $quiz->questions()->with('options')->get();
        return view('app.teacher-quizzes.questions.index', compact('quiz', 'questions'));
    }

    public function create(Quiz $quiz)
    {
        return view('app.teacher-quizzes.questions.create', compact('quiz'));
    }

    public function store(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'question_type' => 'required|in:multiple_choice,true_false,short_answer',
            'question_text' => 'required|string|max:1000',
            'points' => 'required|integer|min:1|max:100',
            'options' => 'required_if:question_type,multiple_choice|array|min:2',
            'options.*' => 'required_if:question_type,multiple_choice|string|max:255',
            'correct_option' => 'required_if:question_type,multiple_choice|integer|min:0|max:3',
            'correct_answer' => 'required_if:question_type,true_false,short_answer|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $question = $quiz->questions()->create([
                'question_type' => $validated['question_type'],
                'question_text' => $validated['question_text'],
                'points' => $validated['points'],
                'correct_answer' => $validated['question_type'] !== 'multiple_choice' ? $validated['correct_answer'] : null,
            ]);

            if ($validated['question_type'] === 'multiple_choice') {
                foreach ($validated['options'] as $index => $optionText) {
                    $question->options()->create([
                        'option_text' => $optionText,
                        'is_correct' => $index === (int)$validated['correct_option'],
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('teacher.quizzes.questions.index', $quiz)
                ->with('success', 'Question added successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to add question. Please try again.');
        }
    }

    public function show(Quiz $quiz, Question $question)
    {
        $question->load('options');
        return view('app.teacher-quizzes.questions.show', compact('quiz', 'question'));
    }

    public function edit(Quiz $quiz, Question $question)
    {
        $question->load('options');
        return view('app.teacher-quizzes.questions.edit', compact('quiz', 'question'));
    }

    public function update(Request $request, Quiz $quiz, Question $question)
    {
        $validated = $request->validate([
            'question_type' => 'required|in:multiple_choice,true_false,short_answer',
            'question_text' => 'required|string|max:1000',
            'points' => 'required|integer|min:1|max:100',
            'options' => 'required_if:question_type,multiple_choice|array|min:2',
            'options.*' => 'required_if:question_type,multiple_choice|string|max:255',
            'correct_option' => 'required_if:question_type,multiple_choice|integer|min:0|max:3',
            'correct_answer' => 'required_if:question_type,true_false,short_answer|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $question->update([
                'question_type' => $validated['question_type'],
                'question_text' => $validated['question_text'],
                'points' => $validated['points'],
                'correct_answer' => $validated['question_type'] !== 'multiple_choice' ? $validated['correct_answer'] : null,
            ]);

            if ($validated['question_type'] === 'multiple_choice') {
                // Delete existing options
                $question->options()->delete();

                // Create new options
                foreach ($validated['options'] as $index => $optionText) {
                    $question->options()->create([
                        'option_text' => $optionText,
                        'is_correct' => $index === (int)$validated['correct_option'],
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('teacher.quizzes.questions.index', $quiz)
                ->with('success', 'Question updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update question. Please try again.');
        }
    }

    public function destroy(Quiz $quiz, Question $question)
    {
        try {
            $question->delete();
            return redirect()
                ->route('teacher.quizzes.questions.index', $quiz)
                ->with('success', 'Question deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete question. Please try again.');
        }
    }
} 