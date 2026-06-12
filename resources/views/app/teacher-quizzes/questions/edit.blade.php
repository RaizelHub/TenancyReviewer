<x-tenant-app-layout>
    <div class="container mx-auto px-4 py-6">
        <div class="max-w-3xl mx-auto min-h-[calc(100vh-10rem)]">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Edit Question</h1>
                <a href="{{ route('teacher-quizzes.show', $quiz) }}"
                   class="text-indigo-600 hover:text-indigo-800">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Quiz
                </a>
            </div>

            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <form action="{{ route('teacher-quizzes.questions.update', [$quiz->id, $question->id]) }}" method="POST" class="p-6">
                    @csrf
                    @method('PUT')

                    <div class="mb-6">
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Question Type</label>
                        <select name="type" id="type"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required>
                            <option value="multiple_choice" {{ old('type', $question->type) == 'multiple_choice' ? 'selected' : '' }}>Multiple Choice</option>
                            <option value="true_false" {{ old('type', $question->type) == 'true_false' ? 'selected' : '' }}>True/False</option>
                            <option value="short_answer" {{ old('type', $question->type) == 'short_answer' ? 'selected' : '' }}>Short Answer</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="question" class="block text-sm font-medium text-gray-700 mb-2">Question Text</label>
                        <textarea name="question" id="question" rows="3"
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                  required>{{ old('question', $question->question) }}</textarea>
                        @error('question')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="points" class="block text-sm font-medium text-gray-700 mb-2">Points</label>
                        <input type="number" name="points" id="points" min="1" max="100"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               value="{{ old('points', $question->points) }}" required>
                        @error('points')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="multiple_choice_options" class="mb-6 {{ $question->type === 'multiple_choice' ? '' : 'hidden' }}">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Options</label>
                        <div id="optionsContainer" class="space-y-4">
                            @if($question->type == 'multiple_choice' && is_array($question->options))
                                @foreach($question->options as $index => $option)
                                    <div class="flex items-center space-x-4">
                                        <input type="text" name="options[]"
                                               class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                               placeholder="Option {{ $index + 1 }}"
                                               value="{{ old('options.' . $index, $option) }}">
                                        <div class="flex items-center">
                                            <input type="radio" name="correct_option" value="{{ $index }}"
                                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300"
                                                   {{ old('correct_option', $question->correct_answer[0] ?? '') == $index ? 'checked' : '' }}>
                                            <label class="ml-2 text-sm text-gray-700">Correct</label>
                                        </div>
                                        @if($index > 1)
                                            <button type="button" class="text-red-600 hover:text-red-900 removeOption">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <div class="flex items-center space-x-4">
                                    <input type="text" name="options[]"
                                           class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                           placeholder="Option 1" value="{{ old('options.0') }}">
                                    <div class="flex items-center">
                                        <input type="radio" name="correct_option" value="0"
                                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300"
                                               {{ old('correct_option') == '0' ? 'checked' : '' }}>
                                        <label class="ml-2 text-sm text-gray-700">Correct</label>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <input type="text" name="options[]"
                                           class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                           placeholder="Option 2" value="{{ old('options.1') }}">
                                    <div class="flex items-center">
                                        <input type="radio" name="correct_option" value="1"
                                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300"
                                               {{ old('correct_option') == '1' ? 'checked' : '' }}>
                                        <label class="ml-2 text-sm text-gray-700">Correct</label>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <button type="button" id="addOption" class="mt-2 text-sm text-indigo-600 hover:text-indigo-800">
                            <i class="fas fa-plus mr-1"></i> Add Option
                        </button>
                        @error('options')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @error('correct_answer')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="true_false_options" class="mb-6 {{ $question->type === 'true_false' ? '' : 'hidden' }}">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Correct Answer</label>
                        <div class="space-y-4">
                            <div class="flex items-center space-x-4">
                                <input type="radio" name="correct_answer[]" value="true"
                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300"
                                       {{ old('correct_answer.0', $question->type == 'true_false' && isset($question->correct_answer[0]) && $question->correct_answer[0] == 0 ? 'true' : '') == 'true' ? 'checked' : '' }}>
                                <label class="text-sm text-gray-700">True</label>
                            </div>
                            <div class="flex items-center space-x-4">
                                <input type="radio" name="correct_answer[]" value="false"
                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300"
                                       {{ old('correct_answer.0', $question->type == 'true_false' && isset($question->correct_answer[0]) && $question->correct_answer[0] == 1 ? 'false' : '') == 'false' ? 'checked' : '' }}>
                                <label class="text-sm text-gray-700">False</label>
                            </div>
                        </div>
                        @error('correct_answer')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="short_answer_options" class="mb-6 {{ $question->type === 'short_answer' ? '' : 'hidden' }}">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Accepted Answers</label>
                        <div id="shortAnswerContainer" class="space-y-4">
                            @if($question->type == 'short_answer' && is_array($question->correct_answer))
                                @foreach($question->correct_answer as $index => $answer)
                                    <div class="flex items-center space-x-4">
                                        <input type="text" name="correct_answer[]"
                                               class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                               placeholder="Accepted answer" value="{{ old('correct_answer.' . $index, $answer) }}">
                                        @if($index > 0)
                                            <button type="button" class="text-red-600 hover:text-red-900 removeAnswer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <div class="flex items-center space-x-4">
                                    <input type="text" name="correct_answer[]"
                                           class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                           placeholder="Accepted answer" value="{{ old('correct_answer.0') }}">
                                </div>
                            @endif
                        </div>
                        <button type="button" id="addAnswer" class="mt-2 text-sm text-indigo-600 hover:text-indigo-800">
                            <i class="fas fa-plus mr-1"></i> Add Another Accepted Answer
                        </button>
                        @error('correct_answer')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Order -->
                    <div class="mb-6">
                        <label for="order" class="block text-sm font-medium text-gray-700 mb-2">Question Order</label>
                        <input type="number" name="order" id="order" min="1"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               value="{{ old('order', $question->order) }}" required>
                        @error('order')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Explanation (Optional) -->
                    <div class="mb-6">
                        <label for="explanation" class="block text-sm font-medium text-gray-700 mb-2">Explanation (Optional)</label>
                        <textarea name="explanation" id="explanation" rows="2"
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('explanation', $question->explanation) }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">Provide an explanation that will be shown to students after they answer.</p>
                        @error('explanation')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition-colors">
                            Update Question
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
            const addOptionBtn = document.getElementById('addOption');
            const optionsContainer = document.getElementById('optionsContainer');
            const addAnswerBtn = document.getElementById('addAnswer');
            const shortAnswerContainer = document.getElementById('shortAnswerContainer');

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

            // Add multiple choice option
            if (addOptionBtn) {
                addOptionBtn.addEventListener('click', function() {
                    const optionCount = optionsContainer.children.length;
                    const newOption = document.createElement('div');
                    newOption.className = 'flex items-center space-x-4';
                    newOption.innerHTML = `
                        <input type="text" name="options[]"
                               class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               placeholder="Option ${optionCount + 1}" required>
                        <div class="flex items-center">
                            <input type="radio" name="correct_option" value="${optionCount}"
                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                            <label class="ml-2 text-sm text-gray-700">Correct</label>
                        </div>
                        <button type="button" class="text-red-600 hover:text-red-900 removeOption">
                            <i class="fas fa-trash"></i>
                        </button>
                    `;
                    optionsContainer.appendChild(newOption);
                });
            }

            // Remove multiple choice option
            if (optionsContainer) {
                optionsContainer.addEventListener('click', function(e) {
                    if (e.target.classList.contains('removeOption') || e.target.closest('.removeOption')) {
                        const button = e.target.classList.contains('removeOption') ? e.target : e.target.closest('.removeOption');
                        const optionDiv = button.closest('div');

                        if (optionsContainer.children.length > 2) {
                            optionDiv.remove();

                            // Update the value attributes of the remaining radio buttons
                            const radioButtons = optionsContainer.querySelectorAll('input[type="radio"]');
                            radioButtons.forEach((radio, index) => {
                                radio.value = index;
                            });
                        } else {
                            alert('You must have at least 2 options for a multiple choice question.');
                        }
                    }
                });
            }

            // Add short answer option
            if (addAnswerBtn) {
                addAnswerBtn.addEventListener('click', function() {
                    const newAnswer = document.createElement('div');
                    newAnswer.className = 'flex items-center space-x-4';
                    newAnswer.innerHTML = `
                        <input type="text" name="correct_answer[]"
                               class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               placeholder="Accepted answer" required>
                        <button type="button" class="text-red-600 hover:text-red-900 removeAnswer">
                            <i class="fas fa-trash"></i>
                        </button>
                    `;
                    shortAnswerContainer.appendChild(newAnswer);
                });
            }

            // Remove short answer option
            if (shortAnswerContainer) {
                shortAnswerContainer.addEventListener('click', function(e) {
                    if (e.target.classList.contains('removeAnswer') || e.target.closest('.removeAnswer')) {
                        const button = e.target.classList.contains('removeAnswer') ? e.target : e.target.closest('.removeAnswer');
                        const answerDiv = button.closest('div');

                        if (shortAnswerContainer.children.length > 1) {
                            answerDiv.remove();
                        } else {
                            alert('You must have at least 1 accepted answer.');
                        }
                    }
                });
            }

            // Update visibility when question type changes
            questionType.addEventListener('change', updateOptionsVisibility);

            // Initial visibility update
            updateOptionsVisibility();
        });
    </script>
    @endpush
</x-tenant-app-layout>