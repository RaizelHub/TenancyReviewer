<x-tenant-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $quiz->title }}
            </h2>
            <div id="quiz-timer" class="text-sm bg-blue-100 text-blue-800 px-3 py-1 rounded-full" data-time-limit="{{ $quiz->time_limit }}">
                @if($quiz->time_limit)
                    Time Remaining: <span id="timer-display">{{ $quiz->time_limit }}:00</span>
                @else
                    No Time Limit
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 min-h-[calc(100vh-10rem)]">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <form id="quiz-form" method="POST" action="{{ route('quizzes.submit', $attempt->id) }}">
                        @csrf

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
                            @endforeach
                        </div>

                        <div class="mt-8 flex justify-between">
                            <a href="{{ route('quizzes.index') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                                Cancel
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-purple-600 rounded-lg hover:bg-purple-700 focus:ring-4 focus:outline-none focus:ring-purple-300">
                                Submit Quiz
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const timeLimit = parseInt(document.getElementById('quiz-timer').dataset.timeLimit);

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
