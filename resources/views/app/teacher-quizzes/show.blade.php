<x-tenant-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $quiz->title }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('teacher-quizzes.edit', $quiz->id) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Quiz
                </a>
                <a href="{{ route('teacher-quizzes.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                    </svg>
                    Back to Quizzes
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 min-h-[calc(100vh-10rem)]">
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <!-- Quiz Details -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Quiz Details</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $quiz->activity->subject->name }} - {{ $quiz->activity->title }}</p>
                        </div>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $quiz->is_published ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $quiz->is_published ? 'Published' : 'Draft' }}
                        </span>
                    </div>

                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Description</h4>
                            <p class="mt-1 text-gray-900 dark:text-white">{{ $quiz->description ?: 'No description provided.' }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Settings</h4>
                            <ul class="mt-1 space-y-1 text-gray-900 dark:text-white">
                                <li>
                                    <span class="text-gray-500 dark:text-gray-400">Time Limit:</span>
                                    {{ $quiz->time_limit ? $quiz->time_limit . ' minutes' : 'No time limit' }}
                                </li>
                                <li>
                                    <span class="text-gray-500 dark:text-gray-400">Passing Score:</span>
                                    {{ $quiz->passing_score }}%
                                </li>
                                <li>
                                    <span class="text-gray-500 dark:text-gray-400">Show Results:</span>
                                    {{ $quiz->show_results ? 'Yes' : 'No' }}
                                </li>
                                <li>
                                    <span class="text-gray-500 dark:text-gray-400">Randomize Questions:</span>
                                    {{ $quiz->randomize_questions ? 'Yes' : 'No' }}
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-between items-center">
                        <div>
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                Created: {{ $quiz->created_at->format('M d, Y') }}
                            </span>
                            @if($quiz->created_at != $quiz->updated_at)
                                <span class="text-sm text-gray-500 dark:text-gray-400 ml-4">
                                    Last Updated: {{ $quiz->updated_at->format('M d, Y') }}
                                </span>
                            @endif
                        </div>
                        <div class="flex space-x-2">
                            <form action="{{ route('teacher-quizzes.toggle-published', $quiz->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $quiz->is_published ? 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200' : 'bg-green-100 text-green-800 hover:bg-green-200' }}">
                                    @if($quiz->is_published)
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                        </svg>
                                        Unpublish
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Publish
                                    @endif
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Questions Section -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Questions ({{ $quiz->questions->count() }})</h3>
                        <a href="{{ route('teacher-quizzes.questions.create', $quiz->id) }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Add Questions
                        </a>
                    </div>

                    @if($quiz->questions->count() > 0)
                        <div class="space-y-6">
                            @foreach($quiz->questions as $index => $question)
                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <div class="flex items-center">
                                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full mr-2">
                                                    Q{{ $index + 1 }}
                                                </span>
                                                <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-0.5 rounded-full mr-2">
                                                    {{ ucfirst(str_replace('_', ' ', $question->type)) }}
                                                </span>
                                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                                    {{ $question->points }} {{ $question->points == 1 ? 'point' : 'points' }}
                                                </span>
                                            </div>
                                            <h4 class="text-gray-900 dark:text-white font-medium mt-2">{{ $question->question }}</h4>

                                            @if($question->type === 'multiple_choice' && is_array($question->options))
                                                <div class="mt-3 pl-4 space-y-1">
                                                    @foreach($question->options as $optionIndex => $option)
                                                        <div class="flex items-center">
                                                            <span class="w-5 h-5 flex items-center justify-center {{ in_array($optionIndex, $question->correct_answer) ? 'text-green-600' : 'text-gray-400' }}">
                                                                @if(in_array($optionIndex, $question->correct_answer))
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                                    </svg>
                                                                @else
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                                    </svg>
                                                                @endif
                                                            </span>
                                                            <span class="ml-2 text-gray-700 dark:text-gray-300">{{ $option }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @elseif($question->type === 'true_false')
                                                <div class="mt-3 pl-4">
                                                    <p class="text-gray-700 dark:text-gray-300">
                                                        Correct answer: <span class="font-medium">{{ $question->correct_answer[0] == 0 ? 'True' : 'False' }}</span>
                                                    </p>
                                                </div>
                                            @elseif($question->type === 'short_answer' && is_array($question->correct_answer))
                                                <div class="mt-3 pl-4">
                                                    <p class="text-gray-700 dark:text-gray-300">
                                                        Accepted answers: <span class="font-medium">{{ implode(', ', $question->correct_answer) }}</span>
                                                    </p>
                                                </div>
                                            @endif

                                            @if($question->explanation)
                                                <div class="mt-3 pl-4">
                                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                                        <span class="font-medium">Explanation:</span> {{ $question->explanation }}
                                                    </p>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex space-x-2 ml-4">
                                            <a href="{{ route('teacher-quizzes.questions.edit', [$quiz->id, $question->id]) }}" class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                            <form action="{{ route('teacher-quizzes.questions.destroy', [$quiz->id, $question->id]) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this question?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No questions yet</h3>
                            <p class="mt-1 text-gray-500 dark:text-gray-400">Start adding questions to your quiz.</p>
                            <div class="mt-6">
                                <a href="{{ route('teacher-quizzes.questions.create', $quiz->id) }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Add Questions
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-tenant-app-layout>
