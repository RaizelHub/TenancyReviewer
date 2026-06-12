@php
    use Illuminate\Support\Facades\Storage;
@endphp
<x-tenant-app-layout>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/dashboard-customizer.css') }}">
    @endpush

    @push('scripts')
        <script src="{{ asset('js/dashboard-customizer.js') }}"></script>
        <script src="{{ asset('js/fix-icon-selector.js') }}"></script>
    @endpush

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Classroom Dashboard') }}
            </h2>
            <div class="flex space-x-2">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300">
                    <i class="fas fa-calendar-alt mr-1.5 customizable-icon" data-icon-id="calendar-icon"></i> {{ now()->format('F j, Y') }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="h-[calc(100vh-4rem)] overflow-hidden">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 h-full overflow-y-auto pb-4 dashboard-container">
            <!-- Welcome Banner -->
            <div class="animated-gradient rounded-xl shadow-lg mb-6 overflow-hidden dashboard-section" data-section-id="welcome-banner">
                <div class="section-header px-6 py-6 md:px-8 md:py-8 bg-black/20">
                    <div class="max-w-3xl">
                        <h2 class="text-xl md:text-2xl font-bold text-white mb-2">Welcome to Your Classroom</h2>
                        <p class="text-white/80 mb-4 text-sm md:text-base">Manage your subjects, students, and activities all in one place.</p>
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('subjects.index') }}" class="inline-flex items-center px-3 py-1.5 bg-white/90 backdrop-blur-sm text-indigo-600 rounded-lg text-sm font-medium hover:bg-white transition-colors shadow-sm">
                                <i class="fas fa-book mr-1.5 customizable-icon" data-icon-id="subjects-icon"></i>
                                Subjects
                            </a>
                            <a href="{{ route('subjects.create') }}" class="inline-flex items-center px-3 py-1.5 bg-white/90 backdrop-blur-sm text-indigo-600 rounded-lg text-sm font-medium hover:bg-white transition-colors shadow-sm">
                                <i class="fas fa-plus mr-1.5 customizable-icon" data-icon-id="new-subject-icon"></i>
                                New Subject
                            </a>
                            <a href="{{ route('students.index') }}" class="inline-flex items-center px-3 py-1.5 bg-white/90 backdrop-blur-sm text-green-600 rounded-lg text-sm font-medium hover:bg-white transition-colors shadow-sm">
                                <i class="fas fa-user-graduate mr-1.5 customizable-icon" data-icon-id="students-icon"></i>
                                Students
                            </a>
                            <a href="{{ route('activities.index') }}" class="inline-flex items-center px-3 py-1.5 bg-white/90 backdrop-blur-sm text-purple-600 rounded-lg text-sm font-medium hover:bg-white transition-colors shadow-sm">
                                <i class="fas fa-tasks mr-1.5 customizable-icon" data-icon-id="activities-icon"></i>
                                Activities
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Overview -->
            <div class="dashboard-section mb-6" data-section-id="stats-overview">
                <div class="section-header flex justify-between items-center mb-3">
                    <h3 class="text-base font-bold text-gray-800 dark:text-gray-200">Stats Overview</h3>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    @php
                        $subjectCount = \App\Models\Subject::where('user_id', auth()->id())->count();
                        $studentCount = \App\Models\Student::count();
                        $activityCount = \App\Models\Activity::whereIn('subject_id',
                            \App\Models\Subject::where('user_id', auth()->id())->pluck('id')
                        )->count();
                        $submissionCount = \App\Models\ActivitySubmission::whereIn('activity_id',
                            \App\Models\Activity::whereIn('subject_id',
                                \App\Models\Subject::where('user_id', auth()->id())->pluck('id')
                            )->pluck('id')
                        )->count();
                    @endphp

                    <!-- Subject Stats -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border border-gray-100 dark:border-gray-700 hover:shadow-md transition-shadow">
                        <div class="flex items-center">
                            <div class="p-2 rounded-lg bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 mr-3">
                                <i class="fas fa-book customizable-icon" data-icon-id="subject-stats-icon"></i>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Subjects</p>
                                <div class="flex items-end">
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $subjectCount }}</h3>
                                    <a href="{{ route('subjects.index') }}" class="ml-2 text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 text-xs">
                                        <i class="fas fa-arrow-right customizable-icon" data-icon-id="subject-arrow-icon"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Student Stats -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border border-gray-100 dark:border-gray-700 hover:shadow-md transition-shadow">
                        <div class="flex items-center">
                            <div class="p-2 rounded-lg bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 mr-3">
                                <i class="fas fa-user-graduate customizable-icon" data-icon-id="student-stats-icon"></i>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Students</p>
                                <div class="flex items-end">
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $studentCount }}</h3>
                                    <a href="{{ route('students.index') }}" class="ml-2 text-green-600 hover:text-green-800 dark:text-green-400 text-xs">
                                        <i class="fas fa-arrow-right customizable-icon" data-icon-id="student-arrow-icon"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Activity Stats -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border border-gray-100 dark:border-gray-700 hover:shadow-md transition-shadow">
                        <div class="flex items-center">
                            <div class="p-2 rounded-lg bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 mr-3">
                                <i class="fas fa-tasks customizable-icon" data-icon-id="activity-stats-icon"></i>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Activities</p>
                                <div class="flex items-end">
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $activityCount }}</h3>
                                    <a href="{{ route('activities.index') }}" class="ml-2 text-purple-600 hover:text-purple-800 dark:text-purple-400 text-xs">
                                        <i class="fas fa-arrow-right customizable-icon" data-icon-id="activity-arrow-icon"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submissions Stats -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border border-gray-100 dark:border-gray-700 hover:shadow-md transition-shadow">
                        <div class="flex items-center">
                            <div class="p-2 rounded-lg bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 mr-3">
                                <i class="fas fa-clipboard-check customizable-icon" data-icon-id="submission-stats-icon"></i>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Submissions</p>
                                <div class="flex items-end">
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $submissionCount }}</h3>
                                    <a href="{{ route('activities.index', ['view' => 'submissions']) }}" class="ml-2 text-amber-600 hover:text-amber-800 dark:text-amber-400 text-xs">
                                        <i class="fas fa-arrow-right customizable-icon" data-icon-id="submission-arrow-icon"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Subjects Section -->
            <div class="dashboard-section mb-6" data-section-id="recent-subjects">
                <div class="section-header flex justify-between items-center mb-3">
                    <h3 class="text-base font-bold text-gray-800 dark:text-gray-200">Recent Subjects</h3>
                    <a href="{{ route('subjects.create') }}" class="inline-flex items-center px-3 py-1.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-xs shadow-sm">
                        <i class="fas fa-plus mr-1.5 customizable-icon" data-icon-id="add-subject-icon"></i>
                        Add Subject
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    @php
                        $subjects = \App\Models\Subject::where('user_id', auth()->id())
                            ->orderBy('created_at', 'desc')
                            ->take(3)
                            ->get();
                    @endphp

                    @forelse($subjects as $subject)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden hover:shadow-md transition-all border border-gray-100 dark:border-gray-700">
                            <div class="h-20 relative" style="background-color: {{ $subject->color }};">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent flex flex-col justify-end p-3">
                                    <h4 class="font-semibold text-sm text-white">{{ $subject->name }}</h4>
                                    <p class="text-xs text-gray-200">{{ $subject->code }}</p>
                                </div>
                            </div>
                            <div class="p-3">
                                <div class="flex justify-between items-center mb-2 text-xs">
                                    <div class="flex items-center">
                                        <i class="fas fa-user-graduate text-gray-400 mr-1 customizable-icon" data-icon-id="subject-students-icon-{{ $subject->id }}"></i>
                                        <span class="text-gray-600 dark:text-gray-300">{{ $subject->students->count() }} students</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-tasks text-gray-400 mr-1 customizable-icon" data-icon-id="subject-activities-icon-{{ $subject->id }}"></i>
                                        <span class="text-gray-600 dark:text-gray-300">{{ $subject->activities->count() }} activities</span>
                                    </div>
                                </div>
                                <div class="flex space-x-1">
                                    <a href="{{ route('subjects.show', $subject->id) }}" class="flex-1 inline-flex items-center justify-center px-2 py-1 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors text-xs shadow-sm">
                                        <i class="fas fa-eye mr-1 customizable-icon" data-icon-id="view-subject-icon-{{ $subject->id }}"></i>
                                        View
                                    </a>
                                    <a href="{{ route('activities.create', ['subject_id' => $subject->id]) }}" class="flex-1 inline-flex items-center justify-center px-2 py-1 bg-white border border-gray-200 dark:border-gray-700 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors text-xs">
                                        <i class="fas fa-plus mr-1 customizable-icon" data-icon-id="add-activity-icon-{{ $subject->id }}"></i>
                                        Activity
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 text-center border border-gray-100 dark:border-gray-700">
                            <div class="w-10 h-10 mx-auto bg-indigo-100 dark:bg-indigo-900/30 rounded-full flex items-center justify-center mb-2">
                                <i class="fas fa-book text-indigo-600 dark:text-indigo-400 customizable-icon" data-icon-id="no-subjects-icon"></i>
                            </div>
                            <h3 class="text-sm font-medium text-gray-900 dark:text-white">No subjects yet</h3>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 max-w-md mx-auto">Get started by creating your first subject.</p>
                            <div class="mt-3">
                                <a href="{{ route('subjects.create') }}" class="inline-flex items-center px-3 py-1.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors shadow-sm text-xs">
                                    <i class="fas fa-plus mr-1.5 customizable-icon" data-icon-id="create-subject-icon"></i>
                                    Create Subject
                                </a>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Recent Activities Section -->
            <div class="dashboard-section mb-6" data-section-id="recent-activities">
                <div class="section-header flex justify-between items-center mb-3">
                    <h3 class="text-base font-bold text-gray-800 dark:text-gray-200">Recent Activities</h3>
                    <div class="flex space-x-2">
                        <a href="{{ route('activities.create') }}" class="inline-flex items-center px-3 py-1.5 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors text-xs shadow-sm">
                            <i class="fas fa-plus mr-1.5 customizable-icon" data-icon-id="add-activity-icon"></i>
                            Add
                        </a>
                        <a href="{{ route('activities.index') }}" class="inline-flex items-center px-2 py-1.5 bg-white border border-gray-200 dark:border-gray-700 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors text-xs">
                            All
                            <i class="fas fa-arrow-right ml-1.5 text-xs customizable-icon" data-icon-id="all-activities-icon"></i>
                        </a>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden border border-gray-100 dark:border-gray-700">
                    @php
                        $recentActivities = \App\Models\Activity::whereIn('subject_id',
                            \App\Models\Subject::where('user_id', auth()->id())->pluck('id')
                        )
                        ->orderBy('created_at', 'desc')
                        ->take(3)
                        ->get();
                    @endphp

                    @if($recentActivities->count() > 0)
                        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($recentActivities as $activity)
                                <li class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <a href="{{ route('activities.show', $activity->id) }}" class="flex items-start p-3">
                                        <div class="p-2 rounded-lg bg-{{ $activity->is_published ? 'green' : 'gray' }}-100 dark:bg-{{ $activity->is_published ? 'green' : 'gray' }}-900/30 text-{{ $activity->is_published ? 'green' : 'gray' }}-600 dark:text-{{ $activity->is_published ? 'green' : 'gray' }}-400 mr-3 flex-shrink-0">
                                            <i class="fas fa-{{ $activity->type === 'assignment' ? 'clipboard-list' : 'book-open' }} customizable-icon" data-icon-id="activity-type-icon-{{ $activity->id }}"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex justify-between items-start">
                                                <h4 class="font-medium text-sm text-gray-900 dark:text-white truncate pr-2">{{ $activity->title }}</h4>
                                                <span class="text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap flex-shrink-0">
                                                    {{ $activity->created_at->diffForHumans() }}
                                                </span>
                                            </div>
                                            <div class="mt-1 flex items-center flex-wrap gap-1">
                                                <span class="inline-flex items-center text-xs bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-300 px-1.5 py-0.5 rounded-full">
                                                    {{ $activity->subject->name }}
                                                </span>
                                                <span class="inline-flex items-center text-xs bg-{{ $activity->type === 'assignment' ? 'purple' : 'green' }}-100 dark:bg-{{ $activity->type === 'assignment' ? 'purple' : 'green' }}-900/30 text-{{ $activity->type === 'assignment' ? 'purple' : 'green' }}-800 dark:text-{{ $activity->type === 'assignment' ? 'purple' : 'green' }}-300 px-1.5 py-0.5 rounded-full">
                                                    {{ ucfirst($activity->type) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-2 flex-shrink-0 self-center">
                                            <i class="fas fa-chevron-right text-gray-400 text-xs customizable-icon" data-icon-id="activity-arrow-icon-{{ $activity->id }}"></i>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="p-4 text-center">
                            <div class="w-10 h-10 mx-auto bg-purple-100 dark:bg-purple-900/30 rounded-full flex items-center justify-center mb-2">
                                <i class="fas fa-tasks text-purple-600 dark:text-purple-400 customizable-icon" data-icon-id="no-activities-icon"></i>
                            </div>
                            <h3 class="text-sm font-medium text-gray-900 dark:text-white">No activities yet</h3>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 max-w-md mx-auto">Create activities for your subjects.</p>
                            <div class="mt-3">
                                <a href="{{ route('activities.create') }}" class="inline-flex items-center px-3 py-1.5 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors shadow-sm text-xs">
                                    <i class="fas fa-plus mr-1.5 customizable-icon" data-icon-id="create-activity-icon"></i>
                                    Create Activity
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="dashboard-section mb-6" data-section-id="quick-actions">
                <div class="section-header flex justify-between items-center mb-3">
                    <h3 class="text-base font-bold text-gray-800 dark:text-gray-200">Quick Actions</h3>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <!-- Create Subject -->
                    <a href="{{ route('subjects.create') }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-3 hover:shadow-md transition-all border border-gray-100 dark:border-gray-700 flex items-center text-center group">
                        <div class="p-2 rounded-lg bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 mr-3 group-hover:scale-110 transition-transform">
                            <i class="fas fa-book customizable-icon" data-icon-id="quick-subject-icon"></i>
                        </div>
                        <div class="text-left">
                            <h4 class="font-medium text-sm text-gray-900 dark:text-white">New Subject</h4>
                        </div>
                    </a>

                    <!-- Add Student -->
                    <a href="{{ route('students.create') }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-3 hover:shadow-md transition-all border border-gray-100 dark:border-gray-700 flex items-center text-center group">
                        <div class="p-2 rounded-lg bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 mr-3 group-hover:scale-110 transition-transform">
                            <i class="fas fa-user-graduate customizable-icon" data-icon-id="quick-student-icon"></i>
                        </div>
                        <div class="text-left">
                            <h4 class="font-medium text-sm text-gray-900 dark:text-white">Add Student</h4>
                        </div>
                    </a>

                    <!-- Create Activity -->
                    <a href="{{ route('activities.create') }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-3 hover:shadow-md transition-all border border-gray-100 dark:border-gray-700 flex items-center text-center group">
                        <div class="p-2 rounded-lg bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 mr-3 group-hover:scale-110 transition-transform">
                            <i class="fas fa-tasks customizable-icon" data-icon-id="quick-activity-icon"></i>
                        </div>
                        <div class="text-left">
                            <h4 class="font-medium text-sm text-gray-900 dark:text-white">New Activity</h4>
                        </div>
                    </a>

                    <!-- View Submissions -->
                    <a href="{{ route('activities.index', ['view' => 'submissions']) }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-3 hover:shadow-md transition-all border border-gray-100 dark:border-gray-700 flex items-center text-center group">
                        <div class="p-2 rounded-lg bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 mr-3 group-hover:scale-110 transition-transform">
                            <i class="fas fa-clipboard-check customizable-icon" data-icon-id="quick-submissions-icon"></i>
                        </div>
                        <div class="text-left">
                            <h4 class="font-medium text-sm text-gray-900 dark:text-white">Submissions</h4>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Students Section -->
            <div class="dashboard-section mb-6" data-section-id="recent-students">
                <div class="section-header flex justify-between items-center mb-3">
                    <h3 class="text-base font-bold text-gray-800 dark:text-gray-200">Recent Students</h3>
                    <div class="flex space-x-2">
                        <a href="{{ route('students.create') }}" class="inline-flex items-center px-3 py-1.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-xs shadow-sm">
                            <i class="fas fa-plus mr-1.5 customizable-icon" data-icon-id="add-student-icon"></i>
                            Add
                        </a>
                        <a href="{{ route('students.index') }}" class="inline-flex items-center px-2 py-1.5 bg-white border border-gray-200 dark:border-gray-700 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors text-xs">
                            All
                            <i class="fas fa-arrow-right ml-1.5 text-xs customizable-icon" data-icon-id="all-students-icon"></i>
                        </a>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden border border-gray-100 dark:border-gray-700">
                    @php
                        $recentStudents = \App\Models\Student::orderBy('created_at', 'desc')
                            ->take(3)
                            ->get();
                    @endphp

                    @if($recentStudents->count() > 0)
                        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($recentStudents as $student)
                                <li class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <a href="{{ route('students.show', $student->id) }}" class="p-3 flex items-center">
                                        <div class="flex-shrink-0 mr-3">
                                            @if($student->profile_photo)
                                                @php
                                                    $profilePhotoUrl = $student->profile_photo;
                                                    // Check if it's a Cloudinary URL or a UI Avatars URL
                                                    if (strpos($profilePhotoUrl, 'cloudinary.com') !== false ||
                                                        strpos($profilePhotoUrl, 'ui-avatars.com') !== false) {
                                                        // Use the URL directly
                                                    } else {
                                                        // If it's a local storage path, use Storage::url
                                                        $profilePhotoUrl = Storage::url($student->profile_photo);
                                                    }
                                                @endphp
                                                <img src="{{ $profilePhotoUrl }}" alt="{{ $student->name }}" class="h-10 w-10 rounded-full object-cover border border-white dark:border-gray-700 shadow-sm">
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center text-green-600 dark:text-green-400 font-semibold border border-white dark:border-gray-700 shadow-sm">
                                                    {{ substr($student->name, 0, 1) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex justify-between items-center">
                                                <h4 class="font-medium text-sm text-gray-900 dark:text-white truncate">{{ $student->name }}</h4>
                                                <span class="text-xs text-gray-500 dark:text-gray-400 ml-2 whitespace-nowrap">
                                                    {{ $student->created_at->diffForHumans() }}
                                                </span>
                                            </div>
                                            <div class="flex items-center">
                                                <p class="text-xs text-gray-600 dark:text-gray-300 truncate">{{ $student->email }}</p>
                                            </div>
                                        </div>
                                        <div class="ml-2 flex-shrink-0">
                                            <i class="fas fa-chevron-right text-gray-400 text-xs customizable-icon" data-icon-id="student-arrow-icon-{{ $student->id }}"></i>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="p-4 text-center">
                            <div class="w-10 h-10 mx-auto bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mb-2">
                                <i class="fas fa-user-graduate text-green-600 dark:text-green-400 customizable-icon" data-icon-id="no-students-icon"></i>
                            </div>
                            <h3 class="text-sm font-medium text-gray-900 dark:text-white">No students yet</h3>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 max-w-md mx-auto">Add students to your classroom.</p>
                            <div class="mt-3">
                                <a href="{{ route('students.create') }}" class="inline-flex items-center px-3 py-1.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors shadow-sm text-xs">
                                    <i class="fas fa-plus mr-1.5 customizable-icon" data-icon-id="create-student-icon"></i>
                                    Add Student
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Customization Controls -->
    <div class="fixed bottom-4 right-4 z-50 flex space-x-2">
        <button id="toggleCustomizeMode" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg shadow-lg flex items-center">
            <i class="fas fa-paint-brush mr-2"></i>
            <span>Customize</span>
        </button>
        <button id="saveCustomizations" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow-lg flex items-center hidden">
            <i class="fas fa-save mr-2"></i>
            <span>Save</span>
        </button>
        <button id="resetCustomizations" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg shadow-lg flex items-center hidden">
            <i class="fas fa-undo mr-2"></i>
            <span>Reset</span>
        </button>
    </div>

    <!-- Icon Selector Modal (Hidden by default) -->
    <div id="iconSelector" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden" style="display: none !important;">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-4xl w-full max-h-[80vh] overflow-y-auto relative">
            <!-- Emergency Close Button (always visible) -->
            <button
                onclick="document.getElementById('iconSelector').style.display = 'none'; return false;"
                class="absolute top-2 right-2 z-[9999] bg-red-600 hover:bg-red-700 text-white w-8 h-8 rounded-full shadow-sm font-bold text-lg flex items-center justify-center"
            >
                X
            </button>

            <div class="flex justify-between items-center mb-6 border-b border-gray-200 dark:border-gray-700 pb-4">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    <i class="fas fa-icons mr-2 text-blue-500"></i>
                    Select Icon
                </h3>
                <div class="flex space-x-2">
                    <button id="closeIconSelector" class="bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 px-3 py-1.5 rounded-md text-sm flex items-center"
                        onclick="document.getElementById('iconSelector').style.display = 'none'; return false;">
                        <i class="fas fa-times mr-1.5"></i> Close
                    </button>
                    <a href="{{ route('dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-md text-sm flex items-center">
                        <i class="fas fa-home mr-1.5"></i> Dashboard
                    </a>
                </div>
            </div>

            <div class="icon-grid" id="iconGrid">
                <!-- Icons will be added here dynamically by category -->
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-spinner fa-spin text-3xl mb-3"></i>
                    <p>Loading icons...</p>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700 flex justify-between">
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    <i class="fas fa-info-circle mr-1"></i>
                    Click on an icon to select it
                </div>
                <button id="cancelIconSelection" class="bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 px-4 py-2 rounded-md text-sm"
                    onclick="document.getElementById('iconSelector').style.display = 'none'; return false;">
                    Cancel
                </button>
            </div>
        </div>
    </div>

    <script>
        // Check for URL parameter to close icon selector
        if (window.location.search.includes('close_icon_selector=1')) {
            // Force close the icon selector
            setTimeout(function() {
                var iconSelector = document.getElementById('iconSelector');
                if (iconSelector) {
                    iconSelector.classList.add('hidden');
                    iconSelector.style.display = 'none';
                    console.log('Icon selector closed via URL parameter');
                }

                // Remove the parameter from URL
                var newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
                window.history.replaceState({path: newUrl}, '', newUrl);
            }, 100);
        }

        // Global function to close the icon selector (can be called from console)
        function closeIconSelector() {
            try {
                const iconSelector = document.getElementById('iconSelector');
                if (iconSelector) {
                    iconSelector.classList.add('hidden');
                    iconSelector.style.display = 'none';
                    console.log('Icon selector closed via global function');
                    return true;
                }
            } catch (e) {
                console.error('Error closing icon selector:', e);
            }

            // Fallback method
            try {
                document.querySelector('.icon-selector').style.display = 'none';
                console.log('Icon selector closed via fallback method');
                return true;
            } catch (e) {
                console.error('Error in fallback method:', e);
                return false;
            }
        }

        // Emergency function that can be called from the console
        window.emergencyCloseIconSelector = function() {
            try {
                document.getElementById('iconSelector').style.display = 'none';
                document.getElementById('iconSelector').classList.add('hidden');
                document.querySelectorAll('.icon-selector').forEach(el => {
                    el.style.display = 'none';
                    el.classList.add('hidden');
                });
                return "Icon selector should be closed now. Please refresh the page.";
            } catch (e) {
                console.error(e);
                return "Error occurred. Try refreshing the page.";
            }
        };

        document.addEventListener('DOMContentLoaded', function() {
            try {
                // Function to close the icon selector
                function closeIconSelectorModal() {
                    try {
                        const iconSelector = document.getElementById('iconSelector');
                        if (iconSelector) {
                            iconSelector.classList.add('hidden');
                            iconSelector.style.display = 'none';
                            console.log('Icon selector closed');
                        }
                    } catch (e) {
                        console.error('Error in closeIconSelectorModal:', e);
                    }
                }

                // Close the icon selector immediately if it exists
                closeIconSelectorModal();

            } catch (e) {
                console.error('Error in DOMContentLoaded:', e);
            }

            // Available icons
            const icons = [
                // Basic navigation
                'fa-home', 'fa-book', 'fa-user-graduate', 'fa-tasks', 'fa-clipboard-check',
                'fa-question-circle', 'fa-comments', 'fa-chart-bar', 'fa-calendar', 'fa-bell',

                // Settings & Admin icons
                'fa-cog', 'fa-sliders-h', 'fa-tools', 'fa-wrench', 'fa-user-cog',
                'fa-users-cog', 'fa-shield-alt', 'fa-lock', 'fa-key', 'fa-user-shield',
                'fa-cogs', 'fa-server', 'fa-database', 'fa-sitemap', 'fa-tachometer-alt',
                'fa-clipboard-list', 'fa-list-ul', 'fa-th-large', 'fa-columns', 'fa-layer-group',

                // Education specific
                'fa-users', 'fa-graduation-cap', 'fa-chalkboard-teacher', 'fa-award',
                'fa-user-tie', 'fa-school', 'fa-university', 'fa-book-open', 'fa-book-reader',
                'fa-pencil-alt', 'fa-pen', 'fa-highlighter', 'fa-edit', 'fa-chalkboard',

                // Content & Media
                'fa-star', 'fa-heart', 'fa-bookmark', 'fa-file', 'fa-folder', 'fa-image',
                'fa-video', 'fa-music', 'fa-globe', 'fa-link', 'fa-search', 'fa-envelope',
                'fa-phone', 'fa-map-marker', 'fa-clock', 'fa-chart-line', 'fa-chart-pie',

                // Actions & Feedback
                'fa-check', 'fa-check-circle', 'fa-times', 'fa-times-circle', 'fa-exclamation-circle',
                'fa-info-circle', 'fa-question', 'fa-plus', 'fa-minus', 'fa-trash', 'fa-download',
                'fa-upload', 'fa-sync', 'fa-redo', 'fa-undo', 'fa-save', 'fa-print'
            ];

            // Define icon categories
            const iconCategories = {
                'Navigation': icons.slice(0, 10),
                'Settings & Admin': icons.slice(10, 30),
                'Education': icons.slice(30, 45),
                'Content & Media': icons.slice(45, 62),
                'Actions & Feedback': icons.slice(62)
            };

            // Clear existing content
            if (iconGrid) {
                iconGrid.innerHTML = '';

                // Create category sections
                Object.entries(iconCategories).forEach(([category, categoryIcons]) => {
                    // Create category header
                    const categoryHeader = document.createElement('div');
                    categoryHeader.className = 'text-sm font-semibold text-gray-700 dark:text-gray-300 mt-4 mb-2 px-2';
                    categoryHeader.textContent = category;
                    iconGrid.appendChild(categoryHeader);

                    // Create icon container for this category
                    const categoryContainer = document.createElement('div');
                    categoryContainer.className = 'grid grid-cols-6 gap-2 mb-4';

                    // Add icons for this category
                    categoryIcons.forEach(icon => {
                        const iconOption = document.createElement('div');
                        iconOption.className = 'icon-option p-3 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg text-center';
                        iconOption.innerHTML = `<i class="fas ${icon} text-xl"></i>`;
                        iconOption.dataset.icon = icon;
                        iconOption.addEventListener('click', () => {
                            if (currentIconElement) {
                                // Remove all fa-* classes
                                [...currentIconElement.classList].forEach(cls => {
                                    if (cls.startsWith('fa-')) {
                                        currentIconElement.classList.remove(cls);
                                    }
                                });

                                // Add new icon class
                                currentIconElement.classList.add(icon);

                                // Close selector
                                closeIconSelectorModal();
                            }
                        });
                        categoryContainer.appendChild(iconOption);
                    });

                    iconGrid.appendChild(categoryContainer);
                });
            }

            // Toggle customization mode
            const toggleBtn = document.getElementById('toggleCustomizeMode');
            const saveBtn = document.getElementById('saveCustomizations');
            const resetBtn = document.getElementById('resetCustomizations');
            const iconSelector = document.getElementById('iconSelector');
            const iconGrid = document.getElementById('iconGrid');
            let currentIconElement = null;

            if (toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    const body = document.body;

                    if (body.classList.contains('customizing-mode')) {
                        // Turn off customizing mode
                        body.classList.remove('customizing-mode');
                        saveBtn.classList.add('hidden');
                        resetBtn.classList.add('hidden');
                        toggleBtn.innerHTML = '<i class="fas fa-paint-brush mr-2"></i><span>Customize</span>';
                    } else {
                        // Turn on customizing mode
                        body.classList.add('customizing-mode');
                        saveBtn.classList.remove('hidden');
                        resetBtn.classList.remove('hidden');
                        toggleBtn.innerHTML = '<i class="fas fa-times mr-2"></i><span>Exit</span>';

                        // Make sections draggable
                        initDraggableSections();
                    }
                });
            }

            // Save customizations
            if (saveBtn) {
                saveBtn.addEventListener('click', function() {
                    alert('Customizations saved!');
                    document.body.classList.remove('customizing-mode');
                    saveBtn.classList.add('hidden');
                    resetBtn.classList.add('hidden');
                    toggleBtn.innerHTML = '<i class="fas fa-paint-brush mr-2"></i><span>Customize</span>';
                });
            }

            // Reset customizations
            if (resetBtn) {
                resetBtn.addEventListener('click', function() {
                    if (confirm('Are you sure you want to reset all customizations?')) {
                        window.location.reload();
                    }
                });
            }

            // Close icon selector with the close button
            const closeIconSelectorBtn = document.getElementById('closeIconSelector');
            const cancelIconSelectionBtn = document.getElementById('cancelIconSelection');

            if (closeIconSelectorBtn) {
                closeIconSelectorBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    closeIconSelectorModal();
                });
            }

            // Close icon selector with the cancel button
            if (cancelIconSelectionBtn) {
                cancelIconSelectionBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    closeIconSelectorModal();
                });
            }

            // Close icon selector when clicking outside
            if (iconSelector) {
                iconSelector.addEventListener('click', function(e) {
                    if (e.target === this) {
                        closeIconSelectorModal();
                    }
                });
            }

            // Close icon selector with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && iconSelector && !iconSelector.classList.contains('hidden')) {
                    closeIconSelectorModal();
                }
            });

            // Make icons customizable
            document.querySelectorAll('.customizable-icon').forEach(icon => {
                icon.addEventListener('click', function(e) {
                    if (document.body.classList.contains('customizing-mode')) {
                        // Only prevent default for icons that are explicitly marked as customizable
                        if (this.closest('a') && this.dataset.iconId) {
                            e.preventDefault();
                            e.stopPropagation();

                            // Set current icon element
                            currentIconElement = this;

                            // Show icon selector
                            iconSelector.classList.remove('hidden');
                        }
                    }
                });
            });

            // Initialize draggable sections
            function initDraggableSections() {
                const sections = document.querySelectorAll('.dashboard-section');
                let draggedSection = null;

                sections.forEach(section => {
                    section.setAttribute('draggable', 'true');

                    section.addEventListener('dragstart', function(e) {
                        draggedSection = this;
                        this.style.opacity = '0.4';
                    });

                    section.addEventListener('dragend', function() {
                        this.style.opacity = '1';
                    });

                    section.addEventListener('dragover', function(e) {
                        e.preventDefault();
                    });

                    section.addEventListener('dragenter', function(e) {
                        e.preventDefault();
                        this.style.background = '#f3f4f6';
                    });

                    section.addEventListener('dragleave', function() {
                        this.style.background = '';
                    });

                    section.addEventListener('drop', function(e) {
                        e.preventDefault();
                        this.style.background = '';

                        if (draggedSection !== this) {
                            const container = document.querySelector('.dashboard-container');
                            const afterElement = this.nextElementSibling;

                            if (afterElement) {
                                container.insertBefore(draggedSection, afterElement);
                            } else {
                                container.appendChild(draggedSection);
                            }
                        }
                    });
                });
            }
        });
    </script>
</x-tenant-app-layout>
