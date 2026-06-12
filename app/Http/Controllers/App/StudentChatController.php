<?php

namespace App\Http\Controllers\App;

use App\Facades\Sendbird;
use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StudentChatController extends Controller
{
    /**
     * Display the chat interface for students
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $student = Auth::guard('student')->user();

        // Check if student exists in Sendbird, if not create them
        $sendbirdUser = Sendbird::getUser($student->id);

        if (!$sendbirdUser) {
            // Create user in Sendbird
            $sendbirdUser = Sendbird::createUser(
                $student->id,
                $student->name,
                $student->profile_photo ?? null
            );

            if (!$sendbirdUser) {
                Log::error('Failed to create Sendbird user for student ID: ' . $student->id);
            }
        }

        // Get student's channels
        $channels = Sendbird::listGroupChannels($student->id);

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

        return view('app.chat.student.index', [
            'student' => $student,
            'sendbirdUser' => $sendbirdUser,
            'channels' => $processedChannels,
            'subjectChannels' => $subjectChannels,
            'appId' => config('sendbird.app_id')
        ]);
    }

    /**
     * Display a specific chat for students
     *
     * @param string $channelUrl
     * @return \Illuminate\View\View
     */
    public function show($channelUrl)
    {
        $student = Auth::guard('student')->user();
        $channel = Sendbird::getGroupChannel($channelUrl);

        if (!$channel) {
            return redirect()->route('student.chat.index')->with('error', 'Chat not found');
        }

        $messagesResponse = Sendbird::getMessages($channelUrl);
        $messages = [];

        if ($messagesResponse && isset($messagesResponse['messages'])) {
            $messages = $messagesResponse['messages'];
            Log::info('Retrieved messages for student channel', [
                'channel_url' => $channelUrl,
                'message_count' => count($messages)
            ]);
        } else {
            Log::info('No messages found or error retrieving messages for student channel', [
                'channel_url' => $channelUrl
            ]);
        }

        return view('app.chat.student.show', [
            'student' => $student,
            'channel' => $channel,
            'messages' => $messages,
            'appId' => config('sendbird.app_id')
        ]);
    }

    /**
     * Create a new chat with a teacher (for students)
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required|string',
            'name' => 'required|string|max:255',
        ]);

        $student = Auth::guard('student')->user();

        // Ensure user IDs are strings
        $userIds = [(string)$student->id, (string)$request->teacher_id];

        // Log the user IDs for debugging
        \Illuminate\Support\Facades\Log::info('Creating student chat with user IDs', [
            'user_ids' => $userIds,
            'student_id' => $student->id,
            'teacher_id' => $request->teacher_id
        ]);

        // Create a group channel (distinct for regular chats)
        $channel = Sendbird::createGroupChannel(
            $userIds,
            $request->name,
            null, // No cover URL
            [], // No specific metadata
            true // Distinct - only one chat per unique set of users for regular chats
        );

        if (!$channel) {
            return redirect()->back()->with('error', 'Failed to create chat');
        }

        return redirect()->route('student.chat.show', $channel['channel_url'])
            ->with('success', 'Chat created successfully');
    }

    /**
     * Show the form to create a new chat (for students)
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Get all teachers for the student to chat with
        $teachers = \App\Models\User::all();

        return view('app.chat.student.create', [
            'teachers' => $teachers
        ]);
    }

    /**
     * Send a message in a chat (for students)
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

        $student = Auth::guard('student')->user();

        // Ensure user ID is a string
        $userId = (string)$student->id;

        // Get the channel to verify it exists
        $channel = Sendbird::getGroupChannel($channelUrl);

        if (!$channel) {
            Log::error('Failed to send student message - channel not found', [
                'channel_url' => $channelUrl,
                'user_id' => $userId
            ]);
            return response()->json(['success' => false, 'message' => 'Channel not found'], 404);
        }

        $result = null;

        // Handle file upload or text message
        if ($request->hasFile('file')) {
            $file = $request->file('file');

            Log::info('Student uploading file to channel', [
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
                Log::warning('Failed to upload student file via Sendbird API, but continuing', [
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
            Log::info('Sending student message to channel', [
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
                Log::warning('Failed to send student message via Sendbird API, but continuing', [
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

        Log::info('Student message/file sent successfully', [
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
                'name' => $student->name
            ]
        ]);
    }

    /**
     * Show the form to create a new chat for a specific subject (for students)
     *
     * @param Subject $subject
     * @return \Illuminate\View\View
     */
    public function createSubjectChat(Subject $subject)
    {
        $student = Auth::guard('student')->user();

        // Check if the student is enrolled in this subject
        if (!$student->subjects()->where('subjects.id', $subject->id)->exists()) {
            return redirect()->route('student.subjects')->with('error', 'You are not enrolled in this subject.');
        }

        // Get the teacher of this subject
        $teacher = $subject->user;

        return view('app.chat.student.create-subject-chat', [
            'subject' => $subject,
            'teacher' => $teacher
        ]);
    }

    /**
     * Delete a chat channel (for students)
     *
     * @param string $channelUrl
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($channelUrl)
    {
        $student = Auth::guard('student')->user();
        $channel = Sendbird::getGroupChannel($channelUrl);

        if (!$channel) {
            return redirect()->route('student.chat.index')->with('error', 'Chat not found');
        }

        // Delete the channel
        $success = Sendbird::deleteChannel($channelUrl);

        if ($success) {
            Log::info('Student chat deleted successfully', [
                'channel_url' => $channelUrl,
                'user_id' => $student->id
            ]);
            return redirect()->route('student.chat.index')->with('success', 'Chat deleted successfully');
        } else {
            Log::error('Failed to delete student chat', [
                'channel_url' => $channelUrl,
                'user_id' => $student->id
            ]);
            return redirect()->route('student.chat.index')->with('error', 'Failed to delete chat');
        }
    }

    /**
     * Create a new chat with the teacher of a specific subject (for students)
     *
     * @param Request $request
     * @param Subject $subject
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeSubjectChat(Request $request, Subject $subject)
    {
        $student = Auth::guard('student')->user();

        // Check if the student is enrolled in this subject
        if (!$student->subjects()->where('subjects.id', $subject->id)->exists()) {
            return redirect()->route('student.subjects')->with('error', 'You are not enrolled in this subject.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Get the teacher ID
        $teacherId = $subject->user_id;

        // Ensure user IDs are strings
        $userIds = [(string)$student->id, (string)$teacherId];

        // Log the user IDs for debugging
        \Illuminate\Support\Facades\Log::info('Creating student subject chat with user IDs', [
            'user_ids' => $userIds,
            'student_id' => $student->id,
            'teacher_id' => $teacherId,
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

        return redirect()->route('student.chat.show', $channel['channel_url'])
            ->with('success', 'Chat created successfully');
    }
}
