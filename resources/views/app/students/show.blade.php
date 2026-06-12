<x-tenant-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                <div class="p-2 rounded-full bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400 mr-3">
                    <i class="fas fa-user-graduate text-lg"></i>
                </div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ $student->name }}
                </h2>
                <span class="ml-3 text-sm {{ $student->plan == 'premium' ? 'bg-gradient-to-r from-purple-500 to-indigo-500 text-white' : 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300' }} px-2.5 py-1 rounded-full shadow-sm">
                    @if($student->plan == 'premium')
                        <i class="fas fa-crown text-amber-300 mr-1"></i>
                    @endif
                    {{ ucfirst($student->plan) }}
                </span>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('students.edit', $student->id) }}" class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-amber-500 to-yellow-500 text-white rounded-xl hover:from-amber-600 hover:to-yellow-600 shadow-sm hover:shadow transition-all duration-200">
                    <i class="fas fa-pencil-alt mr-2"></i>
                    Edit Student
                </a>
                <a href="{{ route('students.index') }}" class="inline-flex items-center px-4 py-2.5 bg-gray-600 text-white rounded-xl hover:bg-gray-700 shadow-sm hover:shadow transition-all duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Students
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

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Student Profile -->
                <div class="md:col-span-1">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-xl border border-gray-100 dark:border-gray-700">
                        <div class="p-6">
                            <div class="flex flex-col items-center">
                                @if($student->profile_photo)
                                    <img src="{{ $student->profile_photo }}" alt="{{ $student->name }}" class="h-36 w-36 rounded-full object-cover mb-5 border-4 border-white dark:border-gray-700 shadow-md">
                                @else
                                    <div class="h-36 w-36 rounded-full bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-900/30 dark:to-blue-800/30 flex items-center justify-center text-blue-600 dark:text-blue-400 font-semibold text-5xl mb-5 border-4 border-white dark:border-gray-700 shadow-md">
                                        {{ substr($student->name, 0, 1) }}
                                    </div>
                                @endif
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $student->name }}</h3>
                                <p class="text-gray-600 dark:text-gray-300 mt-1 flex items-center">
                                    <i class="fas fa-envelope text-gray-400 dark:text-gray-500 mr-1.5"></i>
                                    {{ $student->email }}
                                </p>
                                @if($student->student_id)
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 flex items-center">
                                        <i class="fas fa-id-card text-gray-400 dark:text-gray-500 mr-1.5"></i>
                                        ID: {{ $student->student_id }}
                                    </p>
                                @endif
                            </div>

                            <div class="mt-8 border-t border-gray-200 dark:border-gray-700 pt-5">
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3 flex items-center">
                                    <i class="fas fa-info-circle mr-1.5"></i>
                                    Student Information
                                </h4>
                                <div class="space-y-4">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 rounded-full bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center text-blue-500 dark:text-blue-400 mr-3">
                                            <i class="fas fa-tag"></i>
                                        </div>
                                        <div>
                                            <span class="text-sm text-gray-500 dark:text-gray-400 block">Plan</span>
                                            <span class="text-sm font-medium text-gray-900 dark:text-white">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $student->plan == 'premium' ? 'bg-gradient-to-r from-purple-500 to-indigo-500 text-white' : 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300' }}">
                                                    @if($student->plan == 'premium')
                                                        <i class="fas fa-crown text-amber-300 mr-1"></i>
                                                    @endif
                                                    {{ ucfirst($student->plan) }}
                                                </span>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 rounded-full bg-green-50 dark:bg-green-900/20 flex items-center justify-center text-green-500 dark:text-green-400 mr-3">
                                            <i class="fas fa-book"></i>
                                        </div>
                                        <div>
                                            <span class="text-sm text-gray-500 dark:text-gray-400 block">Enrolled in</span>
                                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $student->subjects->count() }} {{ Str::plural('subject', $student->subjects->count()) }}</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 rounded-full bg-amber-50 dark:bg-amber-900/20 flex items-center justify-center text-amber-500 dark:text-amber-400 mr-3">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                        <div>
                                            <span class="text-sm text-gray-500 dark:text-gray-400 block">Joined</span>
                                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $student->created_at->format('M d, Y') }}</span>
                                        </div>
                                    </div>
                                    @if($student->notes)
                                        <div class="flex items-start pt-2">
                                            <div class="w-8 h-8 rounded-full bg-purple-50 dark:bg-purple-900/20 flex items-center justify-center text-purple-500 dark:text-purple-400 mr-3 mt-0.5">
                                                <i class="fas fa-sticky-note"></i>
                                            </div>
                                            <div>
                                                <span class="text-sm text-gray-500 dark:text-gray-400 block mb-1">Notes</span>
                                                <p class="text-sm text-gray-900 dark:text-white whitespace-pre-line bg-gray-50 dark:bg-gray-700/50 p-3 rounded-lg">{{ $student->notes }}</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Student Details -->
                <div class="md:col-span-2">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-xl border border-gray-100 dark:border-gray-700" x-data="{ activeTab: 'subjects' }">
                        <div class="border-b border-gray-200 dark:border-gray-700">
                            <nav class="flex">
                                <button @click="activeTab = 'subjects'" :class="{ 'border-blue-500 text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/10': activeTab === 'subjects', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300': activeTab !== 'subjects' }" class="w-1/2 py-4 px-1 text-center border-b-2 font-medium text-sm transition-all duration-200">
                                    <i class="fas fa-book mr-1.5"></i>
                                    Enrolled Subjects
                                </button>
                                <button @click="activeTab = 'submissions'" :class="{ 'border-blue-500 text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/10': activeTab === 'submissions', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300': activeTab !== 'submissions' }" class="w-1/2 py-4 px-1 text-center border-b-2 font-medium text-sm transition-all duration-200">
                                    <i class="fas fa-file-alt mr-1.5"></i>
                                    Activity Submissions
                                </button>
                            </nav>
                        </div>

                        <!-- Subjects Tab -->
                        <div x-show="activeTab === 'subjects'" x-transition class="p-6">
                            <div class="flex justify-between items-center mb-5">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                    <i class="fas fa-book text-blue-500 dark:text-blue-400 mr-2"></i>
                                    Enrolled Subjects
                                </h3>
                                <button type="button" onclick="document.getElementById('addSubjectsModal').classList.remove('hidden')" class="inline-flex items-center px-3.5 py-2 bg-gradient-to-r from-blue-500 to-indigo-500 text-white rounded-lg hover:from-blue-600 hover:to-indigo-600 shadow-sm hover:shadow transition-all duration-200 text-sm">
                                    <i class="fas fa-plus mr-1.5"></i>
                                    Enroll in Subjects
                                </button>
                            </div>

                            @if($student->subjects->count() > 0)
                                <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($student->subjects as $subject)
                                        <li class="py-4 hover:bg-gray-50/50 dark:hover:bg-gray-700/50 -mx-6 px-6 transition-colors duration-200">
                                            <div class="flex justify-between items-center">
                                                <div class="flex items-center">
                                                    <div class="w-1.5 h-12 rounded-l-md mr-3" style="background-color: {{ $subject->color }};"></div>
                                                    <div class="h-10 w-10 rounded-lg flex items-center justify-center mr-3" style="background-color: {{ $subject->color }}20;">
                                                        <i class="fas fa-book text-lg" style="color: {{ $subject->color }};"></i>
                                                    </div>
                                                    <div>
                                                        <h4 class="text-base font-medium text-gray-900 dark:text-white">{{ $subject->name }}</h4>
                                                        <p class="text-sm text-gray-500 dark:text-gray-400 flex items-center">
                                                            @if($subject->code)
                                                                <span class="mr-3"><i class="fas fa-hashtag text-xs mr-1 text-gray-400 dark:text-gray-500"></i> {{ $subject->code }}</span>
                                                            @endif
                                                            <span><i class="fas fa-tasks text-xs mr-1 text-gray-400 dark:text-gray-500"></i> {{ $subject->activities->count() }} {{ Str::plural('activity', $subject->activities->count()) }}</span>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    <a href="{{ route('subjects.show', $subject->id) }}" class="p-2 text-blue-600 hover:text-white bg-blue-50 hover:bg-blue-600 dark:bg-blue-900/20 dark:text-blue-400 dark:hover:bg-blue-500 dark:hover:text-white rounded-lg transition-colors">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <form method="POST" action="{{ route('subjects.students.remove', [$subject->id, $student->id]) }}" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" onclick="return confirm('Are you sure you want to remove this student from the subject?')" class="p-2 text-red-600 hover:text-white bg-red-50 hover:bg-red-600 dark:bg-red-900/20 dark:text-red-400 dark:hover:bg-red-500 dark:hover:text-white rounded-lg transition-colors">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <div class="text-center py-8">
                                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-50 dark:bg-blue-900/20 text-blue-500 dark:text-blue-400 mb-4">
                                        <i class="fas fa-book text-2xl"></i>
                                    </div>
                                    <h3 class="mt-2 text-xl font-medium text-gray-900 dark:text-white">No subjects enrolled</h3>
                                    <p class="mt-2 text-gray-500 dark:text-gray-400 max-w-md mx-auto">Enroll this student in subjects to start managing their coursework and assignments.</p>
                                    <div class="mt-6">
                                        <button type="button" onclick="document.getElementById('addSubjectsModal').classList.remove('hidden')" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-blue-500 to-indigo-500 text-white rounded-xl hover:from-blue-600 hover:to-indigo-600 shadow-sm hover:shadow transition-all duration-200">
                                            <i class="fas fa-plus mr-2"></i>
                                            Enroll in Subjects
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Submissions Tab -->
                        <div x-show="activeTab === 'submissions'" x-transition class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-5 flex items-center">
                                <i class="fas fa-file-alt text-blue-500 dark:text-blue-400 mr-2"></i>
                                Activity Submissions
                            </h3>

                            @php
                                $submissions = \App\Models\Submission::where('student_id', $student->id)
                                    ->orderBy('created_at', 'desc')
                                    ->with(['activity', 'activity.subject'])
                                    ->get();
                            @endphp

                            @if($submissions->count() > 0)
                                <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($submissions as $submission)
                                        <li class="py-4 hover:bg-gray-50/50 dark:hover:bg-gray-700/50 -mx-6 px-6 transition-colors duration-200">
                                            <a href="{{ route('submissions.show', $submission) }}" class="flex items-start rounded-lg">
                                                <div class="p-3 rounded-lg {{ $submission->status === 'graded' ? 'bg-green-50 text-green-600 dark:bg-green-900/20 dark:text-green-400' : 'bg-amber-50 text-amber-600 dark:bg-amber-900/20 dark:text-amber-400' }} mr-4 flex-shrink-0">
                                                    <i class="fas {{ $submission->status === 'graded' ? 'fa-check-circle' : 'fa-clock' }}"></i>
                                                </div>
                                                <div class="flex-1">
                                                    <div class="flex justify-between">
                                                        <h4 class="font-medium text-gray-900 dark:text-white text-base">{{ $submission->activity->title }}</h4>
                                                        <span class="text-xs text-gray-500 dark:text-gray-400 flex items-center">
                                                            <i class="fas fa-calendar-alt mr-1"></i>
                                                            {{ $submission->created_at->format('M d, Y') }}
                                                        </span>
                                                    </div>
                                                    <p class="text-sm text-gray-600 dark:text-gray-300 mt-1 flex items-center">
                                                        <i class="fas fa-book text-xs mr-1.5 text-gray-400 dark:text-gray-500"></i>
                                                        {{ $submission->activity->subject->name }}
                                                    </p>
                                                    <div class="mt-2 flex items-center">
                                                        <span class="text-xs font-medium {{ $submission->status === 'graded' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300' }} px-2.5 py-1 rounded-full">
                                                            {{ ucfirst($submission->status) }}
                                                        </span>
                                                        @if($submission->status === 'graded')
                                                            <span class="text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300 px-2.5 py-1 rounded-full ml-2 flex items-center">
                                                                <i class="fas fa-star text-amber-400 mr-1"></i>
                                                                Score: {{ $submission->grade }} / {{ $submission->activity->points ?? '100' }}
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
                                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-50 dark:bg-blue-900/20 text-blue-500 dark:text-blue-400 mb-4">
                                        <i class="fas fa-file-alt text-2xl"></i>
                                    </div>
                                    <h3 class="mt-2 text-xl font-medium text-gray-900 dark:text-white">No submissions yet</h3>
                                    <p class="mt-2 text-gray-500 dark:text-gray-400 max-w-md mx-auto">This student hasn't submitted any activities or assignments yet.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Subjects Modal -->
    <div id="addSubjectsModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-md w-full mx-4 border border-gray-100 dark:border-gray-700">
            <div class="p-6">
                <div class="flex justify-between items-center mb-5">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                        <i class="fas fa-book text-blue-500 dark:text-blue-400 mr-2"></i>
                        Enroll in Subjects
                    </h3>
                    <button type="button" onclick="document.getElementById('addSubjectsModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-500 p-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form method="POST" action="{{ route('students.enroll', $student->id) }}">
                    @csrf
                    <div class="mb-5">
                        <label for="subject_ids" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select Subjects</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400 dark:text-gray-500"></i>
                            </div>
                            <select name="subject_ids[]" id="subject_ids" class="pl-10 block w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm" multiple size="6">
                                @php
                                    $enrolledSubjectIds = $student->subjects->pluck('id')->toArray();
                                    $availableSubjects = \App\Models\Subject::where('user_id', auth()->id())
                                        ->whereNotIn('id', $enrolledSubjectIds)
                                        ->orderBy('name')
                                        ->get();
                                @endphp
                                @foreach($availableSubjects as $subject)
                                    <option value="{{ $subject->id }}" class="py-2">{{ $subject->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 flex items-center">
                            <i class="fas fa-info-circle mr-1"></i>
                            Hold Ctrl (or Cmd) to select multiple subjects
                        </p>
                        @if($availableSubjects->count() === 0)
                            <div class="mt-4 p-4 bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 rounded-lg flex items-start">
                                <i class="fas fa-info-circle mt-0.5 mr-2"></i>
                                <div>
                                    <p class="text-sm">No more subjects available to enroll in.</p>
                                    <a href="{{ route('subjects.create') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-medium inline-flex items-center mt-2">
                                        <i class="fas fa-plus mr-1"></i>
                                        Create new subject
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="document.getElementById('addSubjectsModal').classList.add('hidden')" class="inline-flex items-center px-4 py-2.5 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg font-medium text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 transition-all duration-200">
                            Cancel
                        </button>
                        <button type="submit" class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-blue-500 to-indigo-500 text-white rounded-lg hover:from-blue-600 hover:to-indigo-600 shadow-sm hover:shadow font-medium text-sm transition-all duration-200">
                            <i class="fas fa-check mr-1.5"></i>
                            Enroll
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-tenant-app-layout>
