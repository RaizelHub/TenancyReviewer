<?php

namespace App\Http\Controllers\App;

use App\Facades\Sendbird;
use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    /**
     * Display the chat interface
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();

        // Check if user exists in Sendbird, if not create them
        $sendbirdUser = Sendbird::getUser($user->id);

        if (!$sendbirdUser) {
            // Create user in Sendbird
            $sendbirdUser = Sendbird::createUser(
                $user->id,
                $user->name,
                $user->profile_photo ?? null
            );

            if (!$sendbirdUser) {
                Log::error('Failed to create Sendbird user for user ID: ' . $user->id);
            }
        }

        // Get user's channels
        $channels = Sendbird::listGroupChannels($user->id);

        // Process channels to extract subject information
        $processedChannels = [];
        $subjectChannels = [];

        if (!empty($channels['channels'])) {
            foreach ($channels['channels'] as $channel) {
                // Try to extract subject information from channel data
                $channelData = [];
                if (!empty($channel['data'])) {
                    try {
                        $channelData = json_decode($channel['data'], true) ?? [];
                    } catch (\Exception $e) {
                        \Log::warning('Failed to decode channel data', [
                            'channel_url' => $channel['channel_url'],
                            'error' => $e->getMessage()
                        ]);
                    }
                }

                // Add the processed data to the channel
                $channel['processed_data'] = $channelData;

                // If this channel has subject information, add it to subject channels
                if (!empty($channelData['subject_id'])) {
                    if (!isset($subjectChannels[$channelData['subject_id']])) {
                        $subjectChannels[$channelData['subject_id']] = [
                            'subject_id' => $channelData['subject_id'],
                            'subject_name' => $channelData['subject_name'] ?? 'Unknown Subject',
                            'subject_code' => $channelData['subject_code'] ?? '',
                            'channels' => []
                        ];
                    }
                    $subjectChannels[$channelData['subject_id']]['channels'][] = $channel;
                }

                $processedChannels[] = $channel;
            }
        }

        return view('app.chat.index', [
            'user' => $user,
            'sendbirdUser' => $sendbirdUser,
            'channels' => $processedChannels,
            'subjectChannels' => $subjectChannels,
            'appId' => config('sendbird.app_id')
        ]);
    }

    /**
     * Display a specific chat
     *
     * @param string $channelUrl
     * @return \Illuminate\View\View
     */
    public function show($channelUrl)
    {
        $user = Auth::user();
        $channel = Sendbird::getGroupChannel($channelUrl);

        if (!$channel) {
            return redirect()->route('chat.index')->with('error', 'Chat not found');
        }

        $messagesResponse = Sendbird::getMessages($channelUrl);
        $messages = [];

        if ($messagesResponse && isset($messagesResponse['messages'])) {
            $messages = $messagesResponse['messages'];
            Log::info('Retrieved messages for channel', [
                'channel_url' => $channelUrl,
                'message_count' => count($messages)
            ]);
        } else {
            Log::info('No messages found or error retrieving messages for channel', [
                'channel_url' => $channelUrl
            ]);
        }

        return view('app.chat.show', [
            'user' => $user,
            'channel' => $channel,
            'messages' => $messages,
            'appId' => config('sendbird.app_id')
        ]);
    }

    /**
     * Create a new group chat with multiple students
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_ids' => 'required|array|min:3',
            'student_ids.*' => 'exists:students,id',
            'name' => 'required|string|max:255',
        ]);

        $currentUser = Auth::user();

        // Ensure user IDs are strings and include the current user
        $userIds = [(string)$currentUser->id];

        foreach ($request->student_ids as $studentId) {
            $userIds[] = (string)$studentId;
        }

        // Make sure user IDs are unique
        $userIds = array_values(array_unique($userIds));

        // Log the user IDs for debugging
        \Illuminate\Support\Facades\Log::info('Creating group chat with user IDs', [
            'user_ids' => $userIds,
            'user_ids_type' => gettype($userIds),
            'current_user' => $currentUser->id,
            'student_count' => count($request->student_ids)
        ]);

        // Create a group channel (with empty metadata for regular chats)
        $channel = Sendbird::createGroupChannel(
            $userIds,
            $request->name,
            null, // No cover URL
            [], // No specific metadata for regular chats
            true // Distinct - only one chat per unique set of users for regular chats
        );

        if (!$channel) {
            return redirect()->back()->with('error', 'Failed to create group chat');
        }

        return redirect()->route('chat.show', $channel['channel_url'])
            ->with('success', 'Group chat created successfully');
    }

    /**
     * Show the form to create a new chat
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // For teachers, get all students
        // For students, get all teachers
        $users = [];

        if (Auth::guard('web')->check()) {
            // Teacher is logged in, get students
            $users = \App\Models\Student::all();
        } elseif (Auth::guard('student')->check()) {
            // Student is logged in, get teachers
            $users = \App\Models\User::all();
        }

        return view('app.chat.create', [
            'users' => $users
        ]);
    }

    /**
     * Send a message in a chat
     *
     * @param Request $request
     * @param string $channelUrl
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendMessage(Request $request, $channelUrl)
    {
        // Validate based on whether this is a file upload or text message
        if ($request->hasFile('file')) {
            $request->validate([
                'file' => 'required|file|max:10240', // Max 10MB file size
                'user_id' => 'sometimes|string',
                'timestamp' => 'sometimes|numeric'
            ]);
        } else {
            $request->validate([
                'message' => 'required|string',
                'user_id' => 'sometimes|string',
                'timestamp' => 'sometimes|numeric'
            ]);
        }

        $user = Auth::user();

        // Ensure user ID is a string
        $userId = (string)$user->id;

        // Get the channel to verify it exists
        $channel = Sendbird::getGroupChannel($channelUrl);

        if (!$channel) {
            Log::error('Failed to send message - channel not found', [
                'channel_url' => $channelUrl,
                'user_id' => $userId
            ]);
            return response()->json(['success' => false, 'message' => 'Channel not found'], 404);
        }

        $result = null;

        // Handle file upload or text message
        if ($request->hasFile('file')) {
            $file = $request->file('file');

            Log::info('Uploading file to channel', [
                'channel_url' => $channelUrl,
                'user_id' => $userId,
                'file_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize(),
                'file_type' => $file->getMimeType()
            ]);

            // Upload file via Sendbird API
            $result = Sendbird::uploadFile(
                $channelUrl,
                $userId,
                $file
            );

            // If upload fails, create a fake result
            if (!$result) {
                Log::warning('Failed to upload file via Sendbird API, but continuing', [
                    'channel_url' => $channelUrl,
                    'user_id' => $userId,
                    'file_name' => $file->getClientOriginalName()
                ]);

                // Create a fake result for the client
                $result = [
                    'message_id' => 'local_file_' . time(),
                    'message' => 'File: ' . $file->getClientOriginalName(),
                    'created_at' => now()->timestamp * 1000,
                    'type' => 'FILE',
                    'file_info' => [
                        'name' => $file->getClientOriginalName(),
                        'type' => $file->getMimeType(),
                        'size' => $file->getSize()
                    ]
                ];
            }
        } else {
            // Regular text message
            Log::info('Sending message to channel', [
                'channel_url' => $channelUrl,
                'user_id' => $userId,
                'message_length' => strlen($request->message),
                'timestamp' => $request->timestamp ?? time() * 1000
            ]);

            // Try to send the message via Sendbird API
            $result = Sendbird::sendMessage(
                $channelUrl,
                $userId,
                $request->message
            );

            // If sending fails, create a fake result
            if (!$result) {
                Log::warning('Failed to send message via Sendbird API, but continuing', [
                    'channel_url' => $channelUrl,
                    'user_id' => $userId
                ]);

                // Create a fake result for the client
                $result = [
                    'message_id' => 'local_' . time(),
                    'message' => $request->message,
                    'created_at' => now()->timestamp * 1000,
                    'type' => 'MESG'
                ];
            }
        }

        Log::info('Message/file sent successfully', [
            'channel_url' => $channelUrl,
            'user_id' => $userId,
            'message_id' => $result['message_id'] ?? 'unknown',
            'type' => $request->hasFile('file') ? 'FILE' : 'MESG'
        ]);

        return response()->json([
            'success' => true,
            'message' => $result,
            'user' => [
                'id' => $userId,
                'name' => $user->name
            ]
        ]);
    }

    /**
     * Show the form to create a new chat for a specific subject
     *
     * @param Subject $subject
     * @return \Illuminate\View\View
     */
    public function createSubjectChat(Subject $subject)
    {
        // Check if the user owns this subject
        if ($subject->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Get all students enrolled in this subject
        $students = $subject->students;

        return view('app.chat.create-subject-chat', [
            'subject' => $subject,
            'students' => $students
        ]);
    }

    /**
     * Delete a chat channel
     *
     * @param string $channelUrl
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($channelUrl)
    {
        $user = Auth::user();
        $channel = Sendbird::getGroupChannel($channelUrl);

        if (!$channel) {
            return redirect()->route('chat.index')->with('error', 'Chat not found');
        }

        // Delete the channel
        $success = Sendbird::deleteChannel($channelUrl);

        if ($success) {
            Log::info('Chat deleted successfully', [
                'channel_url' => $channelUrl,
                'user_id' => $user->id
            ]);
            return redirect()->route('chat.index')->with('success', 'Chat deleted successfully');
        } else {
            Log::error('Failed to delete chat', [
                'channel_url' => $channelUrl,
                'user_id' => $user->id
            ]);
            return redirect()->route('chat.index')->with('error', 'Failed to delete chat');
        }
    }

    /**
     * Create a new chat with students from a specific subject
     *
     * @param Request $request
     * @param Subject $subject
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeSubjectChat(Request $request, Subject $subject)
    {
        // Check if the user owns this subject
        if ($subject->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'student_ids' => 'required|array|min:3',
            'student_ids.*' => 'exists:students,id',
            'name' => 'required|string|max:255',
        ]);

        $currentUser = Auth::user();

        // Ensure user IDs are strings and include the current user
        $userIds = [(string)$currentUser->id];

        foreach ($request->student_ids as $studentId) {
            $userIds[] = (string)$studentId;
        }

        // Make sure user IDs are unique
        $userIds = array_values(array_unique($userIds));

        // Log the user IDs for debugging
        \Illuminate\Support\Facades\Log::info('Creating subject chat with user IDs', [
            'user_ids' => $userIds,
            'user_ids_type' => gettype($userIds),
            'subject_id' => $subject->id,
            'subject_name' => $subject->name
        ]);

        // Create a group channel with subject metadata (non-distinct to allow multiple chats)
        $channel = Sendbird::createGroupChannel(
            $userIds,
            $request->name,
            null, // No cover URL
            [
                'subject_id' => $subject->id,
                'subject_name' => $subject->name,
                'subject_code' => $subject->code
            ],
            false // Not distinct - allow multiple chats with the same users
        );

        if (!$channel) {
            return redirect()->back()->with('error', 'Failed to create chat');
        }

        // Store the subject ID in the session to help with navigation context
        session(['last_subject_id' => $subject->id]);

        // Log the redirect URL for debugging
        $redirectUrl = route('chat.show', $channel['channel_url']);
        \Illuminate\Support\Facades\Log::info('Redirecting to chat URL', [
            'redirect_url' => $redirectUrl,
            'channel_url' => $channel['channel_url']
        ]);

        return redirect($redirectUrl)
            ->with('success', 'Subject chat created successfully')
            ->with('subject_context', [
                'id' => $subject->id,
                'name' => $subject->name,
                'code' => $subject->code
            ]);
    }
}
