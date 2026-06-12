<x-tenant-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Create Activity') }}
            </h2>
            <a href="{{ route('activities.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                </svg>
                Back to Activities
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('activities.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Subject -->
                        <div class="mb-4">
                            <label for="subject_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Subject</label>
                            <select name="subject_id" id="subject_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">Select a Subject</option>
                                @foreach(\App\Models\Subject::where('user_id', auth()->id())->orderBy('name')->get() as $subject)
                                    <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                                @endforeach
                            </select>
                            @error('subject_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Title -->
                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Title</label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                            @error('title')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Type -->
                        <div class="mb-4">
                            <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Activity Type</label>
                            <select name="type" id="type" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="assignment" {{ old('type') == 'assignment' ? 'selected' : '' }}>Assignment</option>
                                <option value="material" {{ old('type') == 'material' ? 'selected' : '' }}>Material</option>
                                <option value="announcement" {{ old('type') == 'announcement' ? 'selected' : '' }}>Announcement</option>
                            </select>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                <span class="font-medium text-indigo-600 dark:text-indigo-400">Assignment:</span> Students can submit work for grading
                                <br>
                                <span class="font-medium text-green-600 dark:text-green-400">Material:</span> Learning resources with no submission required
                                <br>
                                <span class="font-medium text-amber-600 dark:text-amber-400">Announcement:</span> Important updates and notifications for students
                            </p>
                            @error('type')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                            <textarea name="description" id="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Points (for assignments and questions) -->
                        <div class="mb-4" id="pointsField">
                            <label for="points" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Points (Optional)</label>
                            <input type="number" name="points" id="points" value="{{ old('points') }}" min="0" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            @error('points')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Due Date (for assignments) -->
                        <div class="mb-4" id="dueDateField">
                            <label for="due_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Due Date (Optional)</label>
                            <input type="datetime-local" name="due_date" id="due_date" value="{{ old('due_date') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            @error('due_date')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Attachment -->
                        <div class="mb-6">
                            <label for="attachment" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Attachment (Optional)</label>
                            <input type="file" name="attachment" id="attachment" class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-gray-700 dark:file:text-gray-300">
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Upload documents, images, or other files related to this activity.</p>
                            @error('attachment')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Published Status -->
                        <div class="mb-6">
                            <div class="flex items-center">
                                <input type="checkbox" name="is_published" id="is_published" value="1" {{ old('is_published') ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <label for="is_published" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">Publish immediately</label>
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">If unchecked, the activity will be saved as a draft.</p>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 focus:bg-purple-700 active:bg-purple-800 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Create Activity
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const typeSelect = document.getElementById('type');
            const pointsField = document.getElementById('pointsField');
            const dueDateField = document.getElementById('dueDateField');
            const activityDocumentField = document.getElementById('activityDocumentField');
            const reviewerAttachmentField = document.getElementById('reviewerAttachmentField');

            function updateFieldVisibility() {
                const selectedType = typeSelect.value;

                // Show/hide fields based on type
                if (selectedType === 'material' || selectedType === 'announcement') {
                    // For materials and announcements, hide points and due date
                    pointsField.style.display = 'none';
                    dueDateField.style.display = 'none';

                    // Show document fields prominently for materials
                    if (activityDocumentField && selectedType === 'material') {
                        activityDocumentField.classList.add('border-green-300', 'bg-green-50', 'dark:bg-green-900/10');
                        // Show material notes
                        document.querySelectorAll('.material-note').forEach(note => {
                            note.classList.remove('hidden');
                        });
                    }

                    // For announcements, highlight differently
                    if (activityDocumentField && selectedType === 'announcement') {
                        activityDocumentField.classList.add('border-amber-300', 'bg-amber-50', 'dark:bg-amber-900/10');
                    }
                } else {
                    // For assignments, show points and due date
                    pointsField.style.display = 'block';
                    dueDateField.style.display = 'block';

                    // Reset document field styling
                    if (activityDocumentField) {
                        activityDocumentField.classList.remove('border-green-300', 'bg-green-50', 'dark:bg-green-900/10');
                        activityDocumentField.classList.remove('border-amber-300', 'bg-amber-50', 'dark:bg-amber-900/10');
                        // Hide material notes
                        document.querySelectorAll('.material-note').forEach(note => {
                            note.classList.add('hidden');
                        });
                    }
                }
            }

            // Initial update
            updateFieldVisibility();

            // Update on change
            typeSelect.addEventListener('change', updateFieldVisibility);
        });
    </script>
</x-tenant-app-layout>
