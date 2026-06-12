<x-tenant-app-layout>
    <div class="container mx-auto px-4 py-6">
        <div class="max-w-5xl mx-auto min-h-[calc(100vh-10rem)]">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Add Questions</h1>
                <a href="{{ route('teacher-quizzes.show', $quiz) }}"
                   class="text-indigo-600 hover:text-indigo-800">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Quiz
                </a>
            </div>

            <div class="bg-white rounded-lg shadow-md overflow-hidden p-6">
                <form id="multipleQuestionsForm" action="{{ route('teacher-quizzes.questions.store', $quiz) }}" method="POST">
                    @csrf

                    <div id="questionsContainer">
                        <!-- Question template will be added here -->
                        <div class="question-block mb-8 p-6 border border-gray-200 rounded-lg relative">
                            <button type="button" class="absolute top-2 right-2 text-gray-400 hover:text-red-600 delete-question hidden">
                                <i class="fas fa-trash"></i>
                            </button>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Question Type</label>
                                <select name="questions[0][type]" class="question-type w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="">Select a type</option>
                                    <option value="multiple_choice">Multiple Choice</option>
                                    <option value="true_false">True/False</option>
                                    <option value="short_answer">Short Answer</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Question Text</label>
                                <textarea name="questions[0][question]" rows="2" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required></textarea>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Points</label>
                                <input type="number" name="questions[0][points]" min="1" max="100" value="1" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            </div>

                            <!-- Multiple Choice Options -->
                            <div class="multiple-choice-options hidden">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Options</label>
                                <div class="options-container space-y-2">
                                    <div class="flex items-center space-x-2">
                                        <input type="text" name="questions[0][options][]" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Option 1">
                                        <div class="flex items-center">
                                            <input type="radio" name="questions[0][correct_option]" value="0" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300" checked>
                                            <label class="ml-2 text-sm text-gray-700">Correct</label>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <input type="text" name="questions[0][options][]" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Option 2">
                                        <div class="flex items-center">
                                            <input type="radio" name="questions[0][correct_option]" value="1" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                            <label class="ml-2 text-sm text-gray-700">Correct</label>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="add-option mt-2 text-sm text-indigo-600 hover:text-indigo-800">
                                    <i class="fas fa-plus mr-1"></i> Add Option
                                </button>
                            </div>

                            <!-- True/False Options -->
                            <div class="true-false-options hidden">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Correct Answer</label>
                                <div class="space-y-2">
                                    <div class="flex items-center space-x-2">
                                        <input type="radio" name="questions[0][correct_answer]" value="true" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300" checked>
                                        <label class="text-sm text-gray-700">True</label>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <input type="radio" name="questions[0][correct_answer]" value="false" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                        <label class="text-sm text-gray-700">False</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Short Answer Options -->
                            <div class="short-answer-options hidden">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Correct Answer</label>
                                <input type="text" name="questions[0][correct_answer]" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between mt-6">
                        <button type="button" id="addQuestion" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition-colors">
                            <i class="fas fa-plus mr-2"></i>Add Another Question
                        </button>

                        <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 transition-colors">
                            Save All Questions
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const questionsContainer = document.getElementById('questionsContainer');
            const addQuestionBtn = document.getElementById('addQuestion');
            let questionCount = 1;

            // Add new question
            addQuestionBtn.addEventListener('click', function() {
                const questionBlock = questionsContainer.querySelector('.question-block').cloneNode(true);

                // Update all name attributes with new index
                const inputs = questionBlock.querySelectorAll('input, textarea, select');
                inputs.forEach(input => {
                    if (input.name) {
                        input.name = input.name.replace(/questions\[0\]/g, `questions[${questionCount}]`);
                    }

                    // Clear values except for points which should be 1
                    if (input.type !== 'radio' && input.type !== 'number') {
                        input.value = '';
                    }

                    // Reset radio buttons
                    if (input.type === 'radio' && input.name.includes('correct_option')) {
                        input.checked = input.value === '0';
                    }
                    if (input.type === 'radio' && input.name.includes('correct_answer')) {
                        input.checked = input.value === 'true';
                    }
                });

                // Show delete button for all questions except the first one
                const deleteBtn = questionBlock.querySelector('.delete-question');
                deleteBtn.classList.remove('hidden');

                // Reset type selection
                const typeSelect = questionBlock.querySelector('.question-type');
                typeSelect.value = '';

                // Hide all option sections
                questionBlock.querySelector('.multiple-choice-options').classList.add('hidden');
                questionBlock.querySelector('.true-false-options').classList.add('hidden');
                questionBlock.querySelector('.short-answer-options').classList.add('hidden');

                // Add the new question block
                questionsContainer.appendChild(questionBlock);
                questionCount++;

                // Reinitialize event listeners for the new question block
                initQuestionBlock(questionBlock);

                // Scroll to the new question
                questionBlock.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });

            // Initialize all question blocks
            function initQuestionBlock(block) {
                const typeSelect = block.querySelector('.question-type');
                const multipleChoiceOptions = block.querySelector('.multiple-choice-options');
                const trueFalseOptions = block.querySelector('.true-false-options');
                const shortAnswerOptions = block.querySelector('.short-answer-options');
                const addOptionBtn = block.querySelector('.add-option');
                const optionsContainer = block.querySelector('.options-container');
                const deleteBtn = block.querySelector('.delete-question');

                // Type change handler
                typeSelect.addEventListener('change', function() {
                    multipleChoiceOptions.classList.add('hidden');
                    trueFalseOptions.classList.add('hidden');
                    shortAnswerOptions.classList.add('hidden');

                    switch(this.value) {
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
                });

                // Add option handler
                if (addOptionBtn) {
                    addOptionBtn.addEventListener('click', function() {
                        const optionCount = optionsContainer.children.length;
                        const newOption = document.createElement('div');
                        newOption.className = 'flex items-center space-x-2';

                        // Extract the question index from the name attribute
                        const questionIndex = typeSelect.name.match(/questions\[(\d+)\]/)[1];

                        newOption.innerHTML = `
                            <input type="text" name="questions[${questionIndex}][options][]" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Option ${optionCount + 1}">
                            <div class="flex items-center">
                                <input type="radio" name="questions[${questionIndex}][correct_option]" value="${optionCount}" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                <label class="ml-2 text-sm text-gray-700">Correct</label>
                            </div>
                            <button type="button" class="text-red-600 hover:text-red-900 remove-option">
                                <i class="fas fa-times"></i>
                            </button>
                        `;

                        optionsContainer.appendChild(newOption);

                        // Add event listener to the remove button
                        const removeBtn = newOption.querySelector('.remove-option');
                        removeBtn.addEventListener('click', function() {
                            newOption.remove();
                            updateOptionIndices(optionsContainer, questionIndex);
                        });
                    });
                }

                // Delete question handler
                if (deleteBtn) {
                    deleteBtn.addEventListener('click', function() {
                        if (confirm('Are you sure you want to remove this question?')) {
                            block.remove();
                        }
                    });
                }

                // Add event listeners to existing remove option buttons
                const removeOptionBtns = block.querySelectorAll('.remove-option');
                removeOptionBtns.forEach(btn => {
                    btn.addEventListener('click', function() {
                        const option = btn.closest('.flex');
                        const questionIndex = typeSelect.name.match(/questions\[(\d+)\]/)[1];
                        option.remove();
                        updateOptionIndices(optionsContainer, questionIndex);
                    });
                });
            }

            // Update option indices after removal
            function updateOptionIndices(container, questionIndex) {
                const options = container.querySelectorAll('.flex');
                options.forEach((option, index) => {
                    const radio = option.querySelector('input[type="radio"]');
                    radio.value = index;
                    radio.name = `questions[${questionIndex}][correct_option]`;
                });
            }

            // Initialize all existing question blocks
            document.querySelectorAll('.question-block').forEach(block => {
                initQuestionBlock(block);
            });
        });
    </script>
    @endpush
</x-tenant-app-layout>
