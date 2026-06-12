<x-tenant-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $subject->name }}
            </h2>
            <a href="{{ route('student.subjects') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to My Subjects
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <!-- Subject Details Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-6">
                        <!-- Left Column: Subject Info -->
                        <div class="flex-1">
                            <div class="flex items-center mb-4">
                                <div class="w-3 h-12 rounded-l-md mr-3" style="background-color: {{ $subject->color ?? '#4f46e5' }};"></div>
                                <div>
                                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $subject->name }}</h3>
                                    <p class="text-gray-500 dark:text-gray-400">{{ $subject->code }}</p>
                                </div>
                            </div>

                            @if($subject->description)
                                <div class="mt-4">
                                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Description</h4>
                                    <p class="text-gray-900 dark:text-white">{{ $subject->description }}</p>
                                </div>
                            @endif

                            <div class="mt-6">
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Teacher</h4>
                                <div class="flex items-center mt-2 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div class="flex-shrink-0 mr-3">
                                        <div class="h-10 w-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center text-indigo-700 dark:text-indigo-300 font-semibold text-lg">
                                            {{ substr($subject->user->name, 0, 1) }}
                                        </div>
                                    </div>
                                    <div class="flex-grow">
                                        <h5 class="font-medium text-gray-900 dark:text-white">{{ $subject->user->name }}</h5>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $subject->user->email }}</p>
                                    </div>
                                </div>
                                <div class="mt-2">
                                
                                </div>
                            </div>

                            <!-- Final Grade Card -->
                            <div class="mt-6 mb-6">
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Your Performance</h4>
                                <div class="bg-gradient-to-r from-indigo-500 to-blue-600 p-4 rounded-lg text-white shadow-md">
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 rounded-full bg-white/20 flex items-center justify-center mr-3">
                                                <i class="fas fa-chart-line text-white"></i>
                                            </div>
                                            <div>
                                                <h5 class="font-bold text-lg">Final Grade</h5>
                                                <p class="text-blue-100 text-sm">Based on your submissions and quiz results</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="flex items-center justify-end">
                                                <div class="text-3xl font-bold mr-3">{{ $gradeData['grade'] }}%</div>
                                                <div class="flex flex-col items-center justify-center bg-white/20 rounded-lg px-3 py-2">
                                                    <span class="text-xs text-blue-100">College Grade</span>
                                                    <span class="text-2xl font-bold {{ $gradeData['college_grade'] == '5.0' ? 'text-red-300' : 'text-green-300' }}">
                                                        {{ $gradeData['college_grade'] }}
                                                    </span>
                                                    <span class="text-xs text-blue-100">{{ $gradeData['remarks'] }}</span>
                                                </div>
                                            </div>
                                            <div class="text-blue-100 text-sm mt-1">
                                                {{ $gradeData['completed_activities'] }}/{{ $gradeData['total_activities'] }} activities completed
                                            </div>
                                        </div>
                                    </div>

                                    <div class="w-full bg-white/20 rounded-full h-2.5 mb-1">
                                        <div class="bg-white h-2.5 rounded-full" style="width: {{ $gradeData['grade'] }}%"></div>
                                    </div>
                                    <div class="flex justify-between text-xs text-blue-100">
                                        <span>0%</span>
                                        <span>50%</span>
                                        <span>100%</span>
                                    </div>

                                    <!-- College Grade Scale Legend -->
                                    <div class="mt-3 pt-3 border-t border-white/20">
                                        <div class="text-xs text-blue-100 mb-2">College Grade Scale:</div>
                                        <div class="grid grid-cols-5 gap-1 text-xs">
                                            <div class="text-center">
                                                <span class="block text-green-300 font-bold">1.0-1.75</span>
                                                <span class="text-blue-100">Excellent</span>
                                            </div>
                                            <div class="text-center">
                                                <span class="block text-green-300 font-bold">2.0-2.25</span>
                                                <span class="text-blue-100">Very Satisfactory</span>
                                            </div>
                                            <div class="text-center">
                                                <span class="block text-green-300 font-bold">2.5-2.75</span>
                                                <span class="text-blue-100">Satisfactory</span>
                                            </div>
                                            <div class="text-center">
                                                <span class="block text-green-300 font-bold">3.0</span>
                                                <span class="text-blue-100">Passed</span>
                                            </div>
                                            <div class="text-center">
                                                <span class="block text-red-300 font-bold">5.0</span>
                                                <span class="text-blue-100">Failed</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6">
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Activities</h4>
                                <div class="grid grid-cols-2 gap-4 mt-2">
                                    <div class="bg-blue-50 dark:bg-blue-900/30 p-3 rounded-lg">
                                        <div class="flex items-center">
                                            <div class="h-8 w-8 rounded-full bg-blue-100 dark:bg-blue-800 flex items-center justify-center text-blue-600 dark:text-blue-300 mr-2">
                                                <i class="fas fa-clipboard-list"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">Assignments</p>
                                                <p class="font-medium text-gray-900 dark:text-white">
                                                    {{ $subject->activities->where('type', 'assignment')->count() }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bg-green-50 dark:bg-green-900/30 p-3 rounded-lg">
                                        <div class="flex items-center">
                                            <div class="h-8 w-8 rounded-full bg-green-100 dark:bg-green-800 flex items-center justify-center text-green-600 dark:text-green-300 mr-2">
                                                <i class="fas fa-book"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">Materials</p>
                                                <p class="font-medium text-gray-900 dark:text-white">
                                                    {{ $subject->activities->where('type', 'material')->count() }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bg-purple-50 dark:bg-purple-900/30 p-3 rounded-lg">
                                        <div class="flex items-center">
                                            <div class="h-8 w-8 rounded-full bg-purple-100 dark:bg-purple-800 flex items-center justify-center text-purple-600 dark:text-purple-300 mr-2">
                                                <i class="fas fa-bullhorn"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">Announcements</p>
                                                <p class="font-medium text-gray-900 dark:text-white">
                                                    {{ $subject->activities->where('type', 'announcement')->count() }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bg-yellow-50 dark:bg-yellow-900/30 p-3 rounded-lg">
                                        <div class="flex items-center">
                                            <div class="h-8 w-8 rounded-full bg-yellow-100 dark:bg-yellow-800 flex items-center justify-center text-yellow-600 dark:text-yellow-300 mr-2">
                                                <i class="fas fa-question-circle"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">Quizzes</p>
                                                <p class="font-medium text-gray-900 dark:text-white">
                                                    @php
                                                        $quizCount = 0;
                                                        foreach ($subject->activities as $activity) {
                                                            if ($activity->quiz) {
                                                                $quizCount++;
                                                            }
                                                        }
                                                        echo $quizCount;
                                                    @endphp
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: Classmates -->
                        <div class="md:w-1/3 bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                                <i class="fas fa-users mr-2 text-indigo-500"></i>
                                Classmates ({{ $classmates->count() }})
                            </h3>

                            @if($classmates->count() > 0)
                                <div class="space-y-3 max-h-96 overflow-y-auto pr-2">
                                    @foreach($classmates as $classmate)
                                        <div class="flex items-center p-2 hover:bg-gray-100 dark:hover:bg-gray-600 rounded-lg transition-colors">
                                            <div class="flex-shrink-0 mr-3">
                                                @if($classmate->profile_photo)
                                                    <img src="{{ Storage::url($classmate->profile_photo) }}" alt="{{ $classmate->name }}" class="h-10 w-10 rounded-full object-cover">
                                                @else
                                                    <div class="h-10 w-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center text-indigo-700 dark:text-indigo-300 font-semibold text-lg">
                                                        {{ substr($classmate->name, 0, 1) }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <h5 class="font-medium text-gray-900 dark:text-white">{{ $classmate->name }}</h5>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                                    @if($classmate->plan === 'premium')
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300">
                                                            <i class="fas fa-crown text-yellow-500 mr-1"></i> Premium
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300">
                                                            Basic
                                                        </span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-user-friends text-4xl mb-2"></i>
                                    <p>You're the only student in this class.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <a href="{{ route('student.assignments') }}" class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm hover:shadow-md transition-shadow flex items-center">
                    <div class="h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center text-blue-600 dark:text-blue-300 mr-3">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900 dark:text-white">My Assignments</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">View all your assignments</p>
                    </div>
                </a>
                <a href="{{ route('student.materials') }}" class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm hover:shadow-md transition-shadow flex items-center">
                    <div class="h-10 w-10 rounded-full bg-green-100 dark:bg-green-900 flex items-center justify-center text-green-600 dark:text-green-300 mr-3">
                        <i class="fas fa-book"></i>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900 dark:text-white">My Materials</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Access learning materials</p>
                    </div>
                </a>
                <a href="{{ route('quizzes.index') }}" class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm hover:shadow-md transition-shadow flex items-center">
                    <div class="h-10 w-10 rounded-full bg-yellow-100 dark:bg-yellow-900 flex items-center justify-center text-yellow-600 dark:text-yellow-300 mr-3">
                        <i class="fas fa-question-circle"></i>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900 dark:text-white">My Quizzes</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Take quizzes and tests</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</x-tenant-app-layout>
