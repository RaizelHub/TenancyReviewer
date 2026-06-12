<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class SendbirdService
{
    protected $client;
    protected $appId;

    public function __construct(Client $client, string $appId)
    {
        $this->client = $client;
        $this->appId = $appId;
    }

    /**
     * Create a Sendbird user
     *
     * @param string $userId
     * @param string $nickname
     * @param string|null $profileUrl
     * @return array|null
     */
    public function createUser(string $userId, string $nickname, ?string $profileUrl = null): ?array
    {
        try {
            // Use the client from the constructor which has the correct base URL
            $fullUrl = config('sendbird.api_url') . '/users';

            Log::info('Making Sendbird request to: ' . $fullUrl, [
                'user_id' => $userId,
                'nickname' => $nickname
            ]);

            $response = $this->client->post('users', [
                'json' => [
                    'user_id' => $userId,
                    'nickname' => $nickname,
                    'profile_url' => $profileUrl,
                ],
            ]);

            $result = json_decode($response->getBody()->getContents(), true);
            Log::info('Sendbird user created successfully', ['user_id' => $userId]);

            return $result;
        } catch (GuzzleException $e) {
            Log::error('Sendbird create user error: ' . $e->getMessage(), [
                'user_id' => $userId,
                'status_code' => $e->getCode(),
                'response' => $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : null
            ]);
            return null;
        }
    }

    /**
     * Get a Sendbird user
     *
     * @param string $userId
     * @return array|null
     */
    public function getUser(string $userId): ?array
    {
        try {
            // Use the client from the constructor which has the correct base URL
            $endpoint = "users/{$userId}";
            $fullUrl = config('sendbird.api_url') . '/' . $endpoint;

            Log::info('Making Sendbird request to: ' . $fullUrl, [
                'user_id' => $userId
            ]);

            $response = $this->client->get($endpoint);
            $result = json_decode($response->getBody()->getContents(), true);

            Log::info('Sendbird user retrieved successfully', [
                'user_id' => $userId,
                'found' => !empty($result)
            ]);

            return $result;
        } catch (GuzzleException $e) {
            // If user not found (404), return null without error
            if ($e->getCode() == 404) {
                Log::info('Sendbird user not found', ['user_id' => $userId]);
                return null;
            }

            Log::error('Sendbird get user error: ' . $e->getMessage(), [
                'user_id' => $userId,
                'status_code' => $e->getCode(),
                'response' => $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : null
            ]);
            return null;
        }
    }

    /**
     * Create a group channel
     *
     * @param array $userIds
     * @param string $name
     * @param string|null $coverUrl
     * @param array $metadata Additional metadata for the channel (e.g., subject_id)
     * @param bool $isDistinct Whether the channel should be distinct (default: false)
     * @return array|null
     */
    public function createGroupChannel(array $userIds, string $name, ?string $coverUrl = null, array $metadata = [], bool $isDistinct = false): ?array
    {
        try {
            // Use the client from the constructor which has the correct base URL
            // No need to include 'v3/' as it's already in the base URL
            $endpoint = 'group_channels';
            $fullUrl = config('sendbird.api_url') . '/' . $endpoint;

            // Ensure all user IDs are strings and unique
            $formattedUserIds = array_values(array_unique(array_map(function($id) {
                return (string) $id;
            }, $userIds)));

            // Make sure we have at least 2 unique user IDs for a channel
            if (count($formattedUserIds) < 2) {
                Log::error('Cannot create a channel with less than 2 unique users');
                return null;
            }

            Log::info('Making Sendbird request to: ' . $fullUrl, [
                'formatted_user_ids' => $formattedUserIds,
                'name' => $name,
                'is_distinct' => $isDistinct
            ]);

            // Prepare channel data with metadata
            $channelData = [
                'created_at' => time(),
                'type' => 'student_teacher_chat'
            ];

            // Add any additional metadata
            if (!empty($metadata)) {
                $channelData = array_merge($channelData, $metadata);
            }

            // Log the channel data
            Log::info('Channel data with metadata', [
                'channel_data' => $channelData
            ]);

            // Create the channel with simplified parameters to ensure compatibility
            $response = $this->client->post($endpoint, [
                'json' => [
                    'user_ids' => $formattedUserIds,
                    'name' => $name,
                    'cover_url' => $coverUrl,
                    'is_distinct' => $isDistinct,
                    'custom_type' => 'student_teacher_chat',
                    'data' => json_encode($channelData),
                ],
            ]);

            $result = json_decode($response->getBody()->getContents(), true);

            // Log successful channel creation with detailed information
            Log::info('Sendbird group channel created successfully', [
                'channel_url' => $result['channel_url'] ?? 'unknown',
                'name' => $name,
                'user_count' => count($formattedUserIds),
                'users' => $formattedUserIds
            ]);

            return $result;
        } catch (GuzzleException $e) {
            // Get the response body if available
            $responseBody = $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : null;

            // Log detailed error information
            Log::error('Sendbird create group channel error: ' . $e->getMessage(), [
                'user_ids' => $userIds,
                'formatted_user_ids' => $formattedUserIds ?? [],
                'formatted_user_ids_type' => gettype($formattedUserIds ?? []),
                'name' => $name,
                'status_code' => $e->getCode(),
                'response' => $responseBody
            ]);

            // Try to parse the error response for more details
            if ($responseBody) {
                try {
                    $errorData = json_decode($responseBody, true);
                    Log::error('Sendbird error details', [
                        'error_code' => $errorData['code'] ?? 'unknown',
                        'error_message' => $errorData['message'] ?? 'unknown'
                    ]);
                } catch (\Exception $jsonError) {
                    Log::error('Failed to parse Sendbird error response', [
                        'error' => $jsonError->getMessage()
                    ]);
                }
            }

            return null;
        }
    }

    /**
     * Get a group channel
     *
     * @param string $channelUrl
     * @return array|null
     */
    public function getGroupChannel(string $channelUrl): ?array
    {
        try {
            // Use the client from the constructor which has the correct base URL
            $endpoint = "group_channels/{$channelUrl}";
            $fullUrl = config('sendbird.api_url') . '/' . $endpoint;

            Log::info('Making Sendbird request to: ' . $fullUrl, [
                'channel_url' => $channelUrl
            ]);

            $response = $this->client->get($endpoint);
            $result = json_decode($response->getBody()->getContents(), true);

            Log::info('Sendbird group channel retrieved successfully', [
                'channel_url' => $channelUrl,
                'name' => $result['name'] ?? 'unknown'
            ]);

            return $result;
        } catch (GuzzleException $e) {
            Log::error('Sendbird get group channel error: ' . $e->getMessage(), [
                'channel_url' => $channelUrl,
                'status_code' => $e->getCode(),
                'response' => $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : null
            ]);
            return null;
        }
    }

    /**
     * List group channels for a user
     *
     * @param string $userId
     * @param int $limit
     * @return array|null
     */
    public function listGroupChannels(string $userId, int $limit = 20): ?array
    {
        try {
            // Use the client from the constructor which has the correct base URL
            $endpoint = "users/{$userId}/my_group_channels";
            $fullUrl = config('sendbird.api_url') . '/' . $endpoint;

            Log::info('Making Sendbird request to: ' . $fullUrl, [
                'user_id' => $userId,
                'limit' => $limit
            ]);

            // Add additional parameters to ensure we get all channels including distinct ones
            // For Sendbird API, we need to simplify our query to avoid parameter errors
            $response = $this->client->get($endpoint, [
                'query' => [
                    'limit' => $limit,
                    'distinct_mode' => 'all', // Include all channels, including distinct ones
                    'order' => 'latest_last_message', // Order by latest message
                ],
            ]);

            $result = json_decode($response->getBody()->getContents(), true);

            Log::info('Sendbird group channels listed successfully', [
                'user_id' => $userId,
                'channel_count' => count($result['channels'] ?? [])
            ]);

            return $result;
        } catch (GuzzleException $e) {
            Log::error('Sendbird list group channels error: ' . $e->getMessage(), [
                'user_id' => $userId,
                'status_code' => $e->getCode(),
                'response' => $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : null
            ]);
            return null;
        }
    }

    /**
     * Send a message to a group channel
     *
     * @param string $channelUrl
     * @param string $userId
     * @param string $message
     * @return array|null
     */
    public function sendMessage(string $channelUrl, string $userId, string $message): ?array
    {
        try {
            // Use the client from the constructor which has the correct base URL
            $endpoint = "group_channels/{$channelUrl}/messages";
            $fullUrl = config('sendbird.api_url') . '/' . $endpoint;

            // Try to get user details, but don't fail if we can't
            try {
                $user = $this->getUser($userId);
                $userName = $user['nickname'] ?? 'User';
            } catch (\Exception $e) {
                Log::warning('Could not get user details for message, using default name', [
                    'user_id' => $userId,
                    'error' => $e->getMessage()
                ]);
                $userName = 'User';
            }

            Log::info('Making Sendbird message request to: ' . $fullUrl, [
                'channel_url' => $channelUrl,
                'user_id' => $userId,
                'user_name' => $userName,
                'message_length' => strlen($message)
            ]);

            // Prepare a simpler request payload
            $payload = [
                'message_type' => 'MESG',
                'user_id' => $userId,
                'message' => $message
            ];

            // Make the API request
            $response = $this->client->post($endpoint, [
                'json' => $payload,
            ]);

            $result = json_decode($response->getBody()->getContents(), true);

            // Log success with detailed information
            Log::info('Sendbird message sent successfully', [
                'channel_url' => $channelUrl,
                'user_id' => $userId,
                'message_id' => $result['message_id'] ?? 'unknown',
                'response_status' => $response->getStatusCode()
            ]);

            return $result;
        } catch (GuzzleException $e) {
            // Log detailed error information
            Log::error('Sendbird send message error: ' . $e->getMessage(), [
                'channel_url' => $channelUrl,
                'user_id' => $userId,
                'status_code' => $e->getCode(),
                'response' => $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : null,
                'request_url' => $fullUrl ?? 'unknown URL'
            ]);

            // Try a fallback approach if the first one fails
            try {
                if ($e->getCode() == 400 || $e->getCode() == 404) {
                    Log::info('Attempting fallback message send approach');

                    // Try a simpler payload
                    $simplePayload = [
                        'message_type' => 'MESG',
                        'user_id' => $userId,
                        'message' => $message
                    ];

                    $response = $this->client->post($endpoint, [
                        'json' => $simplePayload,
                    ]);

                    $result = json_decode($response->getBody()->getContents(), true);

                    Log::info('Fallback message send successful', [
                        'message_id' => $result['message_id'] ?? 'unknown'
                    ]);

                    return $result;
                }
            } catch (GuzzleException $fallbackError) {
                Log::error('Fallback message send also failed: ' . $fallbackError->getMessage());
            }

            return null;
        }
    }

    /**
     * Get messages from a group channel
     *
     * @param string $channelUrl
     * @param int $limit
     * @return array|null
     */
    public function getMessages(string $channelUrl, int $limit = 20): ?array
    {
        try {
            // Use the client from the constructor which has the correct base URL
            $endpoint = "group_channels/{$channelUrl}/messages";
            $fullUrl = config('sendbird.api_url') . '/' . $endpoint;

            Log::info('Making Sendbird request to: ' . $fullUrl, [
                'channel_url' => $channelUrl,
                'limit' => $limit
            ]);

            // According to Sendbird API docs, we need to provide either message_ts or message_id
            // For initial load, we'll use the current timestamp to get the most recent messages
            $currentTimestamp = time() * 1000; // Convert to milliseconds

            $response = $this->client->get($endpoint, [
                'query' => [
                    'limit' => $limit,
                    'message_ts' => $currentTimestamp, // Current time in milliseconds
                    'prev_limit' => $limit, // Get messages before the timestamp
                    'include_reply_type' => 'all', // Include all types of replies
                ],
            ]);

            $result = json_decode($response->getBody()->getContents(), true);

            // Log successful response for debugging
            Log::info('Successfully retrieved messages', [
                'channel_url' => $channelUrl,
                'message_count' => count($result['messages'] ?? [])
            ]);

            // Also log the retrieved messages for debugging
            Log::info('Retrieved messages for channel', [
                'channel_url' => $channelUrl,
                'message_count' => count($result['messages'] ?? [])
            ]);

            return $result;
        } catch (GuzzleException $e) {
            Log::error('Sendbird get messages error: ' . $e->getMessage(), [
                'channel_url' => $channelUrl,
                'status_code' => $e->getCode(),
                'response' => $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : null
            ]);
            return null;
        }
    }

    /**
     * Delete a group channel
     *
     * @param string $channelUrl
     * @return bool
     */
    public function deleteChannel(string $channelUrl): bool
    {
        try {
            // Use the client from the constructor which has the correct base URL
            $endpoint = "group_channels/{$channelUrl}";
            $fullUrl = config('sendbird.api_url') . '/' . $endpoint;

            Log::info('Making Sendbird delete request to: ' . $fullUrl, [
                'channel_url' => $channelUrl
            ]);

            $response = $this->client->delete($endpoint);
            $statusCode = $response->getStatusCode();

            Log::info('Sendbird channel deleted successfully', [
                'channel_url' => $channelUrl,
                'status_code' => $statusCode
            ]);

            return $statusCode >= 200 && $statusCode < 300;
        } catch (GuzzleException $e) {
            Log::error('Sendbird delete channel error: ' . $e->getMessage(), [
                'channel_url' => $channelUrl,
                'status_code' => $e->getCode(),
                'response' => $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : null
            ]);
            return false;
        }
    }

    /**
     * Upload a file to Sendbird
     *
     * @param string $channelUrl
     * @param string $userId
     * @param \Illuminate\Http\UploadedFile $file
     * @param string|null $fileName
     * @return array|null
     */
    public function uploadFile(string $channelUrl, string $userId, \Illuminate\Http\UploadedFile $file, ?string $fileName = null): ?array
    {
        try {
            // Use the client from the constructor which has the correct base URL
            $endpoint = "group_channels/{$channelUrl}/messages";
            $fullUrl = config('sendbird.api_url') . '/' . $endpoint;

            // If no filename is provided, use the original name
            $fileName = $fileName ?? $file->getClientOriginalName();

            Log::info('Uploading file to Sendbird', [
                'channel_url' => $channelUrl,
                'user_id' => $userId,
                'file_name' => $fileName,
                'file_size' => $file->getSize(),
                'file_type' => $file->getMimeType()
            ]);

            // Determine message type based on file mime type
            $messageType = 'FILE';
            if (strpos($file->getMimeType(), 'image/') === 0) {
                $messageType = 'FILE'; // Sendbird handles images as files
            }

            // Create multipart request
            $response = $this->client->post($endpoint, [
                'multipart' => [
                    [
                        'name' => 'message_type',
                        'contents' => $messageType
                    ],
                    [
                        'name' => 'user_id',
                        'contents' => $userId
                    ],
                    [
                        'name' => 'file',
                        'contents' => fopen($file->getPathname(), 'r'),
                        'filename' => $fileName,
                        'headers' => [
                            'Content-Type' => $file->getMimeType()
                        ]
                    ]
                ]
            ]);

            $result = json_decode($response->getBody()->getContents(), true);

            Log::info('File uploaded successfully to Sendbird', [
                'channel_url' => $channelUrl,
                'user_id' => $userId,
                'message_id' => $result['message_id'] ?? 'unknown',
                'file_name' => $fileName
            ]);

            return $result;
        } catch (GuzzleException $e) {
            Log::error('Sendbird file upload error: ' . $e->getMessage(), [
                'channel_url' => $channelUrl,
                'user_id' => $userId,
                'file_name' => $fileName ?? 'unknown',
                'status_code' => $e->getCode(),
                'response' => $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : null
            ]);
            return null;
        }
    }
}
