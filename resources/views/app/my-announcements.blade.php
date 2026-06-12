<x-tenant-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                <div class="p-2 rounded-lg bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400 mr-3">
                    <i class="fas fa-bullhorn"></i>
                </div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Announcements') }}
                </h2>
            </div>
            <div class="text-sm text-gray-500 dark:text-gray-400">
                {{ $announcements->count() }} {{ Str::plural('announcement', $announcements->count()) }}
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

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md rounded-xl border border-gray-100 dark:border-gray-700">
                <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gradient-to-r from-gray-50 to-white dark:from-gray-800 dark:to-gray-750">
                    <div class="flex items-center">
                        <div class="p-2 rounded-lg bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400 mr-3">
                            <i class="fas fa-bullhorn"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Announcements</h3>
                    </div>
                </div>
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if($announcements->count() > 0)
                        <div class="space-y-6">
                            @foreach($announcements as $announcement)
                                <div class="group bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-all duration-300 hover:border-amber-200 dark:hover:border-amber-900 hover-lift">
                                    <div class="h-2 bg-gradient-to-r from-amber-500 to-orange-500"></div>
                                    <div class="p-6">
                                        <div class="flex justify-between items-start mb-4">
                                            <div>
                                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors">{{ $announcement->title }}</h4>
                                                <div class="flex items-center text-xs text-gray-500 dark:text-gray-400 mt-2">
                                                    <div class="p-1 rounded-md bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 mr-2">
                                                        <i class="fas fa-book text-xs"></i>
                                                    </div>
                                                    <span>{{ $announcement->subject->name }}</span>
                                                    <span class="mx-2">•</span>
                                                    <i class="far fa-calendar-alt mr-1"></i>
                                                    <span>Posted: {{ $announcement->created_at->format('M d, Y') }}</span>
                                                </div>
                                            </div>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300">
                                                <i class="fas fa-bullhorn mr-1"></i>
                                                Announcement
                                            </span>
                                        </div>

                                        <div class="bg-amber-50 dark:bg-amber-900/10 rounded-lg p-4 mb-4 border border-amber-100 dark:border-amber-900/30">
                                            <p class="text-gray-700 dark:text-gray-300">{{ $announcement->description }}</p>
                                        </div>

                                        @if($announcement->attachment)
                                            <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-700/30 rounded-lg">
                                                <div class="flex items-center">
                                                    <div class="p-2 rounded-lg bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400 mr-3">
                                                        <i class="fas fa-paperclip"></i>
                                                    </div>
                                                    <div>
                                                        <div class="text-sm font-medium text-gray-700 dark:text-gray-300">Attachment</div>
                                                        <a href="{{ route('activities.download-attachment', ['path' => $announcement->attachment]) }}" class="text-amber-600 hover:text-amber-800 dark:text-amber-400 dark:hover:text-amber-300 text-sm font-medium flex items-center mt-1">
                                                            <i class="fas fa-download mr-1.5 text-xs"></i>
                                                            {{ basename($announcement->attachment) }}
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="flex justify-end mt-5 pt-4 border-t border-gray-100 dark:border-gray-700">
                                            <a href="{{ route('activities.show', $announcement->id) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-lg hover:from-amber-600 hover:to-orange-600 transition-all duration-200 text-sm font-medium shadow-sm">
                                                <i class="fas fa-eye mr-1.5"></i>
                                                View Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-10 text-center bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-dashed border-gray-200 dark:border-gray-700">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-amber-50 dark:bg-amber-900/20 text-amber-500 dark:text-amber-400 mb-4">
                                <i class="fas fa-bullhorn text-xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">No announcements available</h3>
                            <p class="mt-2 text-gray-500 dark:text-gray-400 max-w-md mx-auto">There are no announcements for your subjects yet. Check back later for updates from your teachers.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-tenant-app-layout>
