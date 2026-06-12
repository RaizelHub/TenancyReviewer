<x-tenant-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">
                    Chat Messages
                </h2>
                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="relative">
                        <input type="text" id="chat-search" placeholder="Search conversations..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
                            focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                            bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400 dark:text-gray-500"></i>
                        </div>
                    </div>

                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if(empty($channels))
                        <div class="text-center py-8">
                            <div class="text-5xl text-gray-400 dark:text-gray-600 mb-4">
                                <i class="fas fa-comments"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">No chats yet</h3>
                            <p class="text-gray-500 dark:text-gray-400">Click the "Create New Chat" button above to start a conversation with your teacher.</p>
                        </div>
                    @else
                        <!-- Subject-specific chats -->
                        @if(!empty($subjectChannels))
                            <div class="mb-8">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                    <i class="fas fa-book-open mr-2 text-indigo-600 dark:text-indigo-400"></i>
                                    Subject Chats
                                </h3>

                                <div class="space-y-6">
                                    @foreach($subjectChannels as $subjectId => $subjectData)
                                        <div class="bg-gray-50 dark:bg-gray-750 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                                            <div class="flex items-center mb-3">
                                                <div class="h-10 w-10 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400 mr-3">
                                                    <i class="fas fa-book"></i>
                                                </div>
                                                <div>
                                                    <h4 class="text-md font-semibold text-gray-900 dark:text-white">
                                                        {{ $subjectData['subject_name'] }}
                                                    </h4>
                                                    @if(!empty($subjectData['subject_code']))
                                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                                            {{ $subjectData['subject_code'] }}
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                                @foreach($subjectData['channels'] as $channel)
                                                    <div class="bg-white dark:bg-gray-700 rounded-lg shadow-sm hover:shadow
                                                        transition-all duration-200 overflow-hidden border border-gray-100 dark:border-gray-600
                                                        hover:border-indigo-200 dark:hover:border-indigo-800 group p-3">
                                                        <div class="flex items-center">
                                                            <a href="{{ route('student.chat.show', $channel['channel_url']) }}" class="flex-1 flex items-center">
                                                                <div class="h-10 w-10 rounded-full bg-indigo-100 dark:bg-indigo-900/30
                                                                    flex items-center justify-center text-indigo-600 dark:text-indigo-400 mr-3
                                                                    group-hover:bg-indigo-200 dark:group-hover:bg-indigo-800/40 transition-colors duration-200">
                                                                    <i class="fas fa-comments text-sm"></i>
                                                                </div>
                                                                <div class="flex-1 min-w-0">
                                                                    <h5 class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                                                        {{ $channel['name'] }}
                                                                    </h5>
                                                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                                                        @if(isset($channel['last_message']))
                                                                            <p class="truncate">
                                                                                {{ \Illuminate\Support\Str::limit($channel['last_message']['message'], 30) }}
                                                                            </p>
                                                                            <p>
                                                                                {{ \Carbon\Carbon::parse($channel['last_message']['created_at'])->diffForHumans() }}
                                                                            </p>
                                                                        @else
                                                                            <p>No messages yet</p>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="text-indigo-600 dark:text-indigo-400 ml-2">
                                                                    <i class="fas fa-chevron-right"></i>
                                                                </div>
                                                            </a>
                                                            <div class="ml-2 flex items-center">
                                                                <button type="button" onclick="confirmDeleteChat('{{ $channel['channel_url'] }}', '{{ $channel['name'] }}')"
                                                                    class="p-1.5 text-red-500 hover:text-white hover:bg-red-500 rounded transition-colors">
                                                                    <i class="fas fa-trash-alt"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Other chats -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                <i class="fas fa-comments mr-2 text-indigo-600 dark:text-indigo-400"></i>
                                Other Conversations
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @php
                                    $otherChannels = array_filter($channels, function($channel) {
                                        return empty($channel['processed_data']['subject_id']);
                                    });
                                @endphp

                                @if(count($otherChannels) > 0)
                                    @foreach($otherChannels as $channel)
                                        <div class="bg-white dark:bg-gray-700 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden border border-gray-200 dark:border-gray-600">
                                            <div class="p-5">
                                                <div class="flex items-center mb-3">
                                                    <a href="{{ route('student.chat.show', $channel['channel_url']) }}" class="flex-1 flex items-center">
                                                        <div class="h-10 w-10 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400 mr-3">
                                                            <i class="fas fa-comments"></i>
                                                        </div>
                                                        <div>
                                                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ $channel['name'] }}</h3>
                                                        </div>
                                                    </a>
                                                    <div class="ml-3">
                                                        <button type="button" onclick="confirmDeleteChat('{{ $channel['channel_url'] }}', '{{ $channel['name'] }}')"
                                                            class="p-1.5 text-red-500 hover:text-white hover:bg-red-500 rounded transition-colors">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                                <a href="{{ route('student.chat.show', $channel['channel_url']) }}" class="block">
                                                    <div class="mt-4 flex justify-between items-center">
                                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                                            @if(isset($channel['last_message']))
                                                                <p class="truncate">
                                                                    {{ \Illuminate\Support\Str::limit($channel['last_message']['message'], 30) }}
                                                                </p>
                                                                <p>
                                                                    {{ \Carbon\Carbon::parse($channel['last_message']['created_at'])->diffForHumans() }}
                                                                </p>
                                                            @else
                                                                <p>No messages yet</p>
                                                            @endif
                                                        </div>
                                                        <div class="text-indigo-600 dark:text-indigo-400">
                                                            <i class="fas fa-chevron-right"></i>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="col-span-full text-center py-6">
                                        <p class="text-gray-500 dark:text-gray-400">No other conversations found.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- Delete Chat Confirmation Modal -->
    <div id="deleteChatModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-md w-full p-6 transform transition-all">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 dark:bg-red-900/30 mb-4">
                    <i class="fas fa-trash-alt text-red-600 dark:text-red-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Delete Chat</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6" id="deleteChatModalText">
                    Are you sure you want to delete this chat? This action cannot be undone.
                </p>
                <div class="flex justify-center space-x-3">
                    <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                        Cancel
                    </button>
                    <form id="deleteChatForm" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Chat deletion confirmation
        function confirmDeleteChat(channelUrl, chatName) {
            const modal = document.getElementById('deleteChatModal');
            const form = document.getElementById('deleteChatForm');
            const modalText = document.getElementById('deleteChatModalText');

            // Update the form action
            form.action = `/student/chat/${channelUrl}`;

            // Update the modal text
            modalText.textContent = `Are you sure you want to delete the chat "${chatName}"? This action cannot be undone.`;

            // Show the modal
            modal.classList.remove('hidden');
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteChatModal');
            modal.classList.add('hidden');
        }

        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            const modal = document.getElementById('deleteChatModal');
            if (event.target === modal) {
                closeDeleteModal();
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('chat-search');

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase().trim();

                    // Get all subject sections
                    const subjectSections = document.querySelectorAll('.space-y-6 > div');

                    // Get all other chat cards
                    const otherChatCards = document.querySelectorAll('.grid.grid-cols-1.md\\:grid-cols-2.lg\\:grid-cols-3 > a');

                    let hasVisibleOtherChats = false;

                    // Filter subject sections
                    subjectSections.forEach(section => {
                        const subjectName = section.querySelector('h4').textContent.toLowerCase();
                        const subjectCode = section.querySelector('p')?.textContent.toLowerCase() || '';
                        const chatCards = section.querySelectorAll('.grid a');

                        let hasVisibleChats = false;

                        // Check each chat in this subject
                        chatCards.forEach(card => {
                            const chatName = card.querySelector('h5').textContent.toLowerCase();

                            if (chatName.includes(searchTerm) || subjectName.includes(searchTerm) || subjectCode.includes(searchTerm)) {
                                card.style.display = '';
                                hasVisibleChats = true;
                            } else {
                                card.style.display = 'none';
                            }
                        });

                        // Show/hide the entire subject section
                        section.style.display = hasVisibleChats ? '' : 'none';
                    });

                    // Filter other chats
                    otherChatCards.forEach(card => {
                        const chatName = card.querySelector('h3').textContent.toLowerCase();

                        if (chatName.includes(searchTerm)) {
                            card.style.display = '';
                            hasVisibleOtherChats = true;
                        } else {
                            card.style.display = 'none';
                        }
                    });

                    // Show/hide the "No other conversations found" message
                    const noOtherChatsMessage = document.querySelector('.col-span-full');
                    if (noOtherChatsMessage) {
                        noOtherChatsMessage.style.display = (!hasVisibleOtherChats && otherChatCards.length > 0) ? '' : 'none';
                    }

                    // Show/hide the subject chats heading
                    const subjectChatsHeading = document.querySelector('.mb-8 > h3');
                    if (subjectChatsHeading) {
                        const subjectChatsSection = subjectChatsHeading.closest('.mb-8');
                        const hasVisibleSubjectSections = Array.from(subjectSections).some(section => section.style.display !== 'none');
                        subjectChatsSection.style.display = hasVisibleSubjectSections ? '' : 'none';
                    }

                    // Show/hide the other chats heading
                    const otherChatsHeading = document.querySelector('div > h3:not(.mb-8 > h3)');
                    if (otherChatsHeading) {
                        const otherChatsSection = otherChatsHeading.closest('div');
                        otherChatsSection.style.display = (hasVisibleOtherChats || otherChatCards.length === 0) ? '' : 'none';
                    }
                });
            }
        });
    </script>
</x-tenant-app-layout>
