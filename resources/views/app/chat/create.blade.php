<x-tenant-app-layout>
    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        Start a New Conversation
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Connect with your students through direct messaging
                    </p>
                </div>
                <a href="{{ route('chat.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600
                    rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600
                    focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-150">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Messages
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 dark:border-gray-700">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('chat.store') }}" id="chat-form">
                        @csrf

                        <div class="space-y-8">
                            <!-- Student Selection -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Select Students <span class="text-red-500">*</span> <span class="text-xs text-gray-500">(Minimum 3 students required)</span>
                                </label>

                                <div class="mt-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm p-4 max-h-60 overflow-y-auto">
                                    @if(count($users) > 0)
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            @foreach($users as $user)
                                                <div class="relative flex items-start">
                                                    <div class="flex items-center h-5">
                                                        <input id="student_{{ $user->id }}" name="student_ids[]" value="{{ $user->id }}" type="checkbox"
                                                            class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                                    </div>
                                                    <div class="ml-3 text-sm">
                                                        <label for="student_{{ $user->id }}" class="font-medium text-gray-700 dark:text-gray-300">{{ $user->name }}</label>
                                                        <p class="text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
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
                                    @else
                                        <p class="text-gray-500 dark:text-gray-400">No students available.</p>
                                    @endif
                                </div>

                                <div id="selected-count" class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                    0 students selected
                                </div>

                                @error('student_ids')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Chat Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Conversation Name <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-comment-alt text-gray-400 dark:text-gray-500"></i>
                                    </div>
                                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                                        class="mt-1 block w-full pl-10 pr-4 py-3 border-gray-300 dark:border-gray-700
                                        dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500
                                        rounded-lg shadow-sm" placeholder="e.g., Math Tutoring, Project Discussion" required>
                                </div>
                                @error('name')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                    Choose a descriptive name that helps you identify this conversation later.
                                </p>
                            </div>

                            <!-- Submit Button -->
                            <div class="pt-4">
                                <button type="submit"
                                    class="w-full inline-flex justify-center items-center px-6 py-3 bg-indigo-600 border
                                    border-transparent rounded-lg text-base font-medium text-white
                                    focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500
                                    transition-colors duration-150 shadow-sm opacity-50 cursor-not-allowed"
                                    disabled>
                                    <i class="fas fa-comment-dots mr-2"></i> Start Group Conversation
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const nameInput = document.getElementById('name');
            const checkboxes = document.querySelectorAll('input[name="student_ids[]"]');
            const selectAllBtn = document.getElementById('select-all');
            const deselectAllBtn = document.getElementById('deselect-all');
            const selectedCountEl = document.getElementById('selected-count');
            const submitButton = document.querySelector('button[type="submit"]');

            // Function to update selected count
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

                // Auto-suggest chat name if empty
                if (selectedCount > 0 && !nameInput.value) {
                    nameInput.value = `Group Chat (${selectedCount} students)`;
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
            const chatForm = document.getElementById('chat-form');
            if (chatForm) {
                chatForm.addEventListener('submit', function(e) {
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
