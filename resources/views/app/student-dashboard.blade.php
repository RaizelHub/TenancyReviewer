<x-tenant-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Student Dashboard') }}
            </h2>
            <div class="flex space-x-2">
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-gradient-to-r from-blue-500 to-indigo-600 text-white shadow-sm">
                    <i class="fas fa-calendar-alt mr-1.5"></i> {{ now()->format('F j, Y') }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-0">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Card -->
            <div class="bg-gradient-to-br from-indigo-600 via-blue-600 to-purple-600 rounded-xl shadow-xl mb-8 overflow-hidden">
                <div class="px-6 py-8 md:px-10 md:py-12 relative">
                    <!-- Decorative elements -->
                    <div class="absolute top-0 right-0 w-64 h-64 opacity-10">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-full h-full text-white">
                            <path d="M11.7 2.805a.75.75 0 01.6 0A60.65 60.65 0 0122.83 8.72a.75.75 0 01-.231 1.337 49.949 49.949 0 00-9.902 3.912l-.003.002-.34.18a.75.75 0 01-.707 0A50.009 50.009 0 007.5 12.174v-.224c0-.131.067-.248.172-.311a54.614 54.614 0 014.653-2.52.75.75 0 00-.65-1.352 56.129 56.129 0 00-4.78 2.589 1.858 1.858 0 00-.859 1.228 49.803 49.803 0 00-4.634-1.527.75.75 0 01-.231-1.337A60.653 60.653 0 0111.7 2.805z" />
                            <path d="M13.06 15.473a48.45 48.45 0 017.666-3.282c.134 1.414.22 2.843.255 4.285a.75.75 0 01-.46.71 47.878 47.878 0 00-8.105 4.342.75.75 0 01-.832 0 47.877 47.877 0 00-8.104-4.342.75.75 0 01-.461-.71c.035-1.442.121-2.87.255-4.286A48.4 48.4 0 016 13.18v1.27a1.5 1.5 0 00-.14 2.508c-.09.38-.222.753-.397 1.11.452.213.901.434 1.346.661a6.729 6.729 0 00.551-1.608 1.5 1.5 0 00.14-2.67v-.645a48.549 48.549 0 013.44 1.668 2.25 2.25 0 002.12 0z" />
                            <path d="M4.462 19.462c.42-.419.753-.89 1-1.394.453.213.902.434 1.347.661a6.743 6.743 0 01-1.286 1.794.75.75 0 11-1.06-1.06z" />
                        </svg>
                    </div>
                    <!-- Floating circles -->
                    <div class="absolute -bottom-10 -left-10 w-40 h-40 rounded-full bg-white/10 backdrop-blur-sm"></div>
                    <div class="absolute top-10 right-20 w-20 h-20 rounded-full bg-white/10 backdrop-blur-sm"></div>

                    <div class="relative z-10">
                        <div class="inline-flex items-center px-3 py-1 rounded-full bg-white/20 backdrop-blur-sm text-white text-sm font-medium mb-4">
                            <i class="fas fa-graduation-cap mr-2"></i> Student Portal
                        </div>
                        <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-white mb-3 tracking-tight">Welcome back, {{ Auth::guard('student')->user()->name ?? 'Student' }}!</h2>
                        <p class="text-blue-100 mb-8 text-lg max-w-2xl leading-relaxed">Access your courses, assignments, and learning materials all in one place. Stay updated with the latest announcements from your teachers.</p>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('student.assignments') }}" class="inline-flex items-center px-5 py-2.5 bg-white text-indigo-700 rounded-lg hover:bg-blue-50 transition-colors text-sm font-medium shadow-sm">
                                <i class="fas fa-tasks mr-2"></i> View Assignments
                            </a>
                            <a href="{{ route('student.materials') }}" class="inline-flex items-center px-5 py-2.5 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 transition-colors text-sm font-medium shadow-sm">
                                <i class="fas fa-book-open mr-2"></i> Browse Materials
                            </a>
                            <a href="{{ route('student.subjects') }}" class="inline-flex items-center px-5 py-2.5 bg-purple-500 text-white rounded-lg hover:bg-purple-600 transition-colors text-sm font-medium shadow-sm">
                                <i class="fas fa-book mr-2"></i> My Subjects
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-shadow duration-300 group">
                    <div class="flex items-center">
                        <div class="p-4 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 text-white mr-5 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-book text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Enrolled Subjects</p>
                            <div class="flex items-end">
                                <h3 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $subjects->count() }}</h3>
                                <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">courses</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                        <a href="{{ route('student.subjects') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 flex items-center">
                            <span>View all subjects</span>
                            <i class="fas fa-arrow-right ml-1.5 text-xs"></i>
                        </a>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-shadow duration-300 group">
                    <div class="flex items-center">
                        <div class="p-4 rounded-xl bg-gradient-to-br from-green-500 to-emerald-600 text-white mr-5 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-check-circle text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Completed Assignments</p>
                            <div class="flex items-end">
                                <h3 class="text-3xl font-bold text-gray-900 dark:text-white">
                                    @php
                                        $completedAssignments = 0;
                                        foreach($subjects as $subject) {
                                            $completedAssignments += $subject->activities->where('type', 'assignment')->count();
                                        }
                                    @endphp
                                    {{ $completedAssignments }}
                                </h3>
                                <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">tasks</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                        <a href="{{ route('student.assignments') }}" class="text-sm text-green-600 dark:text-green-400 hover:text-green-700 dark:hover:text-green-300 flex items-center">
                            <span>View assignments</span>
                            <i class="fas fa-arrow-right ml-1.5 text-xs"></i>
                        </a>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-shadow duration-300 group">
                    <div class="flex items-center">
                        <div class="p-4 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 text-white mr-5 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-bullhorn text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">New Announcements</p>
                            <div class="flex items-end">
                                <h3 class="text-3xl font-bold text-gray-900 dark:text-white">
                                    @php
                                        $announcements = 0;
                                        foreach($subjects as $subject) {
                                            $announcements += $subject->activities->where('type', 'announcement')->count();
                                        }
                                    @endphp
                                    {{ $announcements }}
                                </h3>
                                <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">updates</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                        <a href="{{ route('student.announcements') }}" class="text-sm text-amber-600 dark:text-amber-400 hover:text-amber-700 dark:hover:text-amber-300 flex items-center">
                            <span>View announcements</span>
                            <i class="fas fa-arrow-right ml-1.5 text-xs"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- My Subjects -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden border border-gray-100 dark:border-gray-700 mb-8">
                <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gradient-to-r from-gray-50 to-white dark:from-gray-800 dark:to-gray-750">
                    <div class="flex items-center">
                        <div class="p-2 rounded-lg bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400 mr-3">
                            <i class="fas fa-book"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">My Subjects</h3>
                    </div>
                    <a href="{{ route('student.subjects') }}" class="inline-flex items-center px-3 py-1.5 bg-blue-50 hover:bg-blue-100 dark:bg-blue-900/20 dark:hover:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-lg text-sm font-medium transition-colors">
                        <span>View All</span>
                        <i class="fas fa-arrow-right ml-1.5 text-xs"></i>
                    </a>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($subjects as $subject)
                            <div class="group bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-all duration-300 hover:border-blue-200 dark:hover:border-blue-900">
                                <div class="h-28 relative" style="background-color: {{ $subject->color ?? '#4f46e5' }};">
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
                                    <a href="{{ route('student.subject.show', $subject->id) }}" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 text-sm font-medium shadow-sm">
                                        <i class="fas fa-eye mr-1.5"></i>
                                        View Details
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full p-10 text-center bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-dashed border-gray-200 dark:border-gray-700">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-50 dark:bg-blue-900/20 text-blue-500 dark:text-blue-400 mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">No subjects yet</h3>
                                <p class="mt-2 text-gray-500 dark:text-gray-400 max-w-md mx-auto">You are not enrolled in any classes yet. Contact your teacher to get enrolled in subjects.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Recent Announcements -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden border border-gray-100 dark:border-gray-700 mb-8">
                <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gradient-to-r from-gray-50 to-white dark:from-gray-800 dark:to-gray-750">
                    <div class="flex items-center">
                        <div class="p-2 rounded-lg bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400 mr-3">
                            <i class="fas fa-bullhorn"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Announcements</h3>
                    </div>
                    <a href="{{ route('student.announcements') }}" class="inline-flex items-center px-3 py-1.5 bg-amber-50 hover:bg-amber-100 dark:bg-amber-900/20 dark:hover:bg-amber-900/30 text-amber-600 dark:text-amber-400 rounded-lg text-sm font-medium transition-colors">
                        <span>View All</span>
                        <i class="fas fa-arrow-right ml-1.5 text-xs"></i>
                    </a>
                </div>

                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    @php
                        $recentAnnouncements = collect();
                        foreach($subjects as $subject) {
                            $announcements = $subject->activities->where('type', 'announcement')->take(3);
                            foreach($announcements as $announcement) {
                                $announcement->subject = $subject;
                                $recentAnnouncements->push($announcement);
                            }
                        }
                        $recentAnnouncements = $recentAnnouncements->sortByDesc('created_at')->take(3);
                    @endphp

                    @forelse($recentAnnouncements as $announcement)
                        <div class="p-5 hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 mr-4">
                                    <div class="w-10 h-10 rounded-full bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center text-amber-600 dark:text-amber-400">
                                        <i class="fas fa-bullhorn"></i>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between mb-1">
                                        <h4 class="text-base font-medium text-gray-900 dark:text-white truncate">{{ $announcement->title }}</h4>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ $announcement->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-2 mb-2">{{ $announcement->description }}</p>
                                    <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300">
                                            {{ $announcement->subject->name }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center">
                            <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-amber-100 dark:bg-amber-900/20 text-amber-500 dark:text-amber-400 mb-3">
                                <i class="fas fa-bell-slash"></i>
                            </div>
                            <h4 class="text-base font-medium text-gray-900 dark:text-white">No announcements yet</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Check back later for updates from your teachers.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-tenant-app-layout>