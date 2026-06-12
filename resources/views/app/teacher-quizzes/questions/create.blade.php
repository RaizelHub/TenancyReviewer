<x-tenant-app-layout>
    <div class="container mx-auto px-4 py-6">
        <div class="max-w-3xl mx-auto min-h-[calc(100vh-10rem)]">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Add Question</h1>
                <a href="{{ route('teacher-quizzes.show', $quiz) }}"
                   class="text-indigo-600 hover:text-indigo-800">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Quiz
                </a>
            </div>

            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <form action="{{ route('teacher-quizzes.questions.store', $quiz) }}" method="POST" class="p-6">
                    @csrf

                    <div class="mb-6">
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Question Type</label>
                        <select name="type" id="type"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required>
                            <option value="">Select a type</option>
                            <option value="multiple_choice" {{ old('type') == 'multiple_choice' ? 'selected' : '' }}>Multiple Choice</option>
                            <option value="true_false" {{ old('type') == 'true_false' ? 'selected' : '' }}>True/False</option>
                            <option value="short_answer" {{ old('type') == 'short_answer' ? 'selected' : '' }}>Short Answer</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="question" class="block text-sm font-medium text-gray-700 mb-2">Question Text</label>
                        <textarea name="question" id="question" rows="3"
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                  required>{{ old('question') }}</textarea>
                        @error('question')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="points" class="block text-sm font-medium text-gray-700 mb-2">Points</label>
                        <input type="number" name="points" id="points" min="1" max="100"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               value="{{ old('points', 1) }}" required>
                        @error('points')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="multiple_choice_options" class="mb-6 hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Options</label>
                        <div id="optionsContainer" class="space-y-4">
                            @if(old('options'))
                                @foreach(old('options') as $i => $option)
                                    <div class="flex items-center space-x-4">
                                        <input type="text" name="options[]"
                                               class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                               placeholder="Option {{ $i + 1 }}" value="{{ $option }}">
                                        <div class="flex items-center">
                                            <input type="radio" name="correct_option" value="{{ $i }}"
                                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300"
                                                   {{ old('correct_option') == $i ? 'checked' : '' }}>
                                            <label class="ml-2 text-sm text-gray-700">Correct</label>
                                        </div>
                                        @if($i > 1)
                                            <button type="button" class="text-red-600 hover:text-red-900 removeOption">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                @for($i = 0; $i < 2; $i++)
                                    <div class="flex items-center space-x-4">
                                        <input type="text" name="options[]"
                                               class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                               placeholder="Option {{ $i + 1 }}" value="">
                                        <div class="flex items-center">
                                            <input type="radio" name="correct_option" value="{{ $i }}"
                                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300"
                                                   {{ $i == 0 ? 'checked' : '' }}>
                                            <label class="ml-2 text-sm text-gray-700">Correct</label>
                                        </div>
                                    </div>
                                @endfor
                            @endif
                        </div>
                        <button type="button" id="addOption" class="mt-2 text-sm text-indigo-600 hover:text-indigo-800">
                            <i class="fas fa-plus mr-1"></i> Add Option
                        </button>
                        @error('options')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @error('correct_option')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="true_false_options" class="mb-6 hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Correct Answer</label>
                        <div class="space-y-4">
                            <div class="flex items-center space-x-4">
                                <input type="radio" name="correct_answer" value="true"
                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300"
                                       {{ old('correct_answer') == 'true' ? 'checked' : '' }}>
                                <label class="text-sm text-gray-700">True</label>
                            </div>
                            <div class="flex items-center space-x-4">
                                <input type="radio" name="correct_answer" value="false"
                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300"
                                       {{ old('correct_answer') == 'false' ? 'checked' : '' }}>
                                <label class="text-sm text-gray-700">False</label>
                            </div>
                        </div>
                        @error('correct_answer')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="short_answer_options" class="mb-6 hidden">
                        <label for="correct_answer" class="block text-sm font-medium text-gray-700 mb-2">Correct Answer</label>
                        <input type="text" name="correct_answer" id="correct_answer"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               value="{{ old('correct_answer') }}">
                        @error('correct_answer')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Order -->
                    <div class="mb-6">
                        <label for="order" class="block text-sm font-medium text-gray-700 mb-2">Question Order</label>
                        <input type="number" name="order" id="order" min="1"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               value="{{ old('order', $nextOrder ?? 1) }}" required>
                        @error('order')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Explanation (Optional) -->
                    <div class="mb-6">
                        <label for="explanation" class="block text-sm font-medium text-gray-700 mb-2">Explanation (Optional)</label>
                        <textarea name="explanation" id="explanation" rows="2"
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('explanation') }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">Provide an explanation that will be shown to students after they answer.</p>
                        @error('explanation')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="submit" name="add_another" value="1" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition-colors">
                            Add & Create Another
                        </button>
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition-colors">
                            Add Question
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const questionType = document.getElementById('type');
            const multipleChoiceOptions = document.getElementById('multiple_choice_options');
            const trueFalseOptions = document.getElementById('true_false_options');
            const shortAnswerOptions = document.getElementById('short_answer_options');

            function updateOptionsVisibility() {
                // Hide all option sections first
                multipleChoiceOptions.classList.add('hidden');
                trueFalseOptions.classList.add('hidden');
                shortAnswerOptions.classList.add('hidden');

                // Show the selected option section
                switch(questionType.value) {
                    case 'multiple_choice':
                        multipleChoiceOptions.classList.remove('hidden');
                        break;
                    case 'true_false':
                        trueFalseOptions.classList.remove('hidden');
                        break;
                    case 'short_answer':
                        shortAnswerOptions.classList.remove('hidden');
                        break;
                }
            }

            // Update visibility when question type changes
            questionType.addEventListener('change', updateOptionsVisibility);

            // Initial visibility update
            updateOptionsVisibility();
        });
    </script>
    @endpush
</x-tenant-app-layout>