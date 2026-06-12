<x-tenant-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Create New Quiz') }}
            </h2>
            <a href="{{ route('teacher-quizzes.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                </svg>
                Back to Quizzes
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 min-h-[calc(100vh-10rem)]">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('teacher-quizzes.store') }}">
                        @csrf

                        <!-- Activity Selection -->
                        <div class="mb-4">
                            <label for="activity_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Select Material</label>
                            <select name="activity_id" id="activity_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                                @if($activity)
                                    <option value="{{ $activity->id }}" selected>{{ $activity->title }} ({{ $activity->subject->name }})</option>
                                @else
                                    <option value="">-- Select a Material --</option>
                                    @foreach($activities as $activity)
                                        <option value="{{ $activity->id }}" {{ old('activity_id') == $activity->id ? 'selected' : '' }}>
                                            {{ $activity->title }} ({{ $activity->subject->name }})
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('activity_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Select the material to which this quiz will be attached. Only materials without existing quizzes are shown.
                            </p>
                        </div>

                        <!-- Title -->
                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Quiz Title</label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                            @error('title')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                            <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Time Limit -->
                        <div class="mb-4">
                            <label for="time_limit" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Time Limit (minutes)</label>
                            <input type="number" name="time_limit" id="time_limit" value="{{ old('time_limit') }}" min="1" max="180" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            @error('time_limit')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Leave empty for no time limit.
                            </p>
                        </div>

                        <!-- Passing Score -->
                        <div class="mb-4">
                            <label for="passing_score" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Passing Score (%)</label>
                            <input type="number" name="passing_score" id="passing_score" value="{{ old('passing_score', 70) }}" min="1" max="100" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                            @error('passing_score')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Options -->
                        <div class="mb-6 space-y-4">
                            <div class="flex items-center">
                                <input type="checkbox" name="is_published" id="is_published" value="1" {{ old('is_published') ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="is_published" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">Publish quiz immediately</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="show_results" id="show_results" value="1" {{ old('show_results', true) ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="show_results" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">Show results to students immediately after completion</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="randomize_questions" id="randomize_questions" value="1" {{ old('randomize_questions') ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="randomize_questions" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">Randomize question order for each attempt</label>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                Create Quiz and Add Questions
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-tenant-app-layout>
