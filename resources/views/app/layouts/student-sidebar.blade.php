@php
    use Illuminate\Support\Facades\Auth;
@endphp
<aside x-data="{ open: true, activeMenu: '{{ request()->route()->getName() }}' }" class="w-64 bg-white dark:bg-gray-800 shadow-md flex flex-col border-r border-gray-200 dark:border-gray-700 relative h-full">
    <!-- Toggle Button -->
    <button @click="open = !open" class="absolute -right-3 top-20 bg-white dark:bg-gray-800 rounded-full p-1.5 shadow-md border border-gray-200 dark:border-gray-700 focus:outline-none z-10 transition-transform duration-300" :class="{'rotate-180': !open}">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
    </button>

    <!-- Logo and Brand -->
    <div class="flex items-center py-4 px-4 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center space-x-3">
            <div class="h-10 w-10 rounded-full bg-indigo-600 flex items-center justify-center">
                <span class="text-lg font-bold text-white">B</span>
            </div>
            <div x-show="open" class="transition-all duration-300 ease-in-out" x-transition>
                <div class="text-gray-800 dark:text-white text-lg font-bold">Bukidnon State</div>
                <div class="text-gray-500 dark:text-gray-400 text-xs">University</div>
                <div class="text-xs text-indigo-600 dark:text-indigo-400 flex items-center mt-1">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"></path>
                    </svg>
                    <span>Classroom</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 overflow-y-auto py-4 px-3">
        <div class="mb-2 px-3">
            <h3 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">MAIN MENU</h3>
        </div>
        <div class="space-y-1">
            <a href="{{ route('student.dashboard') }}"
               class="group flex items-center px-3 py-2 rounded-lg font-medium transition-all duration-200"
               :class="activeMenu === 'student.dashboard' ? 'bg-indigo-600 text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 hover:text-indigo-600 dark:hover:text-indigo-400'">
                <div class="flex items-center justify-center w-8 h-8 rounded-lg" :class="activeMenu === 'student.dashboard' ? 'bg-white/20 text-white' : 'bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 group-hover:bg-indigo-200 dark:group-hover:bg-indigo-800/40'">
                    <i class="fas fa-home"></i>
                </div>
                <span x-show="open" class="ml-3 transition-all duration-300 ease-in-out" x-transition>Dashboard</span>
            </a>

            <a href="{{ route('student.subjects') }}"
               class="group flex items-center px-3 py-2 rounded-lg font-medium transition-all duration-200"
               :class="activeMenu === 'student.subjects' ? 'bg-indigo-600 text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 hover:text-indigo-600 dark:hover:text-indigo-400'">
                <div class="flex items-center justify-center w-8 h-8 rounded-lg" :class="activeMenu === 'student.subjects' ? 'bg-white/20 text-white' : 'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 group-hover:bg-blue-200 dark:group-hover:bg-blue-800/40'">
                    <i class="fas fa-book"></i>
                </div>
                <span x-show="open" class="ml-3 transition-all duration-300 ease-in-out" x-transition>My Subjects</span>
            </a>

            <a href="{{ route('student.assignments') }}"
               class="group flex items-center px-3 py-2 rounded-lg font-medium transition-all duration-200"
               :class="activeMenu === 'student.assignments' ? 'bg-indigo-600 text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 hover:text-indigo-600 dark:hover:text-indigo-400'">
                <div class="flex items-center justify-center w-8 h-8 rounded-lg" :class="activeMenu === 'student.assignments' ? 'bg-white/20 text-white' : 'bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 group-hover:bg-green-200 dark:group-hover:bg-green-800/40'">
                    <i class="fas fa-tasks"></i>
                </div>
                <div class="flex items-center" x-show="open" x-transition>
                    <span class="ml-3 transition-all duration-300 ease-in-out">To-Do List</span>
                    @php
                        $pendingAssignments = 0;
                        $student = Auth::guard('student')->user();
                        if ($student) {
                            // Get all subjects for the student
                            $subjects = $student->subjects;

                            // Loop through each subject
                            foreach($subjects as $subject) {
                                // Get assignments that are due in the future
                                $assignments = $subject->activities()
                                    ->where('type', 'assignment')
                                    ->where('due_date', '>', now())
                                    ->get();

                                // For each assignment, check if the student has already submitted it
                                foreach($assignments as $assignment) {
                                    $hasSubmission = $student->submissions()
                                        ->where('activity_id', $assignment->id)
                                        ->exists();

                                    // Only count assignments that haven't been submitted yet
                                    if (!$hasSubmission) {
                                        $pendingAssignments++;
                                    }
                                }
                            }
                        }
                    @endphp
                    @if($pendingAssignments > 0)
                        <span class="ml-auto inline-flex items-center justify-center min-w-[20px] h-5 px-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300">
                            {{ $pendingAssignments }}
                        </span>
                    @endif
                </div>
            </a>

            <a href="{{ route('student.materials') }}"
               class="group flex items-center px-3 py-2 rounded-lg font-medium transition-all duration-200"
               :class="activeMenu === 'student.materials' ? 'bg-indigo-600 text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 hover:text-indigo-600 dark:hover:text-indigo-400'">
                <div class="flex items-center justify-center w-8 h-8 rounded-lg" :class="activeMenu === 'student.materials' ? 'bg-white/20 text-white' : 'bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 group-hover:bg-purple-200 dark:group-hover:bg-purple-800/40'">
                    <i class="fas fa-book-open"></i>
                </div>
                <span x-show="open" class="ml-3 transition-all duration-300 ease-in-out" x-transition>Materials</span>
            </a>

            <a href="{{ route('student.announcements') }}"
               class="group flex items-center px-3 py-2 rounded-lg font-medium transition-all duration-200"
               :class="activeMenu === 'student.announcements' ? 'bg-indigo-600 text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 hover:text-indigo-600 dark:hover:text-indigo-400'">
                <div class="flex items-center justify-center w-8 h-8 rounded-lg" :class="activeMenu === 'student.announcements' ? 'bg-white/20 text-white' : 'bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 group-hover:bg-amber-200 dark:group-hover:bg-amber-800/40'">
                    <i class="fas fa-bullhorn"></i>
                </div>
                <div class="flex items-center" x-show="open" x-transition>
                    <span class="ml-3 transition-all duration-300 ease-in-out">Announcements</span>
                    @php
                        $newAnnouncements = 0;
                        $student = Auth::guard('student')->user();
                        if ($student) {
                            foreach($student->subjects as $subject) {
                                $newAnnouncements += $subject->activities->where('type', 'announcement')->count();
                            }
                        }
                    @endphp
                    @if($newAnnouncements > 0)
                        <span class="ml-auto inline-flex items-center justify-center min-w-[20px] h-5 px-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-300">
                            {{ $newAnnouncements }}
                        </span>
                    @endif
                </div>
            </a>

            <a href="{{ route('quizzes.index') }}"
               class="group flex items-center px-3 py-2 rounded-lg font-medium transition-all duration-200"
               :class="activeMenu === 'quizzes.index' ? 'bg-indigo-600 text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 hover:text-indigo-600 dark:hover:text-indigo-400'">
                <div class="flex items-center justify-center w-8 h-8 rounded-lg" :class="activeMenu === 'quizzes.index' ? 'bg-white/20 text-white' : 'bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 group-hover:bg-red-200 dark:group-hover:bg-red-800/40'">
                    <i class="fas fa-question-circle"></i>
                </div>
                <div class="flex items-center" x-show="open" x-transition>
                    <span class="ml-3 transition-all duration-300 ease-in-out">Quizzes</span>
                    @if(Auth::guard('student')->user()->plan !== 'premium')
                        <span class="ml-2 inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-300">
                            <i class="fas fa-crown text-amber-500 mr-1 text-xs"></i> Premium
                        </span>
                    @endif
                </div>
            </a>

            <a href="{{ route('student.chat.index') }}"
               class="group flex items-center px-3 py-2 rounded-lg font-medium transition-all duration-200"
               :class="activeMenu === 'student.chat.index' || activeMenu === 'student.chat.show' || activeMenu === 'student.chat.create' ? 'bg-indigo-600 text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 hover:text-indigo-600 dark:hover:text-indigo-400'">
                <div class="flex items-center justify-center w-8 h-8 rounded-lg" :class="activeMenu === 'student.chat.index' || activeMenu === 'student.chat.show' || activeMenu === 'student.chat.create' ? 'bg-white/20 text-white' : 'bg-teal-100 dark:bg-teal-900/30 text-teal-600 dark:text-teal-400 group-hover:bg-teal-200 dark:group-hover:bg-teal-800/40'">
                    <i class="fas fa-comments"></i>
                </div>
                <span x-show="open" class="ml-3 transition-all duration-300 ease-in-out" x-transition>Chat</span>
            </a>
        </div>

        <!-- Version Info -->
        <div class="mt-8 px-4 py-3 text-xs text-gray-400 dark:text-gray-500" x-show="open">
            <div class="flex items-center">
                <div class="w-2 h-2 rounded-full bg-green-400 mr-2"></div>
                <span>Student Portal v1.0</span>
            </div>
        </div>
    </nav>
</aside>