<x-tenant-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $quiz->title }}
            </h2>
            @if($attempt->id === 0)
                <div class="text-sm bg-amber-100 text-amber-800 px-3 py-1 rounded-full font-semibold flex items-center gap-1.5 shadow-sm">
                    <i class="fas fa-graduation-cap"></i> Practice Mode
                </div>
            @else
                <div id="quiz-timer" class="text-sm bg-blue-100 text-blue-800 px-3 py-1 rounded-full" data-time-limit="{{ $quiz->time_limit }}">
                    @if($quiz->time_limit)
                        Time Remaining: <span id="timer-display">{{ $quiz->time_limit }}:00</span>
                    @else
                        No Time Limit
                    @endif
                </div>
            @endif
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 min-h-[calc(100vh-10rem)]">
            @if($attempt->id === 0)
                <div class="bg-amber-50 border-l-4 border-amber-500 p-4 mb-6 rounded-lg flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-circle-info text-amber-600 text-lg"></i>
                        <div class="text-sm text-amber-800">
                            <span class="font-bold">Practice Sandbox Mode is Active.</span> Click "Check Answer" under any question to instantly verify accuracy and see explanation guides.
                        </div>
                    </div>
                    <a href="{{ route('quizzes.show', $quiz->id) }}" class="text-xs bg-amber-100 hover:bg-amber-200/80 px-2.5 py-1.5 rounded-lg text-amber-900 font-semibold transition-colors flex items-center gap-1">
                        <i class="fas fa-circle-xmark"></i> Exit Practice
                    </a>
                </div>
            @endif

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

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    @if($attempt->id === 0)
                        <div id="quiz-form">
                    @else
                        <form id="quiz-form" method="POST" action="{{ route('quizzes.submit', $attempt->id) }}">
                            @csrf
                    @endif

                        <div class="mb-6">
                            <div class="flex justify-between items-center">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $quiz->title }}</h3>
                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $questions->count() }} questions •
                                    @if($quiz->passing_score)
                                        Passing score: {{ $quiz->passing_score }}%
                                    @endif
                                </span>
                            </div>

                            @if($quiz->description)
                                <p class="mt-2 text-gray-600 dark:text-gray-300">{{ $quiz->description }}</p>
                            @endif
                        </div>

                        <div class="space-y-8">
                            @foreach($questions as $index => $question)
                                @if($attempt->id === 0)
                                    <!-- Practice Mode Question Card -->
                                    <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg border border-gray-100 dark:border-gray-600 shadow-sm" id="question-{{ $question->id }}"
                                         x-data="{ 
                                            selectedAnswer: '',
                                            correctAnswer: '{{ is_array($question->correct_answer) ? ($question->correct_answer[0] ?? "") : $question->correct_answer }}',
                                            showFeedback: false,
                                            checkAnswer() {
                                                this.showFeedback = true;
                                            }
                                         }">
                                        <div class="flex justify-between items-start mb-4">
                                            <h4 class="text-md font-medium text-gray-900 dark:text-white">
                                                Question {{ $index + 1 }}: {{ $question->question }}
                                            </h4>
                                            <span class="text-xs bg-amber-100 text-amber-800 px-2 py-1 rounded">
                                                {{ $question->points }} {{ $question->points == 1 ? 'point' : 'points' }}
                                            </span>
                                        </div>

                                        @if($question->type === 'multiple_choice')
                                            <div class="space-y-3">
                                                @foreach($question->options as $optionIndex => $option)
                                                    <div class="flex items-center p-2.5 rounded-lg border transition-colors"
                                                         :class="{ 
                                                            'bg-green-50 border-green-200 dark:bg-green-950/20 dark:border-green-900': showFeedback && correctAnswer == '{{ $optionIndex }}',
                                                            'bg-red-50 border-red-200 dark:bg-red-950/20 dark:border-red-900': showFeedback && selectedAnswer == '{{ $optionIndex }}' && selectedAnswer != correctAnswer,
                                                            'border-gray-200/60 dark:border-gray-600': !showFeedback || (correctAnswer != '{{ $optionIndex }}' && selectedAnswer != '{{ $optionIndex }}')
                                                         }">
                                                        <input type="radio" id="q{{ $question->id }}_option{{ $optionIndex }}"
                                                            name="answer_{{ $question->id }}"
                                                            value="{{ $optionIndex }}"
                                                            x-model="selectedAnswer"
                                                            :disabled="showFeedback"
                                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:bg-gray-700 dark:border-gray-600">
                                                        <label for="q{{ $question->id }}_option{{ $optionIndex }}" class="ml-2.5 text-sm font-medium text-gray-900 dark:text-gray-300 flex-1 cursor-pointer">
                                                            {{ $option }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @elseif($question->type === 'true_false')
                                            <div class="space-y-3">
                                                <div class="flex items-center p-2.5 rounded-lg border transition-colors"
                                                     :class="{ 
                                                        'bg-green-50 border-green-200 dark:bg-green-950/20 dark:border-green-900': showFeedback && correctAnswer == 'true',
                                                        'bg-red-50 border-red-200 dark:bg-red-950/20 dark:border-red-900': showFeedback && selectedAnswer == 'true' && selectedAnswer != correctAnswer,
                                                        'border-gray-200/60 dark:border-gray-600': !showFeedback || (correctAnswer != 'true' && selectedAnswer != 'true')
                                                     }">
                                                    <input type="radio" id="q{{ $question->id }}_true"
                                                        name="answer_{{ $question->id }}"
                                                        value="true"
                                                        x-model="selectedAnswer"
                                                        :disabled="showFeedback"
                                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:bg-gray-700 dark:border-gray-600">
                                                    <label for="q{{ $question->id }}_true" class="ml-2.5 text-sm font-medium text-gray-900 dark:text-gray-300 flex-1 cursor-pointer">
                                                        True
                                                    </label>
                                                </div>
                                                <div class="flex items-center p-2.5 rounded-lg border transition-colors"
                                                     :class="{ 
                                                        'bg-green-50 border-green-200 dark:bg-green-950/20 dark:border-green-900': showFeedback && correctAnswer == 'false',
                                                        'bg-red-50 border-red-200 dark:bg-red-950/20 dark:border-red-900': showFeedback && selectedAnswer == 'false' && selectedAnswer != correctAnswer,
                                                        'border-gray-200/60 dark:border-gray-600': !showFeedback || (correctAnswer != 'false' && selectedAnswer != 'false')
                                                     }">
                                                    <input type="radio" id="q{{ $question->id }}_false"
                                                        name="answer_{{ $question->id }}"
                                                        value="false"
                                                        x-model="selectedAnswer"
                                                        :disabled="showFeedback"
                                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:bg-gray-700 dark:border-gray-600">
                                                    <label for="q{{ $question->id }}_false" class="ml-2.5 text-sm font-medium text-gray-900 dark:text-gray-300 flex-1 cursor-pointer">
                                                        False
                                                    </label>
                                                </div>
                                            </div>
                                        @elseif($question->type === 'short_answer')
                                            <div>
                                                <input type="text" id="q{{ $question->id }}_answer"
                                                    name="answer_{{ $question->id }}"
                                                    x-model="selectedAnswer"
                                                    :disabled="showFeedback"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                    :class="{
                                                        'border-green-500 bg-green-50 dark:bg-green-950/20': showFeedback && selectedAnswer.toLowerCase().trim() == correctAnswer.toLowerCase().trim(),
                                                        'border-red-500 bg-red-50 dark:bg-red-950/20': showFeedback && selectedAnswer.toLowerCase().trim() != correctAnswer.toLowerCase().trim()
                                                    }"
                                                    placeholder="Your answer">
                                            </div>
                                        @endif

                                        <div class="mt-4 flex flex-col gap-2">
                                            <div class="flex gap-2">
                                                <button type="button" @click="checkAnswer()" :disabled="!selectedAnswer || showFeedback" class="px-4 py-1.5 text-xs font-semibold bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg disabled:opacity-50 transition-colors">
                                                    Check Answer
                                                </button>
                                                <button type="button" x-show="showFeedback" @click="showFeedback = false; selectedAnswer = ''" class="px-4 py-1.5 text-xs font-semibold bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors">
                                                    Reset
                                                </button>
                                            </div>
                                            
                                            <!-- Answer Feedback -->
                                            <div x-show="showFeedback" class="mt-2 text-sm" style="display: none;">
                                                <template x-if="selectedAnswer.toLowerCase().trim() == correctAnswer.toLowerCase().trim()">
                                                    <span class="text-green-600 dark:text-green-400 font-bold flex items-center gap-1">
                                                        <i class="fas fa-circle-check"></i> Correct!
                                                    </span>
                                                </template>
                                                <template x-if="selectedAnswer.toLowerCase().trim() != correctAnswer.toLowerCase().trim()">
                                                    <span class="text-red-600 dark:text-red-400 font-bold flex items-center gap-1">
                                                        <i class="fas fa-circle-xmark"></i> Incorrect. The correct answer is: 
                                                        <span class="underline font-mono" x-text="correctAnswer"></span>
                                                    </span>
                                                </template>
                                                
                                                @if($question->explanation)
                                                    <div class="mt-2 bg-emerald-50/50 dark:bg-emerald-950/20 border-l-2 border-emerald-500 p-3 rounded text-gray-700 dark:text-gray-300">
                                                        <span class="font-semibold block text-emerald-700 dark:text-emerald-400 text-xs uppercase tracking-wider mb-1">Explanation:</span>
                                                        {{ $question->explanation }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <!-- Regular Exam Mode Question Card -->
                                    <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg" id="question-{{ $question->id }}">
                                        <div class="flex justify-between items-start mb-4">
                                            <h4 class="text-md font-medium text-gray-900 dark:text-white">
                                                Question {{ $index + 1 }}: {{ $question->question }}
                                            </h4>
                                            <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">
                                                {{ $question->points }} {{ $question->points == 1 ? 'point' : 'points' }}
                                            </span>
                                        </div>

                                        @if($question->type === 'multiple_choice')
                                            <div class="space-y-3">
                                                @foreach($question->options as $optionIndex => $option)
                                                    <div class="flex items-center">
                                                        <input type="radio" id="q{{ $question->id }}_option{{ $optionIndex }}"
                                                            name="answer_{{ $question->id }}"
                                                            value="{{ $optionIndex }}"
                                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:bg-gray-700 dark:border-gray-600">
                                                        <label for="q{{ $question->id }}_option{{ $optionIndex }}" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                                            {{ $option }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @elseif($question->type === 'true_false')
                                            <div class="space-y-3">
                                                <div class="flex items-center">
                                                    <input type="radio" id="q{{ $question->id }}_true"
                                                        name="answer_{{ $question->id }}"
                                                        value="true"
                                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:bg-gray-700 dark:border-gray-600">
                                                    <label for="q{{ $question->id }}_true" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                                        True
                                                    </label>
                                                </div>
                                                <div class="flex items-center">
                                                    <input type="radio" id="q{{ $question->id }}_false"
                                                        name="answer_{{ $question->id }}"
                                                        value="false"
                                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:bg-gray-700 dark:border-gray-600">
                                                    <label for="q{{ $question->id }}_false" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                                        False
                                                    </label>
                                                </div>
                                            </div>
                                        @elseif($question->type === 'short_answer')
                                            <div>
                                                <input type="text" id="q{{ $question->id }}_answer"
                                                    name="answer_{{ $question->id }}"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                    placeholder="Your answer">
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        <div class="mt-8 flex justify-between">
                            @if($attempt->id === 0)
                                <a href="{{ route('quizzes.show', $quiz->id) }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                                    Cancel
                                </a>
                                <a href="{{ route('quizzes.show', $quiz->id) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition-colors">
                                    Finish Practice
                                </a>
                            @else
                                <a href="{{ route('quizzes.index') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                                    Cancel
                                </a>
                                <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 focus:ring-4 focus:outline-none focus:ring-emerald-300">
                                    Submit Quiz
                                </button>
                            @endif
                        </div>
                    @if($attempt->id === 0)
                        </div>
                    @else
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const timerEl = document.getElementById('quiz-timer');
            if (!timerEl) return;
            const timeLimit = parseInt(timerEl.dataset.timeLimit);

            if (timeLimit) {
                let totalSeconds = timeLimit * 60;
                const timerDisplay = document.getElementById('timer-display');
                const quizForm = document.getElementById('quiz-form');

                const timer = setInterval(function() {
                    totalSeconds--;

                    if (totalSeconds <= 0) {
                        clearInterval(timer);
                        alert('Time is up! Your quiz will be submitted automatically.');
                        quizForm.submit();
                        return;
                    }

                    const minutes = Math.floor(totalSeconds / 60);
                    const seconds = totalSeconds % 60;

                    timerDisplay.textContent = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;

                    // Warning when 1 minute remaining
                    if (totalSeconds === 60) {
                        alert('You have 1 minute remaining!');
                    }
                }, 1000);

                // Submit form when leaving page if quiz is not completed
                window.addEventListener('beforeunload', function(e) {
                    const confirmationMessage = 'If you leave this page, your quiz progress will be lost. Are you sure you want to leave?';
                    e.returnValue = confirmationMessage;
                    return confirmationMessage;
                });

                // Remove warning when submitting form
                quizForm.addEventListener('submit', function() {
                    window.removeEventListener('beforeunload', function() {});
                });
            }
        });
    </script>
    @endpush
</x-tenant-app-layout>
