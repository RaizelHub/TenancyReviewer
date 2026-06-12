<x-tenant-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Submission Details
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('activities.show', $submission->activity_id) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                    </svg>
                    Back to Activity
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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
                    <!-- Submission Details -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Submission Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Student</p>
                                <p class="text-gray-900 dark:text-white">{{ $submission->student->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Submitted At</p>
                                <p class="text-gray-900 dark:text-white">{{ $submission->created_at->format('M d, Y H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Status</p>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $submission->status === 'graded' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($submission->status) }}
                                </span>
                            </div>
                            @if($submission->status === 'graded')
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Grade</p>
                                    <p class="text-gray-900 dark:text-white">{{ $submission->grade }}%</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Submitted File -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Submitted File</h3>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500 dark:text-gray-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                    </svg>
                                    <span class="text-gray-900 dark:text-white">{{ basename($submission->file_path) }}</span>
                                </div>
                                <div class="flex space-x-2">
                                    @if($isPremium)
                                        <a href="{{ route('submissions.preview', $submission) }}" target="_blank" class="inline-flex items-center px-3 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors text-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            View
                                        </a>
                                        <a href="{{ route('submissions.download', $submission) }}" class="inline-flex items-center px-3 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors text-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                            Download
                                        </a>
                                    @else
                                        <div class="flex items-center space-x-4">
                                            <a href="{{ route('submissions.preview', $submission) }}" target="_blank" class="inline-flex items-center px-3 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors text-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                Download
                                            </a>                             
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Grading Form -->
                    @if($submission->status !== 'graded')
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Grade Submission</h3>
                            <form action="{{ route('submissions.grade', $submission) }}" method="POST" class="space-y-6">
                                @csrf
                                <div>
                                    <x-input-label for="grade" :value="__('Grade (0-100)')" />
                                    <x-text-input id="grade" name="grade" type="number" class="mt-1 block w-full" min="0" max="100" step="0.1" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('grade')" />
                                </div>

                                <div>
                                    <x-input-label for="feedback" :value="__('Feedback (Optional)')" />
                                    <textarea id="feedback" name="feedback" rows="4" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"></textarea>
                                    <x-input-error class="mt-2" :messages="$errors->get('feedback')" />
                                </div>

                                <div class="flex justify-end">
                                    <x-primary-button>
                                        Submit Grade
                                    </x-primary-button>
                                </div>
                            </form>
                        </div>
                    @else
                        <!-- Grade Details -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Grade Details</h3>
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Grade</p>
                                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $submission->grade }}%</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Graded At</p>
                                        <p class="text-gray-900 dark:text-white">{{ $submission->graded_at->format('M d, Y H:i') }}</p>
                                    </div>
                                    @if($submission->feedback)
                                        <div class="md:col-span-2">
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Feedback</p>
                                            <p class="text-gray-900 dark:text-white whitespace-pre-line">{{ $submission->feedback }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-tenant-app-layout>