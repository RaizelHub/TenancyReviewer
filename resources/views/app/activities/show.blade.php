<x-tenant-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $activity->title }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('activities.edit', $activity->id) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Activity
                </a>
                <a href="{{ route('activities.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                    </svg>
                    Back to Activities
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

            <!-- Activity Details -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $activity->title }}</h1>
                            <div class="flex items-center mt-2 flex-wrap gap-2">
                                <a href="{{ route('subjects.show', $activity->subject_id) }}" class="text-sm bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full hover:bg-blue-200 transition-colors">
                                    {{ $activity->subject->name }}
                                </a>
                                @php
                                    $typeColors = [
                                        'assignment' => 'purple',
                                        'material' => 'green',
                                        'announcement' => 'amber'
                                    ];
                                    $typeColor = $typeColors[$activity->type] ?? 'gray';
                                @endphp
                                <span class="text-sm bg-{{ $typeColor }}-100 text-{{ $typeColor }}-800 px-2 py-0.5 rounded-full">
                                    {{ ucfirst($activity->type) }}
                                </span>
                                <span class="text-sm {{ $activity->is_published ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }} px-2 py-0.5 rounded-full">
                                    {{ $activity->is_published ? 'Published' : 'Draft' }}
                                </span>
                                @if($activity->points)
                                    <span class="text-sm bg-gray-100 text-gray-800 px-2 py-0.5 rounded-full">
                                        {{ $activity->points }} points
                                    </span>
                                @endif
                                @if($activity->due_date)
                                    <span class="text-sm bg-red-100 text-red-800 px-2 py-0.5 rounded-full">
                                        Due: {{ $activity->due_date->format('M d, Y g:i A') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            @if($activity->is_published)
                                <form method="POST" action="{{ route('activities.unpublish', $activity->id) }}">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors text-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                        </svg>
                                        Unpublish
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('activities.publish', $activity->id) }}">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors text-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Publish
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Description</h3>
                        <div class="prose dark:prose-invert max-w-none">
                            <p class="text-gray-600 dark:text-gray-300 whitespace-pre-line">{{ $activity->description }}</p>
                        </div>
                    </div>

                    @if($activity->attachment)
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Attachment</h3>
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500 dark:text-gray-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                    </svg>
                                    <span class="text-gray-700 dark:text-gray-300">
                                        {{ basename($activity->attachment) }}
                                    </span>
                                </div>
                                <div class="mt-3 flex space-x-3">
                                    <a href="{{ route('activities.file-viewer', ['path' => $activity->attachment]) }}" target="_blank" class="inline-flex items-center px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors text-sm shadow-sm">
                                        <i class="fas fa-eye mr-1.5"></i>
                                        View
                                    </a>
                                    <a href="{{ route('activities.download-attachment', ['path' => $activity->attachment]) }}" class="inline-flex items-center px-3 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors text-sm shadow-sm">
                                        <i class="fas fa-download mr-1.5"></i>
                                        Download
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Activity Documents -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Activity Documents</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @if($activity->activity_document_path)
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <h4 class="font-medium text-gray-900 dark:text-white mb-2">Activity Document</h4>
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('activities.file-viewer', ['path' => $activity->activity_document_path]) }}" target="_blank" class="inline-flex items-center px-3 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors text-sm shadow-sm">
                                        <i class="fas fa-eye mr-1.5"></i>
                                        View
                                    </a>
                                    <a href="{{ route('activities.download-attachment', ['path' => $activity->activity_document_path]) }}" class="inline-flex items-center px-3 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors text-sm shadow-sm">
                                        <i class="fas fa-download mr-1.5"></i>
                                        Download
                                    </a>
                                </div>
                            </div>
                        @endif

                        @if($activity->reviewer_attachment_path)
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <h4 class="font-medium text-gray-900 dark:text-white mb-2">Reviewer Attachment</h4>
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('activities.file-viewer', ['path' => $activity->reviewer_attachment_path]) }}" target="_blank" class="inline-flex items-center px-3 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors text-sm shadow-sm">
                                        <i class="fas fa-eye mr-1.5"></i>
                                        View
                                    </a>
                                    <a href="{{ route('activities.download-attachment', ['path' => $activity->reviewer_attachment_path]) }}" class="inline-flex items-center px-3 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors text-sm shadow-sm">
                                        <i class="fas fa-download mr-1.5"></i>
                                        Download
                                    </a>
                                </div>
                            </div>
                        @endif

                        @if($activity->google_docs_url)
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <h4 class="font-medium text-gray-900 dark:text-white mb-2">Google Docs</h4>
                                <a href="{{ $activity->google_docs_url }}" target="_blank" class="inline-flex items-center px-3 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors text-sm shadow-sm">
                                    <i class="fab fa-google-drive mr-1.5"></i>
                                    Open in Google Docs
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Submissions Section (for assignments only) -->
            <!-- Quiz Section (for materials only) -->
            @if($activity->type === 'material')
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg mb-6">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Quiz</h3>
                            @if(!$activity->hasQuiz())
                                <a href="{{ route('teacher-quizzes.create', ['activity_id' => $activity->id]) }}" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Add Quiz
                                </a>
                            @endif
                        </div>

                        @if($activity->hasQuiz())
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="font-medium text-gray-900 dark:text-white">{{ $activity->quiz->title }}</h4>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $activity->quiz->description }}</p>
                                        <div class="mt-2 flex flex-wrap gap-2">
                                            <span class="text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full">
                                                {{ $activity->quiz->questions->count() }} questions
                                            </span>
                                            <span class="text-xs {{ $activity->quiz->is_published ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }} px-2 py-0.5 rounded-full">
                                                {{ $activity->quiz->is_published ? 'Published' : 'Draft' }}
                                            </span>
                                            <span class="text-xs bg-purple-100 text-purple-800 px-2 py-0.5 rounded-full">
                                                Passing score: {{ $activity->quiz->passing_score }}%
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('teacher-quizzes.show', $activity->quiz->id) }}" class="inline-flex items-center px-3 py-1.5 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors text-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Manage Quiz
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No Quiz Added</h3>
                                <p class="mt-1 text-gray-500 dark:text-gray-400">Add a quiz to this material to test student knowledge.</p>
                                <div class="mt-6">
                                    <a href="{{ route('teacher-quizzes.create', ['activity_id' => $activity->id]) }}" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Add Quiz
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Submissions Section (for assignments only) -->
            @if($activity->type === 'assignment')
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Student Submissions</h3>
                        @php
                            $totalStudents = $activity->subject->students->count();
                            $submittedCount = $activity->submissions->count();
                            $gradedCount = $activity->submissions->where('status', 'graded')->count();
                        @endphp

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                            <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg">
                                <h4 class="text-sm font-medium text-blue-800 dark:text-blue-300">Total Students</h4>
                                <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $totalStudents }}</p>
                            </div>
                            <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg">
                                <h4 class="text-sm font-medium text-green-800 dark:text-green-300">Submitted</h4>
                                <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $submittedCount }} / {{ $totalStudents }}</p>
                            </div>
                            <div class="bg-purple-50 dark:bg-purple-900/20 p-4 rounded-lg">
                                <h4 class="text-sm font-medium text-purple-800 dark:text-purple-300">Graded</h4>
                                <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $gradedCount }} / {{ $submittedCount }}</p>
                            </div>
                        </div>

                        @if($activity->submissions->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Student</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Submitted</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Score</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($activity->submissions as $submission)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div class="flex-shrink-0 h-8 w-8">
                                                            @if($submission->student->profile_photo)
                                                                <img class="h-8 w-8 rounded-full" src="{{ Storage::url($submission->student->profile_photo) }}" alt="{{ $submission->student->name }}">
                                                            @else
                                                                <div class="h-8 w-8 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center text-gray-700 dark:text-gray-300 font-semibold">
                                                                    {{ substr($submission->student->name, 0, 1) }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="ml-4">
                                                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $submission->student->name }}</div>
                                                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $submission->student->email }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900 dark:text-white">{{ $submission->created_at->format('M d, Y') }}</div>
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $submission->created_at->format('g:i A') }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $submission->status === 'graded' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                        {{ ucfirst($submission->status) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                    @if($submission->status === 'graded')
                                                        {{ $submission->grade }} / 100
                                                    @else
                                                        Not graded
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <a href="{{ route('submissions.show', $submission->id) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mr-3">View</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No submissions yet</h3>
                                <p class="mt-1 text-gray-500 dark:text-gray-400">Students haven't submitted any work for this activity yet.</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif


        </div>
    </div>
</x-tenant-app-layout>
