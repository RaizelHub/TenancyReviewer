<x-tenant-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Activities') }}
            </h2>
            <a href="{{ route('activities.create') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Create Activity
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <!-- Filter Controls -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 mb-6">
                <form method="GET" action="{{ route('activities.index') }}">
                    <div class="flex flex-wrap gap-4">
                        <div class="w-full md:w-auto">
                            <label for="subject_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Filter by Subject</label>
                            <select name="subject_id" id="subject_id" class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 w-full">
                                <option value="">All Subjects</option>
                                @foreach(\App\Models\Subject::where('user_id', auth()->id())->orderBy('name')->get() as $subject)
                                    <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-full md:w-auto">
                            <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Filter by Type</label>
                            <select name="type" id="type" class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 w-full">
                                <option value="">All Types</option>
                                <option value="assignment" {{ request('type') == 'assignment' ? 'selected' : '' }}>Assignment</option>
                                <option value="material" {{ request('type') == 'material' ? 'selected' : '' }}>Material</option>
                                <option value="announcement" {{ request('type') == 'announcement' ? 'selected' : '' }}>Announcement</option>
                            </select>
                        </div>
                        <div class="w-full md:w-auto">
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Filter by Status</label>
                            <select name="status" id="status" class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 w-full">
                                <option value="">All Status</option>
                                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            </select>
                        </div>
                        <div class="w-full md:w-auto flex items-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                </svg>
                                Filter
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Activities List -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                @if($activities->count() > 0)
                    <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($activities as $activity)
                            <li class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <a href="{{ route('activities.show', $activity->id) }}" class="flex items-start">
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
                                        <div class="mt-2 flex items-center flex-wrap gap-2">
                                            <span class="text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full">
                                                {{ $activity->subject->name }}
                                            </span>
                                            @php
                                                $typeColors = [
                                                    'assignment' => 'purple',
                                                    'material' => 'green',
                                                    'announcement' => 'amber'
                                                ];
                                                $typeColor = $typeColors[$activity->type] ?? 'gray';
                                            @endphp
                                            <span class="text-xs bg-{{ $typeColor }}-100 text-{{ $typeColor }}-800 px-2 py-0.5 rounded-full">
                                                {{ ucfirst($activity->type) }}
                                            </span>
                                            @if($activity->due_date)
                                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                                    Due: {{ $activity->due_date->format('M d, Y') }}
                                                </span>
                                            @endif
                                            @if($activity->points)
                                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $activity->points }} points
                                                </span>
                                            @endif
                                            <span class="text-xs {{ $activity->is_published ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }} px-2 py-0.5 rounded-full">
                                                {{ $activity->is_published ? 'Published' : 'Draft' }}
                                            </span>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                        {{ $activities->links() }}
                    </div>
                @else
                    <div class="p-6 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No activities found</h3>
                        <p class="mt-1 text-gray-500 dark:text-gray-400">Create activities for your subjects to get started.</p>
                        <div class="mt-6">
                            <a href="{{ route('activities.create') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Create Activity
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-tenant-app-layout>
