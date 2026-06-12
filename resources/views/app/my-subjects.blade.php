<x-tenant-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                <div class="p-2 rounded-lg bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400 mr-3">
                    <i class="fas fa-book"></i>
                </div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('My Subjects') }}
                </h2>
            </div>
            <div class="text-sm text-gray-500 dark:text-gray-400">
                {{ $subjects->count() }} {{ Str::plural('subject', $subjects->count()) }} enrolled
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md rounded-xl border border-gray-100 dark:border-gray-700 mb-8">
                <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gradient-to-r from-gray-50 to-white dark:from-gray-800 dark:to-gray-750">
                    <div class="flex items-center">
                        <div class="p-2 rounded-lg bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400 mr-3">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">My Enrolled Subjects</h3>
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($subjects as $subject)
                            <div class="group bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-all duration-300 hover:border-blue-200 dark:hover:border-blue-900 hover-lift">
                                <div class="h-32 relative" style="background-color: {{ $subject->color ?? '#4f46e5' }};">
                                    <!-- Decorative pattern -->
                                    <div class="absolute inset-0 opacity-10">
                                        <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                                            <defs>
                                                <pattern id="smallGrid" width="10" height="10" patternUnits="userSpaceOnUse">
                                                    <path d="M 10 0 L 0 0 0 10" fill="none" stroke="white" stroke-width="0.5" />
                                                </pattern>
                                            </defs>
                                            <rect width="100%" height="100%" fill="url(#smallGrid)" />
                                        </svg>
                                    </div>

                                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent flex flex-col justify-end p-4">
                                        <h4 class="font-semibold text-lg text-white group-hover:text-white/90 transition-colors">{{ $subject->name }}</h4>
                                        <p class="text-sm text-gray-200 group-hover:text-white/80 transition-colors">{{ $subject->code }}</p>
                                    </div>
                                </div>
                                <div class="p-5">
                                    <div class="flex justify-between items-center mb-4">
                                        <div class="flex items-center">
                                            <div class="p-1.5 rounded-md bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 mr-2">
                                                <i class="fas fa-tasks text-sm"></i>
                                            </div>
                                            <span class="text-sm text-gray-600 dark:text-gray-300">{{ $subject->activities->count() }} activities</span>
                                        </div>
                                        <div class="flex items-center">
                                            <div class="p-1.5 rounded-md bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400 mr-2">
                                                <i class="fas fa-user text-sm"></i>
                                            </div>
                                            <span class="text-sm text-gray-600 dark:text-gray-300">{{ $subject->user->name }}</span>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-3 gap-3 mb-4">
                                        <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-3 text-center">
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">Assignments</div>
                                            <div class="text-lg font-semibold text-blue-600 dark:text-blue-400">
                                                {{ $subject->activities->where('type', 'assignment')->count() }}
                                            </div>
                                        </div>
                                        <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-3 text-center">
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">Materials</div>
                                            <div class="text-lg font-semibold text-green-600 dark:text-green-400">
                                                {{ $subject->activities->where('type', 'material')->count() }}
                                            </div>
                                        </div>
                                        <div class="bg-gradient-to-r from-indigo-500 to-blue-600 rounded-lg p-3 text-center">
                                            <div class="text-xs text-white/80 mb-1">Grade</div>
                                            @php
                                                $student = Auth::guard('student')->user();
                                                $gradeData = $student->calculateFinalGradeForSubject($subject->id);
                                            @endphp
                                            <div class="flex flex-col items-center">
                                                <div class="text-lg font-semibold text-white">
                                                    {{ $gradeData['grade'] }}%
                                                </div>
                                                <div class="mt-1 px-2 py-0.5 rounded-full {{ $gradeData['college_grade'] == '5.0' ? 'bg-red-500/30 text-red-100' : 'bg-green-500/30 text-green-100' }} text-xs font-medium">
                                                    {{ $gradeData['college_grade'] }} ({{ $gradeData['remarks'] }})
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <a href="{{ route('student.subject.show', $subject->id) }}" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 text-sm font-medium shadow-sm">
                                        <i class="fas fa-eye mr-1.5"></i>
                                        View Details
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full p-10 text-center bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-dashed border-gray-200 dark:border-gray-700">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-50 dark:bg-blue-900/20 text-blue-500 dark:text-blue-400 mb-4">
                                    <i class="fas fa-book text-xl"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">No subjects yet</h3>
                                <p class="mt-2 text-gray-500 dark:text-gray-400 max-w-md mx-auto">You are not enrolled in any classes yet. Contact your teacher to get enrolled in subjects.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-tenant-app-layout>