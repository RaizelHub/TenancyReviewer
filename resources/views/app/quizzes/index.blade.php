<x-tenant-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                <div class="p-2 rounded-lg bg-purple-100 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400 mr-3">
                    <i class="fas fa-question-circle"></i>
                </div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Available Quizzes') }}
                </h2>
            </div>
            <div class="flex items-center">
                @if(Auth::guard('student')->user()->plan === 'premium')
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-gradient-to-r from-purple-600 to-indigo-600 text-white shadow-sm">
                        <i class="fas fa-crown text-amber-300 mr-1.5"></i> Premium Plan
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                        Basic Plan
                    </span>
                    <a href="{{ route('student.plan') }}" class="ml-2 text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 inline-flex items-center">
                        <span>Upgrade for Quizzes</span>
                        <i class="fas fa-arrow-up ml-1 text-xs"></i>
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 min-h-[calc(100vh-10rem)]">
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow-sm" role="alert">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        </div>
                        <p>{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg shadow-sm" role="alert">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
                        </div>
                        <p>{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            @if(session('info'))
                <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6 rounded-lg shadow-sm" role="alert">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                        </div>
                        <p>{{ session('info') }}</p>
                    </div>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md rounded-xl border border-gray-100 dark:border-gray-700">
                <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gradient-to-r from-gray-50 to-white dark:from-gray-800 dark:to-gray-750">
                    <div class="flex items-center">
                        <div class="p-2 rounded-lg bg-purple-100 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400 mr-3">
                            <i class="fas fa-question-circle"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Available Quizzes</h3>
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        {{ $activitiesWithQuizzes->count() }} {{ Str::plural('quiz', $activitiesWithQuizzes->count()) }} available
                    </div>
                </div>
                <div class="p-6">

                    @if($activitiesWithQuizzes->isEmpty())
                        <div class="p-10 text-center bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-dashed border-gray-200 dark:border-gray-700">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-purple-50 dark:bg-purple-900/20 text-purple-500 dark:text-purple-400 mb-4">
                                <i class="fas fa-question-circle text-xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">No quizzes available</h3>
                            <p class="mt-2 text-gray-500 dark:text-gray-400 max-w-md mx-auto">There are no quizzes available for your enrolled subjects yet. Check back later for new quizzes from your teachers.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($activitiesWithQuizzes as $activity)
                                <div class="group bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-all duration-300 hover:border-purple-200 dark:hover:border-purple-900 hover-lift">
                                    <div class="h-2 bg-gradient-to-r from-purple-500 to-indigo-500"></div>
                                    <div class="p-6">
                                        <div class="flex justify-between items-start mb-3">
                                            <div>
                                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">{{ $activity->quiz->title }}</h3>
                                                <div class="flex items-center text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                    <div class="p-1 rounded-md bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 mr-2">
                                                        <i class="fas fa-book text-xs"></i>
                                                    </div>
                                                    <span>{{ $activity->subject->name }}</span>
                                                </div>
                                            </div>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300">
                                                <i class="fas fa-list-ul mr-1"></i>
                                                {{ $activity->quiz->question_count }} {{ Str::plural('question', $activity->quiz->question_count) }}
                                            </span>
                                        </div>

                                        <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-3 mb-4">
                                            <p class="text-gray-600 dark:text-gray-300 text-sm line-clamp-2">
                                                {{ $activity->quiz->description ?? 'No description available.' }}
                                            </p>
                                        </div>

                                        <div class="grid grid-cols-2 gap-3 mb-4">
                                            <div class="bg-purple-50 dark:bg-purple-900/10 rounded-lg p-3 text-center">
                                                <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">Time Limit</div>
                                                <div class="text-sm font-medium text-purple-700 dark:text-purple-400">
                                                    @if($activity->quiz->time_limit)
                                                        <i class="far fa-clock mr-1"></i> {{ $activity->quiz->time_limit }} minutes
                                                    @else
                                                        <i class="fas fa-infinity mr-1"></i> No limit
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="bg-purple-50 dark:bg-purple-900/10 rounded-lg p-3 text-center">
                                                <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">Passing Score</div>
                                                <div class="text-sm font-medium text-purple-700 dark:text-purple-400">
                                                    <i class="fas fa-award mr-1"></i> {{ $activity->quiz->passing_score }}%
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-4">
                                            @if(isset($attempts[$activity->quiz->id]) && $attempts[$activity->quiz->id]->count() > 0)
                                                @php
                                                    $latestAttempt = $attempts[$activity->quiz->id]->first();
                                                    $passed = $latestAttempt->passed;
                                                @endphp

                                                <div class="mb-4 p-3 {{ $passed ? 'bg-green-50 dark:bg-green-900/10 border border-green-100 dark:border-green-900/30' : 'bg-red-50 dark:bg-red-900/10 border border-red-100 dark:border-red-900/30' }} rounded-lg">
                                                    <div class="flex justify-between items-center">
                                                        <div>
                                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                                Previous attempts: {{ $attempts[$activity->quiz->id]->count() }}
                                                            </div>
                                                            <div class="text-sm font-medium {{ $passed ? 'text-green-700 dark:text-green-400' : 'text-red-700 dark:text-red-400' }} mt-1">
                                                                Latest score: {{ number_format($latestAttempt->percentage, 1) }}%
                                                                @if($passed)
                                                                    <span class="inline-flex items-center ml-1 text-xs bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 px-1.5 py-0.5 rounded">
                                                                        <i class="fas fa-check-circle mr-1"></i> Passed
                                                                    </span>
                                                                @else
                                                                    <span class="inline-flex items-center ml-1 text-xs bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300 px-1.5 py-0.5 rounded">
                                                                        <i class="fas fa-times-circle mr-1"></i> Failed
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="flex space-x-2">
                                                    <a href="{{ route('quizzes.results', $latestAttempt->id) }}" class="flex-1 inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-center text-white bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 shadow-sm">
                                                        <i class="fas fa-chart-bar mr-1.5"></i>
                                                        View Results
                                                    </a>

                                                    <a href="{{ route('quizzes.start', $activity->quiz->id) }}" class="flex-1 inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-center text-white bg-gradient-to-r from-purple-600 to-indigo-600 rounded-lg hover:from-purple-700 hover:to-indigo-700 transition-all duration-200 shadow-sm">
                                                        <i class="fas fa-redo mr-1.5"></i>
                                                        Retake Quiz
                                                    </a>
                                                </div>
                                            @else
                                                <a href="{{ route('quizzes.start', $activity->quiz->id) }}" class="w-full inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-center text-white bg-gradient-to-r from-purple-600 to-indigo-600 rounded-lg hover:from-purple-700 hover:to-indigo-700 transition-all duration-200 shadow-sm">
                                                    <i class="fas fa-play-circle mr-1.5"></i>
                                                    Start Quiz
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-tenant-app-layout>
