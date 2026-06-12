<x-tenant-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                <div class="p-2 rounded-lg bg-purple-100 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400 mr-3">
                    <i class="fas fa-book-open"></i>
                </div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Learning Materials') }}
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
                        <span>Upgrade</span>
                        <i class="fas fa-arrow-up ml-1 text-xs"></i>
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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

            @if(Auth::guard('student')->user()->plan !== 'premium')
                <div class="bg-gradient-to-r from-purple-500/10 to-indigo-500/10 border border-purple-200 dark:border-purple-800 rounded-xl shadow-md p-5 mb-6" role="alert">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 mr-4">
                            <div class="h-12 w-12 rounded-full bg-gradient-to-r from-purple-600 to-indigo-600 flex items-center justify-center text-white shadow-md">
                                <i class="fas fa-crown text-amber-300 text-lg"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-lg text-purple-800 dark:text-purple-300">Premium Plan Benefits</h3>
                            <p class="text-sm mt-1 text-purple-700 dark:text-purple-200">Upgrade to Premium to access interactive quizzes for all learning materials. Premium members can test their knowledge with practice quizzes.</p>
                        </div>
                        <div class="ml-4">
                            <a href="{{ route('student.plan') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-lg hover:from-purple-700 hover:to-indigo-700 transition-all duration-200 text-sm font-medium shadow-sm">
                                <i class="fas fa-crown text-amber-300 mr-2"></i>
                                Upgrade Now
                            </a>
                        </div>
                    </div>
                </div>
            @endif


            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md rounded-xl border border-gray-100 dark:border-gray-700">
                <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gradient-to-r from-gray-50 to-white dark:from-gray-800 dark:to-gray-750">
                    <div class="flex items-center">
                        <div class="p-2 rounded-lg bg-purple-100 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400 mr-3">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Available Materials</h3>
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        {{ $materials->count() }} {{ Str::plural('material', $materials->count()) }} available
                    </div>
                </div>

                <div class="p-6">
                    @if($materials->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($materials as $material)
                                <div class="group bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-all duration-300 hover:border-purple-200 dark:hover:border-purple-800 hover-lift">
                                    <div class="h-3 bg-gradient-to-r from-purple-500 to-indigo-500"></div>
                                    <div class="p-5">
                                        <div class="flex justify-between items-start mb-3">
                                            <div>
                                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-1 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">{{ $material->title }}</h4>
                                                <div class="flex items-center">
                                                    <div class="p-1 rounded-md bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 mr-2">
                                                        <i class="fas fa-book text-xs"></i>
                                                    </div>
                                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $material->subject->name }}</p>
                                                </div>
                                            </div>
                                            <span class="bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 text-xs font-medium px-2.5 py-1 rounded-full">Material</span>
                                        </div>
                                        <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-3 mb-4">
                                            <p class="text-gray-600 dark:text-gray-300 text-sm line-clamp-2">{{ $material->description }}</p>
                                        </div>

                                        <div class="flex flex-col space-y-3">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('activities.file-viewer', ['path' => $material->attachment]) }}" target="_blank" class="flex-1 inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-center text-white bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 shadow-sm">
                                                    <i class="fas fa-eye mr-1.5"></i>
                                                    View
                                                </a>
                                                <a href="{{ route('activities.download-attachment', ['path' => $material->attachment]) }}" class="flex-1 inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-center text-white bg-gradient-to-r from-green-600 to-emerald-600 rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-sm">
                                                    <i class="fas fa-download mr-1.5"></i>
                                                    Download
                                                </a>
                                            </div>

                                            @if(Auth::guard('student')->user()->plan === 'premium')
                                                @if($material->hasQuiz() && isset($material->quiz) && $material->quiz && $material->quiz->is_published)
                                                    <a href="{{ route('quizzes.show', $material->quiz->id) }}" class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-center text-white bg-gradient-to-r from-purple-600 to-indigo-600 rounded-lg hover:from-purple-700 hover:to-indigo-700 transition-all duration-200 shadow-sm">
                                                        <i class="fas fa-question-circle mr-1.5"></i>
                                                        Take Quiz
                                                    </a>
                                                @else
                                                    <span class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-center text-white bg-gray-400 dark:bg-gray-600 rounded-lg cursor-not-allowed" title="No quiz available for this material">
                                                        <i class="fas fa-question-circle mr-1.5"></i>
                                                        No Quiz Available
                                                    </span>
                                                @endif
                                            @else
                                                <span class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-center text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg cursor-not-allowed border border-dashed border-gray-300 dark:border-gray-600" title="Premium plan required to access quizzes">
                                                    <i class="fas fa-crown text-amber-500 mr-1.5"></i>
                                                    Premium Feature
                                                    <i class="fas fa-lock ml-1.5 text-xs"></i>
                                                </span>
                                            @endif
                                        </div>

                                        <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700 text-right">
                                            <span class="text-xs text-gray-500 dark:text-gray-400 flex items-center justify-end">
                                                <i class="far fa-clock mr-1"></i>
                                                Added {{ $material->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-10 text-center bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-dashed border-gray-200 dark:border-gray-700">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-purple-50 dark:bg-purple-900/20 text-purple-500 dark:text-purple-400 mb-4">
                                <i class="fas fa-book-open text-xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">No materials available</h3>
                            <p class="mt-2 text-gray-500 dark:text-gray-400 max-w-md mx-auto">There are no learning materials available for your subjects yet. Check back later for updates from your teachers.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-tenant-app-layout>
