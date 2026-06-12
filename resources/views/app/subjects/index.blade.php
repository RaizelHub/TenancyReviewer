<x-tenant-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                <div class="p-2 rounded-full bg-indigo-100 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400 mr-3">
                    <i class="fas fa-book text-lg"></i>
                </div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Subjects') }}
                </h2>
            </div>
            <a href="{{ route('subjects.create') }}" class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-indigo-500 to-purple-500 text-white rounded-xl hover:from-indigo-600 hover:to-purple-600 shadow-sm hover:shadow transition-all duration-200">
                <i class="fas fa-plus mr-2"></i>
                Create Subject
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 p-4 mb-6 rounded-xl flex items-start" role="alert">
                    <div class="flex-shrink-0 mr-3">
                        <i class="fas fa-check-circle text-green-500 dark:text-green-400 text-xl"></i>
                    </div>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 p-4 mb-6 rounded-xl flex items-start" role="alert">
                    <div class="flex-shrink-0 mr-3">
                        <i class="fas fa-exclamation-circle text-red-500 dark:text-red-400 text-xl"></i>
                    </div>
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <!-- Subscription Plan Info -->
            <div class="mb-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border border-gray-100 dark:border-gray-700">
                <div class="flex flex-col md:flex-row md:items-center justify-between">
                    <div class="flex items-center mb-4 md:mb-0">
                        <div class="p-2 rounded-full {{ $currentPlan == 'Pro' ? 'bg-purple-100 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400' : ($currentPlan == 'Premium' ? 'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400') }} mr-3">
                            <i class="fas {{ $currentPlan == 'Pro' ? 'fa-crown' : ($currentPlan == 'Premium' ? 'fa-star' : 'fa-user') }} text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-900 dark:text-white">{{ $currentPlan }} Plan</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $remainingSubjects < PHP_INT_MAX ? "$remainingSubjects of $maxSubjects subjects remaining" : "Unlimited subjects" }}
                            </p>
                        </div>
                    </div>
                    @if($currentPlan != 'Pro')
                        <a href="#" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-500 to-indigo-500 text-white rounded-lg hover:from-purple-600 hover:to-indigo-600 shadow-sm transition-all duration-200 text-sm">
                            <i class="fas fa-arrow-up mr-2"></i>
                            Upgrade Plan
                        </a>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($subjects as $subject)
                    <div class="group bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden hover:shadow-lg transition-all duration-300 border border-gray-100 dark:border-gray-700 hover:border-indigo-200 dark:hover:border-indigo-900">
                        <div class="h-36 relative" style="background-color: {{ $subject->color }};">
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

                            @if($subject->banner_image)
                                <img src="{{ $subject->banner_image }}" alt="{{ $subject->name }}" class="w-full h-full object-cover">
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent flex flex-col justify-end p-4">
                                <h4 class="font-semibold text-lg text-white group-hover:text-white/90 transition-colors">{{ $subject->name }}</h4>
                                @if($subject->code)
                                    <p class="text-sm text-gray-200 group-hover:text-white/80 transition-colors">{{ $subject->code }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="p-5">
                            <div class="flex justify-between items-center mb-4">
                                <div class="flex items-center">
                                    <div class="p-1.5 rounded-md bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 mr-2">
                                        <i class="fas fa-user-graduate text-sm"></i>
                                    </div>
                                    <span class="text-sm text-gray-600 dark:text-gray-300">{{ $subject->students->count() }} {{ Str::plural('student', $subject->students->count()) }}</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="p-1.5 rounded-md bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400 mr-2">
                                        <i class="fas fa-tasks text-sm"></i>
                                    </div>
                                    <span class="text-sm text-gray-600 dark:text-gray-300">{{ $subject->activities->count() }} {{ Str::plural('activity', $subject->activities->count()) }}</span>
                                </div>
                            </div>

                            @if($subject->description)
                                <p class="text-sm text-gray-600 dark:text-gray-300 mb-4 line-clamp-2 bg-gray-50 dark:bg-gray-700/30 p-3 rounded-lg">{{ $subject->description }}</p>
                            @endif

                            <div class="flex space-x-2 mt-4">
                                <a href="{{ route('subjects.show', $subject->id) }}" class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm shadow-sm">
                                    <i class="fas fa-eye mr-1.5"></i>
                                    View
                                </a>
                                <a href="{{ route('subjects.edit', $subject->id) }}" class="inline-flex items-center justify-center px-3 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors text-sm shadow-sm">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <a href="{{ route('activities.create', ['subject_id' => $subject->id]) }}" class="inline-flex items-center justify-center px-3 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors text-sm shadow-sm">
                                    <i class="fas fa-plus"></i>
                                </a>
                            </div>

                            <div class="mt-3">
                                <a href="{{ route('subjects.grade-report', $subject->id) }}" class="w-full inline-flex items-center justify-center px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm shadow-sm">
                                    <i class="fas fa-file-pdf mr-1.5"></i>
                                    Generate Grade Report
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full p-10 text-center bg-white dark:bg-gray-800 rounded-xl border border-dashed border-gray-200 dark:border-gray-700 shadow-sm">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-indigo-50 dark:bg-indigo-900/20 text-indigo-500 dark:text-indigo-400 mb-4">
                            <i class="fas fa-book text-2xl"></i>
                        </div>
                        <h3 class="mt-2 text-xl font-medium text-gray-900 dark:text-white">No subjects yet</h3>
                        <p class="mt-2 text-gray-500 dark:text-gray-400 max-w-md mx-auto">Get started by creating your first subject to organize your classroom content.</p>
                        <div class="mt-6">
                            <a href="{{ route('subjects.create') }}" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-indigo-500 to-purple-500 text-white rounded-xl hover:from-indigo-600 hover:to-purple-600 shadow-sm hover:shadow transition-all duration-200">
                                <i class="fas fa-plus mr-2"></i>
                                Create Subject
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            @if($subjects->count() > 0)
                <div class="mt-6 px-4 py-4 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl shadow-sm">
                    {{ $subjects->links() }}
                </div>
            @endif
        </div>
    </div>
</x-tenant-app-layout>
