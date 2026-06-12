<x-tenant-app-layout>
    <div class="container mx-auto px-4 py-6">
        <div class="max-w-3xl mx-auto min-h-[calc(100vh-10rem)]">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Question Details</h1>
                <div class="flex space-x-4">
                    <a href="{{ route('teacher.quizzes.questions.edit', [$quiz, $question]) }}"
                       class="text-indigo-600 hover:text-indigo-800">
                        <i class="fas fa-edit mr-2"></i>Edit Question
                    </a>
                    <a href="{{ route('teacher.quizzes.questions.index', $quiz) }}"
                       class="text-gray-600 hover:text-gray-800">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Questions
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-6">
                    <div class="mb-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-2">Question Text</h2>
                        <p class="text-gray-700">{{ $question->question_text }}</p>
                    </div>

                    <div class="mb-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-2">Question Type</h2>
                        <p class="text-gray-700">{{ ucfirst(str_replace('_', ' ', $question->question_type)) }}</p>
                    </div>

                    <div class="mb-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-2">Points</h2>
                        <p class="text-gray-700">{{ $question->points }}</p>
                    </div>

                    @if($question->question_type === 'multiple_choice')
                        <div class="mb-6">
                            <h2 class="text-lg font-medium text-gray-900 mb-2">Options</h2>
                            <div class="space-y-2">
                                @foreach($question->options as $option)
                                    <div class="flex items-center space-x-2">
                                        <span class="text-gray-700">{{ $option->option_text }}</span>
                                        @if($option->is_correct)
                                            <span class="text-green-600 text-sm">(Correct Answer)</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @elseif($question->question_type === 'true_false')
                        <div class="mb-6">
                            <h2 class="text-lg font-medium text-gray-900 mb-2">Correct Answer</h2>
                            <p class="text-gray-700">{{ ucfirst($question->correct_answer) }}</p>
                        </div>
                    @elseif($question->question_type === 'short_answer')
                        <div class="mb-6">
                            <h2 class="text-lg font-medium text-gray-900 mb-2">Correct Answer</h2>
                            <p class="text-gray-700">{{ $question->correct_answer }}</p>
                        </div>
                    @endif

                    <div class="flex justify-end space-x-4">
                        <form action="{{ route('teacher.quizzes.questions.destroy', [$quiz, $question]) }}"
                              method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900"
                                    onclick="return confirm('Are you sure you want to delete this question?')">
                                <i class="fas fa-trash mr-2"></i>Delete Question
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-tenant-app-layout>