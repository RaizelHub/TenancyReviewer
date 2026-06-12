<x-tenant-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-gray-900 dark:text-white flex items-center">
                    <a href="{{ route('student.chat.index') }}" class="mr-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    {{ $channel['name'] }}
                </h2>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Chat interface -->
                    <div class="flex flex-col h-[600px]">
                        <!-- Chat messages area -->
                        <div id="chat-messages" class="flex-1 overflow-y-auto mb-4 p-4 bg-gray-50 dark:bg-gray-900 rounded-lg">
                            @if(empty($messages))
                                <div class="text-center py-8">
                                    <div class="text-5xl text-gray-400 dark:text-gray-600 mb-4">
                                        <i class="fas fa-comments"></i>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">No messages yet</h3>
                                    <p class="text-gray-500 dark:text-gray-400">Start the conversation by sending a message below</p>
                                </div>
                            @else
                                @foreach($messages as $message)
                                    <div class="mb-4 {{ $message['user']['user_id'] == $student->id ? 'ml-auto' : 'mr-auto' }} max-w-[80%]">
                                        <div class="flex {{ $message['user']['user_id'] == $student->id ? 'flex-row-reverse' : 'flex-row' }} items-start">
                                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400 {{ $message['user']['user_id'] == $student->id ? 'ml-3' : 'mr-3' }}">
                                                @if(isset($message['user']['profile_url']) && $message['user']['profile_url'])
                                                    <img src="{{ $message['user']['profile_url'] }}" alt="{{ $message['user']['nickname'] }}" class="h-10 w-10 rounded-full">
                                                @else
                                                    {{ strtoupper(substr($message['user']['nickname'], 0, 1)) }}
                                                @endif
                                            </div>
                                            <div>
                                                <div class="flex items-center {{ $message['user']['user_id'] == $student->id ? 'justify-end' : 'justify-start' }} mb-1">
                                                    <span class="text-sm font-medium text-gray-900 dark:text-white">
                                                        {{ $message['user']['nickname'] }}
                                                    </span>
                                                    <span class="ml-2 text-xs text-gray-500 dark:text-gray-400">
                                                        {{ \Carbon\Carbon::parse($message['created_at'])->format('M d, Y h:i A') }}
                                                    </span>
                                                </div>
                                                <div class="{{ $message['user']['user_id'] == $student->id ? 'bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-200' : 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200' }} px-4 py-2 rounded-lg">
                                                    {{ $message['message'] }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <!-- Message input area -->
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                            <form id="message-form" class="flex flex-col space-y-2" enctype="multipart/form-data">
                                @csrf
                                <!-- File preview area (hidden by default) -->
                                <div id="file-preview" class="hidden p-2 bg-gray-100 dark:bg-gray-700 rounded-lg">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <i class="fas fa-file mr-2 text-indigo-500"></i>
                                            <span id="file-name" class="text-sm truncate max-w-xs"></span>
                                        </div>
                                        <button type="button" id="remove-file" class="text-red-500 hover:text-red-700">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="flex items-center">
                                    <button type="button" id="emoji-button" class="p-2 text-gray-500 hover:text-indigo-500 focus:outline-none">
                                        <i class="far fa-smile text-xl"></i>
                                    </button>
                                    <input type="text" id="message-input" class="flex-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Type your message...">
                                    <button type="button" id="attach-button" class="p-2 text-gray-500 hover:text-indigo-500 focus:outline-none">
                                        <i class="fas fa-paperclip text-xl"></i>
                                    </button>
                                    <button type="submit" id="send-button" class="ml-3 inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        <i class="fas fa-paper-plane mr-2"></i> Send
                                    </button>
                                </div>

                                <!-- Hidden file input -->
                                <input type="file" id="file-input" name="file" class="hidden" accept="image/*,video/*,audio/*,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt">

                                <!-- Emoji picker container (hidden by default) -->
                                <div id="emoji-picker" class="hidden absolute bottom-20 left-20 z-50 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg shadow-lg p-2">
                                    <div class="grid grid-cols-8 gap-1">
                                        <!-- Common emojis -->
                                        <button type="button" class="emoji-pick p-1 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">😀</button>
                                        <button type="button" class="emoji-pick p-1 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">😂</button>
                                        <button type="button" class="emoji-pick p-1 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">😊</button>
                                        <button type="button" class="emoji-pick p-1 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">😍</button>
                                        <button type="button" class="emoji-pick p-1 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">🤔</button>
                                        <button type="button" class="emoji-pick p-1 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">😎</button>
                                        <button type="button" class="emoji-pick p-1 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">👍</button>
                                        <button type="button" class="emoji-pick p-1 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">👎</button>
                                        <button type="button" class="emoji-pick p-1 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">❤️</button>
                                        <button type="button" class="emoji-pick p-1 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">🎉</button>
                                        <button type="button" class="emoji-pick p-1 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">🔥</button>
                                        <button type="button" class="emoji-pick p-1 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">🙏</button>
                                        <button type="button" class="emoji-pick p-1 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">😭</button>
                                        <button type="button" class="emoji-pick p-1 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">😱</button>
                                        <button type="button" class="emoji-pick p-1 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">👏</button>
                                        <button type="button" class="emoji-pick p-1 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">🤩</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Sendbird SDK -->
    @include('app.chat.sendbird-sdk')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Sendbird Chat
            const APP_ID = '{{ $appId }}';
            const USER_ID = '{{ $student->id }}';
            const CHANNEL_URL = '{{ $channel["channel_url"] }}';

            console.log('Initializing Sendbird with:', {
                appId: APP_ID,
                userId: USER_ID,
                channelUrl: CHANNEL_URL
            });

            // Initialize Sendbird directly
            console.log('Initializing Sendbird directly');

            // Initialize Sendbird Chat with the application ID
            // Make sure the app ID is in uppercase format
            const formattedAppId = APP_ID.toUpperCase();
            console.log('Creating SendBird instance with app ID:', formattedAppId);
            let sb;
            try {
                // Configure Sendbird to not use its own UI or URL handling
                const sendbirdOptions = {
                    appId: formattedAppId,
                    // Add debugging options
                    logLevel: 'debug',
                    debugMode: true,
                    // Disable URL navigation
                    useReactNativeWebView: true, // This prevents URL changes
                    newWinHandlerUrl: 'javascript:void(0);' // Prevent new window opening
                };

                // Log the options
                console.log('Sendbird initialization options:', sendbirdOptions);

                sb = new SendBird(sendbirdOptions);
                console.log('SendBird instance created:', sb);

                // Ensure no URL changes happen
                if (sb.setAppInfo) {
                    sb.setAppInfo({
                        disableUserProfile: true,
                        disableChannelProfile: true
                    });
                    console.log('Disabled Sendbird profiles to prevent URL changes');
                }
            } catch (error) {
                console.error('Error creating SendBird instance:', error);
                alert('Error initializing chat. Please refresh the page and try again.');
                return;
            }

            // Connect to Sendbird server with the user ID
            sb.connect(USER_ID, function(user, error) {
                if (error) {
                    console.error('Error connecting to Sendbird', error);
                    alert('Failed to connect to chat. Please refresh the page and try again.');
                    return;
                }

                console.log('Connected to Sendbird server', user);

                // Get the channel
                sb.GroupChannel.getChannel(CHANNEL_URL, function(channel, error) {
                    if (error) {
                        console.error('Error getting channel', error);
                        alert('Failed to load chat. Please refresh the page and try again.');
                        return;
                    }

                    console.log('Channel retrieved', channel);

                    // Load previous messages
                    const messageListParams = new sb.MessageListParams();
                    messageListParams.prevResultSize = 30; // Get up to 30 previous messages

                    channel.getMessagesByTimestamp(Date.now(), messageListParams, function(messages, error) {
                        if (error) {
                            console.error('Error loading previous messages', error);
                        } else {
                            console.log('Previous messages loaded:', messages);

                                // Clear existing messages if any were loaded from server-side
                                const chatMessages = document.getElementById('chat-messages');
                                const noMessagesDiv = chatMessages.querySelector('.text-center.py-8');

                                if (messages.length > 0 && noMessagesDiv) {
                                    chatMessages.innerHTML = '';
                                }

                            // Add previous messages to UI
                            messages.forEach(function(message) {
                                const isCurrentUser = message.sender.userId === USER_ID;
                                addMessageToUI(message, isCurrentUser);
                            });

                            // Scroll to bottom
                            chatMessages.scrollTop = chatMessages.scrollHeight;
                        }
                    });

                    // Set up message form submission
                    const messageForm = document.getElementById('message-form');
                    const messageInput = document.getElementById('message-input');
                    const sendButton = document.getElementById('send-button');
                    const chatMessages = document.getElementById('chat-messages');

                    // Function to send a message or file
                    function sendMessage(event) {
                        if (event) {
                            event.preventDefault();
                        }

                        console.log('sendMessage function called');

                        try {
                            // Check if channel is available
                            if (!channel) {
                                console.error('Channel is not available');
                                alert('Chat channel is not available. Please refresh the page and try again.');
                                return;
                            }

                            // Check if we're sending a file or a text message
                            const fileInput = document.getElementById('file-input');
                            const hasFile = fileInput.files && fileInput.files.length > 0;
                            const message = messageInput.value.trim();

                            // If no file and no message, don't send anything
                            if (!hasFile && !message) {
                                console.log('Empty message and no file, not sending');
                                return;
                            }

                            // Disable the form while sending
                            const submitButton = document.getElementById('send-button');
                            const originalButtonText = submitButton.innerHTML;
                            submitButton.disabled = true;
                            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Sending...';

                            if (hasFile) {
                                // Handle file upload
                                const file = fileInput.files[0];
                                console.log('Sending file:', file.name, file.type, file.size);

                                // Create a temporary message object for immediate display
                                const tempMessage = {
                                    message: `File: ${file.name}`,
                                    sender: {
                                        userId: USER_ID,
                                        nickname: '{{ $student->name }}'
                                    },
                                    createdAt: Date.now(),
                                    type: 'FILE',
                                    file_info: {
                                        name: file.name,
                                        type: file.type,
                                        size: file.size
                                    }
                                };

                                // Add message to UI immediately for better UX
                                addMessageToUI(tempMessage, true);

                                // Create FormData for file upload
                                const formData = new FormData();
                                formData.append('file', file);
                                formData.append('user_id', USER_ID);
                                formData.append('timestamp', Date.now());

                                // Send file to server
                                fetch('{{ route('student.chat.send', $channel["channel_url"]) }}', {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: formData
                                })
                                .then(response => {
                                    if (!response.ok) {
                                        throw new Error('Server response was not OK: ' + response.status);
                                    }
                                    return response.json();
                                })
                                .then(data => {
                                    console.log('File upload response:', data);

                                    // Clear file input and hide preview
                                    fileInput.value = '';
                                    document.getElementById('file-preview').classList.add('hidden');

                                    // Re-enable the form
                                    submitButton.disabled = false;
                                    submitButton.innerHTML = originalButtonText;

                                    // Scroll to bottom
                                    chatMessages.scrollTop = chatMessages.scrollHeight;
                                })
                                .catch(error => {
                                    console.error('Error uploading file:', error);

                                    // Re-enable the form
                                    submitButton.disabled = false;
                                    submitButton.innerHTML = originalButtonText;

                                    // Show error to user
                                    alert('File appears in your chat but may not be delivered to others. Error: ' + error.message);
                                });
                            } else {
                                // Handle text message
                                console.log('Sending message:', message);

                                // Create a temporary message object for immediate display
                                const tempMessage = {
                                    message: message,
                                    sender: {
                                        userId: USER_ID,
                                        nickname: '{{ $student->name }}'
                                    },
                                    createdAt: Date.now()
                                };

                                // Add message to UI immediately for better UX
                                addMessageToUI(tempMessage, true);

                                // Clear input
                                messageInput.value = '';

                                // Scroll to bottom
                                chatMessages.scrollTop = chatMessages.scrollHeight;

                                // First try sending directly to our server
                                sendMessageToServer(message)
                                    .then(function(serverResult) {
                                        console.log('Message sent to server first:', serverResult);

                                        // Now try the SDK with a simpler approach
                                        // Use the direct method without params object for better compatibility
                                        console.log('About to call sendUserMessage with message:', message);

                                        // Use the sendUserMessage method with callback for better compatibility with v3
                                        return new Promise((resolve, reject) => {
                                            try {
                                                channel.sendUserMessage(message, function(sentMessage, error) {
                                                    if (error) {
                                                        console.error('Error in sendUserMessage callback:', error);
                                                        reject(error);
                                                    } else {
                                                        console.log('Message sent successfully in callback:', sentMessage);
                                                        resolve(sentMessage);
                                                    }
                                                });
                                            } catch (err) {
                                                console.error('Exception in sendUserMessage:', err);
                                                reject(err);
                                            }
                                        });
                                    })
                                    .then(function(message) {
                                        console.log('Message sent successfully via SDK:', message);

                                        // Re-enable the form
                                        submitButton.disabled = false;
                                        submitButton.innerHTML = originalButtonText;
                                    })
                                    .catch(function(error) {
                                        console.error('Failed to send message:', error);

                                        // Re-enable the form
                                        submitButton.disabled = false;
                                        submitButton.innerHTML = originalButtonText;

                                        // Show error to user
                                        alert('Message appears in your chat but may not be delivered to others. Error: ' + error.message);
                                    });
                            }
                        } catch (error) {
                            console.error('Error in sendMessage function:', error);
                            alert('Error sending message: ' + error.message);

                            // Re-enable the button if it was disabled
                            const sendBtn = document.getElementById('send-button');
                            if (sendBtn.disabled) {
                                sendBtn.disabled = false;
                                sendBtn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i> Send';
                            }
                        }
                    }

                    // Add event listener for form submission
                    document.getElementById('message-form').addEventListener('submit', sendMessage);

                    // Add event listener for Enter key press
                    messageInput.addEventListener('keypress', function(e) {
                        if (e.key === 'Enter' && !e.shiftKey) {
                            e.preventDefault();
                            sendMessage();
                        }
                    });

                    // Set up emoji picker
                    const emojiButton = document.getElementById('emoji-button');
                    const emojiPicker = document.getElementById('emoji-picker');

                    emojiButton.addEventListener('click', function(e) {
                        // Toggle visibility
                        emojiPicker.classList.toggle('hidden');

                        // Position the emoji picker relative to the button
                        // This ensures it's not covered by the sidebar
                        if (!emojiPicker.classList.contains('hidden')) {
                            const buttonRect = emojiButton.getBoundingClientRect();

                            // Check if sidebar is expanded or collapsed
                            // We can detect this by checking for the 'open' property in Alpine.js data
                            // or by measuring the actual sidebar width
                            const sidebarElement = document.querySelector('aside');
                            let sidebarWidth = 64; // Default width when collapsed

                            if (sidebarElement) {
                                const sidebarRect = sidebarElement.getBoundingClientRect();
                                sidebarWidth = sidebarRect.width;
                            }

                            // Ensure the picker is positioned to the right of the sidebar
                            // Add a margin of 20px to avoid being too close to the sidebar
                            emojiPicker.style.left = Math.max(buttonRect.left, sidebarWidth + 20) + 'px';
                            emojiPicker.style.bottom = (window.innerHeight - buttonRect.top) + 'px';
                        }

                        // Prevent the event from bubbling up
                        e.stopPropagation();
                    });

                    // Close emoji picker when clicking outside
                    document.addEventListener('click', function(e) {
                        if (!emojiButton.contains(e.target) && !emojiPicker.contains(e.target)) {
                            emojiPicker.classList.add('hidden');
                        }
                    });

                    // Reposition emoji picker when window is resized
                    window.addEventListener('resize', function() {
                        if (!emojiPicker.classList.contains('hidden')) {
                            const buttonRect = emojiButton.getBoundingClientRect();
                            const sidebarElement = document.querySelector('aside');
                            let sidebarWidth = 64;

                            if (sidebarElement) {
                                const sidebarRect = sidebarElement.getBoundingClientRect();
                                sidebarWidth = sidebarRect.width;
                            }

                            emojiPicker.style.left = Math.max(buttonRect.left, sidebarWidth + 20) + 'px';
                            emojiPicker.style.bottom = (window.innerHeight - buttonRect.top) + 'px';
                        }
                    });

                    // Add emoji to message input when clicked
                    document.querySelectorAll('.emoji-pick').forEach(function(emoji) {
                        emoji.addEventListener('click', function() {
                            const emoji = this.textContent;
                            const cursorPos = messageInput.selectionStart;
                            const textBefore = messageInput.value.substring(0, cursorPos);
                            const textAfter = messageInput.value.substring(cursorPos);

                            messageInput.value = textBefore + emoji + textAfter;
                            messageInput.focus();
                            messageInput.selectionStart = cursorPos + emoji.length;
                            messageInput.selectionEnd = cursorPos + emoji.length;

                            // Hide emoji picker
                            emojiPicker.classList.add('hidden');
                        });
                    });

                    // Set up file attachment
                    const attachButton = document.getElementById('attach-button');
                    const fileInput = document.getElementById('file-input');
                    const filePreview = document.getElementById('file-preview');
                    const fileName = document.getElementById('file-name');
                    const removeFile = document.getElementById('remove-file');

                    attachButton.addEventListener('click', function() {
                        fileInput.click();
                    });

                    fileInput.addEventListener('change', function() {
                        if (this.files && this.files.length > 0) {
                            const file = this.files[0];
                            fileName.textContent = file.name;
                            filePreview.classList.remove('hidden');

                            // Update file icon based on file type
                            const fileIcon = filePreview.querySelector('i');
                            if (file.type.startsWith('image/')) {
                                fileIcon.className = 'fas fa-image mr-2 text-indigo-500';
                            } else if (file.type.startsWith('video/')) {
                                fileIcon.className = 'fas fa-video mr-2 text-indigo-500';
                            } else if (file.type.startsWith('audio/')) {
                                fileIcon.className = 'fas fa-music mr-2 text-indigo-500';
                            } else if (file.type.includes('pdf')) {
                                fileIcon.className = 'fas fa-file-pdf mr-2 text-indigo-500';
                            } else if (file.type.includes('word') || file.type.includes('document')) {
                                fileIcon.className = 'fas fa-file-word mr-2 text-indigo-500';
                            } else if (file.type.includes('excel') || file.type.includes('sheet')) {
                                fileIcon.className = 'fas fa-file-excel mr-2 text-indigo-500';
                            } else if (file.type.includes('powerpoint') || file.type.includes('presentation')) {
                                fileIcon.className = 'fas fa-file-powerpoint mr-2 text-indigo-500';
                            } else {
                                fileIcon.className = 'fas fa-file mr-2 text-indigo-500';
                            }
                        }
                    });

                    removeFile.addEventListener('click', function() {
                        fileInput.value = '';
                        filePreview.classList.add('hidden');
                    });

                    // Function to send message to server
                    function sendMessageToServer(messageText, callback) {
                        return fetch('{{ route('student.chat.send', $channel["channel_url"]) }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                message: messageText,
                                user_id: USER_ID,
                                timestamp: Date.now()
                            })
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Server response was not OK: ' + response.status);
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log('Server message response:', data);
                            if (callback && typeof callback === 'function') {
                                callback(true);
                            }
                            return data;
                        })
                        .catch(error => {
                            console.error('Error sending message to server:', error);
                            if (callback && typeof callback === 'function') {
                                callback(false);
                            }
                            throw error;
                        });
                    }

                    // Set up channel event handler to receive messages
                    const channelHandler = new sb.ChannelHandler();

                    // Handle received messages
                    channelHandler.onMessageReceived = function(channel, message) {
                        console.log('Message received from Sendbird:', message);

                        // Only add the message to UI if it's from another user
                        // We already added our own messages to the UI when sending
                        if (message.sender.userId !== USER_ID) {
                            addMessageToUI(message, false);

                            // Scroll to bottom
                            chatMessages.scrollTop = chatMessages.scrollHeight;

                            // Play a notification sound if available
                            const notificationSound = document.getElementById('notification-sound');
                            if (notificationSound) {
                                notificationSound.play().catch(e => console.log('Could not play notification sound', e));
                            }
                        }
                    };

                    // Handle channel updates
                    channelHandler.onChannelChanged = function(channel) {
                        console.log('Channel updated:', channel);
                    };

                    // Handle message updates
                    channelHandler.onMessageUpdated = function(channel, message) {
                        console.log('Message updated:', message);

                        // Find and update the message in the UI
                        const messageElements = document.querySelectorAll(`[data-message-id="${message.messageId}"]`);
                        if (messageElements.length > 0) {
                            // Update the message content
                            const messageContentEl = messageElements[0].querySelector('.message-content');
                            if (messageContentEl) {
                                messageContentEl.textContent = message.message;
                            }
                        }
                    };

                    // Add the channel handler with a unique ID
                    const handlerId = 'HANDLER_' + Date.now();
                    sb.addChannelHandler(handlerId, channelHandler);
                    console.log('Added channel handler with ID:', handlerId);

                    // Add a hidden audio element for notifications
                    const audioElement = document.createElement('audio');
                    audioElement.id = 'notification-sound';
                    audioElement.style.display = 'none';
                    audioElement.src = 'data:audio/mpeg;base64,SUQzBAAAAAABEVRYWFgAAAAtAAADY29tbWVudABCaWdTb3VuZEJhbmsuY29tIC8gTGFTb25vdGhlcXVlLm9yZwBURU5DAAAAHQAAA1N3aXRjaCBQbHVzIMKpIE5DSCBTb2Z0d2FyZQBUSVQyAAAABgAAAzIyMzUAVFNTRQAAAA8AAANMYXZmNTcuODMuMTAwAAAAAAAAAAAAAAD/80DEAAAAA0gAAAAATEFNRTMuMTAwVVVVVVVVVVVVVUxBTUUzLjEwMFVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVf/zQsRbAAADSAAAAABVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVf/zQMSkAAADSAAAAABVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVV';
                    document.body.appendChild(audioElement);

                    // Function to add a message to the UI
                    function addMessageToUI(message, isCurrentUser) {
                        // Check if we already have a "no messages" placeholder
                        const chatMessages = document.getElementById('chat-messages');
                        const noMessagesDiv = chatMessages.querySelector('.text-center.py-8');

                        if (noMessagesDiv) {
                            chatMessages.innerHTML = '';
                        }

                        const messageDiv = document.createElement('div');
                        messageDiv.className = `mb-4 ${isCurrentUser ? 'ml-auto' : 'mr-auto'} max-w-[80%]`;

                        // Handle different message formats (SDK vs server)
                        let sender, nickname, profileUrl, messageText, timestamp, messageType, fileInfo;

                        if (message.sender) {
                            // SDK format
                            sender = message.sender;
                            nickname = sender.nickname || 'User';
                            profileUrl = sender.profileUrl;
                            messageText = message.message;
                            timestamp = message.createdAt ? new Date(message.createdAt) : new Date();
                            messageType = message.type || 'MESG';
                            fileInfo = message.file || message.file_info;
                        } else if (message.user) {
                            // Server format
                            sender = message.user;
                            nickname = sender.nickname || 'User';
                            profileUrl = sender.profile_url;
                            messageText = message.message;
                            timestamp = message.created_at ? new Date(message.created_at) : new Date();
                            messageType = message.type || 'MESG';
                            fileInfo = message.file || message.file_info;
                        } else {
                            // Fallback
                            sender = {};
                            nickname = 'User';
                            profileUrl = null;
                            messageText = message.message || 'No message content';
                            timestamp = new Date();
                            messageType = 'MESG';
                            fileInfo = null;
                        }

                        const initial = nickname.charAt(0).toUpperCase();
                        const isFileMessage = messageType === 'FILE' || messageText.startsWith('File:') || fileInfo;

                        console.log('Adding message to UI:', {
                            message: messageText,
                            sender: sender,
                            isCurrentUser: isCurrentUser,
                            timestamp: timestamp,
                            type: messageType,
                            fileInfo: fileInfo
                        });

                        // Add message ID if available for tracking updates
                        const messageId = message.messageId || message.message_id || ('temp_' + Date.now());

                        // Prepare message content based on type
                        let messageContent = '';

                        if (isFileMessage) {
                            // Extract file information
                            let fileName, fileType, fileUrl;

                            if (fileInfo) {
                                fileName = fileInfo.name || 'File';
                                fileType = fileInfo.type || 'application/octet-stream';
                                fileUrl = fileInfo.url || fileInfo.file_url || '#';
                            } else {
                                // Try to extract from message text
                                fileName = messageText.replace('File:', '').trim();
                                fileType = 'application/octet-stream';
                                fileUrl = '#';
                            }

                            // Determine file icon based on type
                            let fileIcon = 'fa-file';
                            if (fileType.startsWith('image/')) {
                                fileIcon = 'fa-image';
                            } else if (fileType.startsWith('video/')) {
                                fileIcon = 'fa-video';
                            } else if (fileType.startsWith('audio/')) {
                                fileIcon = 'fa-music';
                            } else if (fileType.includes('pdf')) {
                                fileIcon = 'fa-file-pdf';
                            } else if (fileType.includes('word') || fileType.includes('document')) {
                                fileIcon = 'fa-file-word';
                            } else if (fileType.includes('excel') || fileType.includes('sheet')) {
                                fileIcon = 'fa-file-excel';
                            } else if (fileType.includes('powerpoint') || fileType.includes('presentation')) {
                                fileIcon = 'fa-file-powerpoint';
                            }

                            // Create file message content
                            if (fileType.startsWith('image/') && fileUrl && fileUrl !== '#') {
                                // Image with preview
                                messageContent = `
                                    <div class="file-message">
                                        <div class="mb-2">
                                            <img src="${fileUrl}" alt="${fileName}" class="max-w-full rounded-lg max-h-60 object-contain">
                                        </div>
                                        <div class="flex items-center text-sm">
                                            <i class="fas ${fileIcon} mr-2"></i>
                                            <span class="truncate">${fileName}</span>
                                            <a href="${fileUrl}" download="${fileName}" class="ml-2 text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        </div>
                                    </div>
                                `;
                            } else {
                                // Regular file
                                messageContent = `
                                    <div class="file-message flex items-center">
                                        <i class="fas ${fileIcon} mr-2 text-2xl"></i>
                                        <div class="flex flex-col">
                                            <span class="truncate font-medium">${fileName}</span>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">File</span>
                                        </div>
                                        ${fileUrl && fileUrl !== '#' ? `
                                            <a href="${fileUrl}" download="${fileName}" class="ml-3 text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        ` : ''}
                                    </div>
                                `;
                            }
                        } else {
                            // Regular text message - escape HTML and convert URLs to links
                            const escapedText = messageText
                                .replace(/&/g, '&amp;')
                                .replace(/</g, '&lt;')
                                .replace(/>/g, '&gt;')
                                .replace(/"/g, '&quot;')
                                .replace(/'/g, '&#039;');

                            // Convert URLs to clickable links
                            const linkedText = escapedText.replace(
                                /(https?:\/\/[^\s]+)/g,
                                '<a href="$1" target="_blank" class="text-indigo-600 hover:underline dark:text-indigo-400">$1</a>'
                            );

                            messageContent = linkedText;
                        }

                        messageDiv.setAttribute('data-message-id', messageId);
                        messageDiv.innerHTML = `
                            <div class="flex ${isCurrentUser ? 'flex-row-reverse' : 'flex-row'} items-start">
                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400 ${isCurrentUser ? 'ml-3' : 'mr-3'}">
                                    ${profileUrl ? `<img src="${profileUrl}" alt="${nickname}" class="h-10 w-10 rounded-full">` : initial}
                                </div>
                                <div>
                                    <div class="flex items-center ${isCurrentUser ? 'justify-end' : 'justify-start'} mb-1">
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">
                                            ${nickname}
                                        </span>
                                        <span class="ml-2 text-xs text-gray-500 dark:text-gray-400">
                                            ${timestamp.toLocaleString()}
                                        </span>
                                    </div>
                                    <div class="message-content ${isCurrentUser ? 'bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-200' : 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200'} px-4 py-2 rounded-lg">
                                        ${messageContent}
                                    </div>
                                </div>
                            </div>
                        `;

                        chatMessages.appendChild(messageDiv);
                    }

                    // Scroll to bottom initially
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                });
            });
        });
    </script>
</x-tenant-app-layout>
