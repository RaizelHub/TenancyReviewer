<x-tenant-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $subject->name }}
                @if($subject->code)
                    <span class="text-sm text-gray-500 dark:text-gray-400 ml-2">{{ $subject->code }}</span>
                @endif
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('subjects.edit', $subject->id) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Subject
                </a>
                <a href="{{ route('subjects.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                    </svg>
                    Back to Subjects
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" x-data="{ activeTab: 'activities' }">
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <!-- Subject Banner -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg mb-6">
                <div class="h-48 relative" style="background-color: {{ $subject->color }};">
                    @if($subject->banner_image)
                        <img src="{{ $subject->banner_image }}" alt="{{ $subject->name }}" class="w-full h-full object-cover">
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex items-end">
                        <div class="p-6">
                            <h1 class="text-3xl font-bold text-white">{{ $subject->name }}</h1>
                            @if($subject->code)
                                <p class="text-gray-200 mt-1">{{ $subject->code }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="p-6 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between items-center mb-4">
                        <div class="flex items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-300 mr-4">
                                <span class="font-semibold">{{ $subject->students->count() }}</span> students
                            </span>
                            <span class="text-sm text-gray-600 dark:text-gray-300">
                                <span class="font-semibold">{{ $subject->activities->count() }}</span> activities
                            </span>
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('subjects.chat.create', $subject->id) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors text-sm">
                                <i class="fas fa-comments mr-1"></i> Chat with Students
                            </a>
                            <a href="{{ route('activities.create', ['subject_id' => $subject->id]) }}" class="inline-flex items-center px-3 py-1.5 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Add Activity
                            </a>
                            <button type="button" onclick="document.getElementById('addStudentsModal').classList.remove('hidden')" class="inline-flex items-center px-3 py-1.5 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                </svg>
                                Add Students
                            </button>
                            <a href="{{ route('subjects.grade-report', $subject->id) }}" class="inline-flex items-center px-3 py-1.5 bg-green-700 text-white rounded-md hover:bg-green-800 transition-colors text-sm">
                                <i class="fas fa-file-pdf mr-1"></i> Grade Report
                            </a>
                        </div>
                    </div>

                    <!-- Top Performing Students Section -->
                    @if(isset($topStudents) && $topStudents->count() > 0)
                        <div class="mt-6 mb-6" x-data="{ showTopStudents: false }">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    <span class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                        </svg>
                                        Top Performing Students
                                    </span>
                                </h3>
                                <button
                                    @click="showTopStudents = !showTopStudents"
                                    class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors text-sm"
                                >
                                    <span x-text="showTopStudents ? 'Hide Top Students' : 'Show Top Students'"></span>
                                    <svg x-show="!showTopStudents" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                    <svg x-show="showTopStudents" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                    </svg>
                                </button>
                            </div>

                            <div x-show="showTopStudents" x-transition class="bg-white dark:bg-gray-700 rounded-lg shadow-sm border border-gray-200 dark:border-gray-600 overflow-hidden">
                                <ul class="divide-y divide-gray-200 dark:divide-gray-600">
                                    @foreach($topStudents as $index => $student)
                                        <li class="p-4 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 mr-3">
                                                    @if($index === 0)
                                                        <div class="h-8 w-8 rounded-full bg-yellow-500 flex items-center justify-center text-white font-bold">
                                                            1
                                                        </div>
                                                    @elseif($index === 1)
                                                        <div class="h-8 w-8 rounded-full bg-gray-400 flex items-center justify-center text-white font-bold">
                                                            2
                                                        </div>
                                                    @elseif($index === 2)
                                                        <div class="h-8 w-8 rounded-full bg-amber-700 flex items-center justify-center text-white font-bold">
                                                            3
                                                        </div>
                                                    @else
                                                        <div class="h-8 w-8 rounded-full bg-gray-200 dark:bg-gray-500 flex items-center justify-center text-gray-700 dark:text-gray-200 font-bold">
                                                            {{ $index + 1 }}
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 mr-3">
                                                        @if($student->profile_photo)
                                                            <img src="{{ $student->profile_photo }}" alt="{{ $student->name }}" class="h-10 w-10 rounded-full object-cover">
                                                        @else
                                                            <div class="h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center text-gray-700 dark:text-gray-300 font-semibold text-lg">
                                                                {{ substr($student->name, 0, 1) }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <h4 class="font-medium text-gray-900 dark:text-white">{{ $student->name }}</h4>
                                                        <p class="text-sm text-gray-600 dark:text-gray-300">{{ $student->email }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <div class="text-lg font-bold text-gray-900 dark:text-white">{{ $student->average_grade }}/100</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $student->submission_count }} submission(s)</div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    @if($subject->description)
                        <div class="mt-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Description</h3>
                            <p class="text-gray-600 dark:text-gray-300">{{ $subject->description }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Tabs -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <nav class="-mb-px flex">
                        <button @click="activeTab = 'activities'" :class="{ 'border-blue-500 text-blue-600 dark:text-blue-400': activeTab === 'activities', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300': activeTab !== 'activities' }" class="w-1/2 py-4 px-1 text-center border-b-2 font-medium text-sm">
                            Activities
                        </button>
                        <button @click="activeTab = 'students'" :class="{ 'border-blue-500 text-blue-600 dark:text-blue-400': activeTab === 'students', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300': activeTab !== 'students' }" class="w-1/2 py-4 px-1 text-center border-b-2 font-medium text-sm">
                            Students
                        </button>
                    </nav>
                </div>

                <!-- Activities Tab -->
                <div x-show="activeTab === 'activities'" class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Activities</h3>
                    </div>

                    @if($subject->activities->count() > 0)
                        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($subject->activities->sortByDesc('created_at') as $activity)
                                <li class="py-4">
                                    <a href="{{ route('activities.show', $activity->id) }}" class="flex items-start hover:bg-gray-50 dark:hover:bg-gray-700 -mx-4 px-4 py-2 rounded-lg transition-colors">
                                        <div class="p-2 rounded-full bg-{{ $activity->is_published ? 'green' : 'gray' }}-100 text-{{ $activity->is_published ? 'green' : 'gray' }}-600 mr-4">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex justify-between">
                                                <h4 class="font-medium text-gray-900 dark:text-white">{{ $activity->title }}</h4>
                                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $activity->created_at->format('M d, Y') }}
                                                </span>
                                            </div>
                                            <p class="text-sm text-gray-600 dark:text-gray-300 mt-1 line-clamp-2">{{ $activity->description }}</p>
                                            <div class="mt-2 flex items-center">
                                                <span class="text-xs bg-{{ $activity->type === 'assignment' ? 'blue' : 'green' }}-100 text-{{ $activity->type === 'assignment' ? 'blue' : 'green' }}-800 px-2 py-0.5 rounded-full">
                                                    {{ ucfirst($activity->type) }}
                                                </span>
                                                @if($activity->due_date)
                                                    <span class="text-xs text-gray-500 dark:text-gray-400 ml-2">
                                                        Due: {{ $activity->due_date->format('M d, Y') }}
                                                    </span>
                                                @endif
                                                @if($activity->points)
                                                    <span class="text-xs text-gray-500 dark:text-gray-400 ml-2">
                                                        {{ $activity->points }} points
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-center py-8">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                            <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No activities yet</h3>
                            <p class="mt-1 text-gray-500 dark:text-gray-400">Create activities for your subject to get started.</p>
                            <div class="mt-6">
                                <a href="{{ route('activities.create', ['subject_id' => $subject->id]) }}" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Create Activity
                                </a>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Students Tab -->
                <div x-show="activeTab === 'students'" class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Enrolled Students</h3>
                    </div>

                    @if($subject->students->count() > 0)
                        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($subject->students as $student)
                                <li class="py-4 flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 mr-3">
                                            @if($student->profile_photo)
                                                <img src="{{ $student->profile_photo }}" alt="{{ $student->name }}" class="h-10 w-10 rounded-full object-cover">
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center text-gray-700 dark:text-gray-300 font-semibold text-lg">
                                                    {{ substr($student->name, 0, 1) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-gray-900 dark:text-white">{{ $student->name }}</h4>
                                            <p class="text-sm text-gray-600 dark:text-gray-300">{{ $student->email }}</p>
                                        </div>
                                    </div>
                                    <div>
                                        <form method="POST" action="{{ route('subjects.students.remove', [$subject->id, $student->id]) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Are you sure you want to remove this student from the subject?')" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-center py-8">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No students enrolled</h3>
                            <p class="mt-1 text-gray-500 dark:text-gray-400">Add students to your subject to get started.</p>
                            <div class="mt-6">
                                <button type="button" onclick="document.getElementById('addStudentsModal').classList.remove('hidden')" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Add Students
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Add Students Modal -->
    <div id="addStudentsModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg max-w-md w-full mx-4">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Add Students to Subject</h3>
                    <button type="button" onclick="document.getElementById('addStudentsModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form method="POST" action="{{ route('subjects.students.add', $subject->id) }}">
                    @csrf
                    <div class="mb-4">
                        <label for="student_ids" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select Students</label>
                        <select name="student_ids[]" id="student_ids" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500" multiple>
                            @php
                                $availableStudents = \App\Models\Student::whereNotIn('id', $subject->students->pluck('id'))->get();
                            @endphp
                            @foreach($availableStudents as $student)
                                <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->email }})</option>
                            @endforeach
                        </select>
                        @if($availableStudents->count() === 0)
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">No more students available to add. <a href="{{ route('students.create') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">Create new students</a>.</p>
                        @endif
                    </div>
                    <div class="flex justify-end">
                        <button type="button" onclick="document.getElementById('addStudentsModal').classList.add('hidden')" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-2">
                            Cancel
                        </button>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Add Students
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-tenant-app-layout>
