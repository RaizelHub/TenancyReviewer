<x-tenant-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">
                    Start a Chat for {{ $subject->name }}
                </h2>
                <div class="flex space-x-2">
                    <a href="{{ route('subjects.show', $subject->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:bg-gray-300 dark:focus:bg-gray-600 active:bg-gray-400 dark:active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Subject
                    </a>
                    <a href="{{ route('chat.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:bg-gray-300 dark:focus:bg-gray-600 active:bg-gray-400 dark:active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <i class="fas fa-comments mr-2"></i> All Chats
                    </a>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('subjects.chat.store', $subject->id) }}">
                        @csrf

                        <div class="mb-6">
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Chat Name
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name', $subject->name . ' Chat') }}" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Enter a name for this chat" required>
                            @error('name')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Select Students <span class="text-red-500">*</span> <span class="text-xs text-gray-500">(Minimum 3 students required)</span>
                            </label>

                            @if($students->count() > 0)
                                <div class="mt-2 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($students as $student)
                                        <div class="relative flex items-start">
                                            <div class="flex items-center h-5">
                                                <input id="student_{{ $student->id }}" name="student_ids[]" value="{{ $student->id }}" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="student_{{ $student->id }}" class="font-medium text-gray-700 dark:text-gray-300">{{ $student->name }}</label>
                                                <p class="text-gray-500 dark:text-gray-400">{{ $student->email }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="mt-4 flex items-center">
                                    <button type="button" id="select-all" class="text-sm text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">
                                        Select All
                                    </button>
                                    <span class="mx-2 text-gray-500">|</span>
                                    <button type="button" id="deselect-all" class="text-sm text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">
                                        Deselect All
                                    </button>
                                </div>

                                @error('student_ids')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                            @else
                                <div class="bg-yellow-50 dark:bg-yellow-900/30 p-4 rounded-md">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-300">No students enrolled</h3>
                                            <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-200">
                                                <p>There are no students enrolled in this subject yet. Please add students to the subject first.</p>
                                            </div>
                                            <div class="mt-4">
                                                <a href="{{ route('subjects.show', $subject->id) }}" class="text-sm font-medium text-yellow-800 dark:text-yellow-300 hover:text-yellow-700 dark:hover:text-yellow-200">
                                                    Go back to subject <span aria-hidden="true">&rarr;</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div id="selected-count" class="mb-4 text-sm text-gray-500 dark:text-gray-400">
                            0 students selected
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" id="submit-button" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 opacity-50 cursor-not-allowed" disabled>
                                <i class="fas fa-paper-plane mr-2"></i> Create Group Chat
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAllBtn = document.getElementById('select-all');
            const deselectAllBtn = document.getElementById('deselect-all');
            const checkboxes = document.querySelectorAll('input[name="student_ids[]"]');
            const selectedCountEl = document.getElementById('selected-count');
            const submitButton = document.getElementById('submit-button');
            const form = document.querySelector('form');

            // Function to update selected count and button state
            function updateSelectedCount() {
                const selectedCount = document.querySelectorAll('input[name="student_ids[]"]:checked').length;
                selectedCountEl.textContent = `${selectedCount} students selected`;

                // Update submit button state based on selection count
                if (selectedCount >= 3) {
                    submitButton.disabled = false;
                    submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
                    submitButton.classList.add('hover:bg-indigo-700');
                } else {
                    submitButton.disabled = true;
                    submitButton.classList.add('opacity-50', 'cursor-not-allowed');
                    submitButton.classList.remove('hover:bg-indigo-700');
                }
            }

            // Add event listeners to checkboxes
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateSelectedCount);
            });

            // Select all button
            if (selectAllBtn) {
                selectAllBtn.addEventListener('click', function() {
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = true;
                    });
                    updateSelectedCount();
                });
            }

            // Deselect all button
            if (deselectAllBtn) {
                deselectAllBtn.addEventListener('click', function() {
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = false;
                    });
                    updateSelectedCount();
                });
            }

            // Form validation
            if (form) {
                form.addEventListener('submit', function(e) {
                    const selectedCount = document.querySelectorAll('input[name="student_ids[]"]:checked').length;
                    if (selectedCount < 3) {
                        e.preventDefault();
                        alert('Please select at least 3 students for the group chat.');
                    }
                });
            }

            // Initialize
            updateSelectedCount();
        });
    </script>
</x-tenant-app-layout>
