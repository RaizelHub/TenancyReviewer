<x-tenant-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Quiz Results
            </h2>
            <div class="flex items-center">
                <span class="text-sm {{ Auth::guard('student')->user()->plan === 'premium' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }} px-3 py-1 rounded-full">
                    {{ ucfirst(Auth::guard('student')->user()->plan) }} Plan
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 min-h-[calc(100vh-10rem)]">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $quiz->title }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $quiz->activity->subject->name }}</p>
                        </div>
                        <div class="flex space-x-4">
                            <a href="{{ route('quizzes.show', $quiz->id) }}" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                Back to Quiz
                            </a>
                            <a href="{{ route('quizzes.index') }}" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                All Quizzes
                            </a>
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg mb-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            <div class="flex flex-col items-center justify-center p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                                <div class="text-3xl font-bold {{ $attempt->passed ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                    {{ number_format($attempt->percentage, 1) }}%
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">Your Score</div>
                            </div>

                            <div class="flex flex-col items-center justify-center p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                                <div class="text-3xl font-bold text-gray-900 dark:text-white">
                                    {{ $attempt->score }} / {{ $quiz->total_points }}
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">Points</div>
                            </div>

                            <div class="flex flex-col items-center justify-center p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                                <div class="text-3xl font-bold {{ $attempt->passed ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                    {{ $attempt->passed ? 'Passed' : 'Failed' }}
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">Result</div>
                            </div>

                            <div class="flex flex-col items-center justify-center p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                                <div class="text-3xl font-bold text-gray-900 dark:text-white">
                                    @if($attempt->time_spent)
                                        {{ $attempt->time_spent }}
                                    @else
                                        -
                                    @endif
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">Minutes Spent</div>
                            </div>
                        </div>
                    </div>

                    @if($quiz->show_results)
                        <div class="mb-6">
                            <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4">Question Review</h4>

                            <div class="space-y-6">
                                @foreach($quiz->questions as $index => $question)
                                    @php
                                        $userAnswer = isset($attempt->answers[$question->id]) ? $attempt->answers[$question->id] : null;
                                        $isCorrect = $userAnswer !== null && $question->isCorrect($userAnswer);
                                    @endphp

                                    <div class="bg-white dark:bg-gray-700 border {{ $isCorrect ? 'border-green-200 dark:border-green-800' : 'border-red-200 dark:border-red-800' }} rounded-lg p-4">
                                        <div class="flex justify-between items-start mb-3">
                                            <h5 class="text-md font-medium text-gray-900 dark:text-white">
                                                Question {{ $index + 1 }}: {{ $question->question }}
                                            </h5>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $isCorrect ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100' }}">
                                                {{ $isCorrect ? 'Correct' : 'Incorrect' }}
                                            </span>
                                        </div>

                                        <div class="mb-3">
                                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Your Answer:</p>
                                            <div class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                                @if($userAnswer === null)
                                                    <span class="italic">No answer provided</span>
                                                @elseif($question->type === 'multiple_choice')
                                                    {{ isset($question->options[$userAnswer]) ? $question->options[$userAnswer] : 'Invalid option' }}
                                                @elseif($question->type === 'true_false')
                                                    {{ $userAnswer === 'true' ? 'True' : 'False' }}
                                                @else
                                                    {{ $userAnswer }}
                                                @endif
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Correct Answer:</p>
                                            <div class="mt-1 text-sm text-green-600 dark:text-green-400">
                                                @if($question->type === 'multiple_choice')
                                                    @foreach($question->correct_answer as $correctOption)
                                                        {{ isset($question->options[$correctOption]) ? $question->options[$correctOption] : 'Invalid option' }}{{ !$loop->last ? ', ' : '' }}
                                                    @endforeach
                                                @elseif($question->type === 'true_false')
                                                    {{ $question->correct_answer[0] === 'true' ? 'True' : 'False' }}
                                                @else
                                                    {{ $question->correct_answer[0] }}
                                                @endif
                                            </div>
                                        </div>

                                        @if($question->explanation)
                                            <div>
                                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Explanation:</p>
                                                <div class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                                    {{ $question->explanation }}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="bg-blue-50 dark:bg-blue-900 p-4 rounded-lg mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-blue-700 dark:text-blue-300">
                                        Detailed results for this quiz are not available. Please contact your instructor if you have any questions.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="flex justify-center mt-6">
                        <a href="{{ route('quizzes.start', $quiz->id) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-purple-600 rounded-lg hover:bg-purple-700 focus:ring-4 focus:outline-none focus:ring-purple-300">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Retake Quiz
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-tenant-app-layout>
