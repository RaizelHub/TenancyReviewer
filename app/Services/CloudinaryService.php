<?php

namespace App\Services;

use Cloudinary\Cloudinary;
use Cloudinary\Api\Upload\UploadApi;
use Cloudinary\Configuration\Configuration;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\CurlHandler;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class CloudinaryService
{
    /**
     * The Cloudinary instance.
     *
     * @var Cloudinary
     */
    protected $cloudinary;

    /**
     * The Upload API instance.
     *
     * @var UploadApi
     */
    protected $uploadApi;

    /**
     * Create a new CloudinaryService instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Set the SSL certificate path directly in the environment
        putenv('CURL_CA_BUNDLE=C:/xampp/php/extras/ssl/cacert.pem');

        // Create a Cloudinary configuration
        $config = new Configuration([
            'cloud' => [
                'cloud_name' => config('filesystems.disks.cloudinary.cloud_name'),
                'api_key' => config('filesystems.disks.cloudinary.api_key'),
                'api_secret' => config('filesystems.disks.cloudinary.api_secret'),
            ],
            'url' => [
                'secure' => true
            ],
            // Configure the HTTP client options with explicit SSL certificate path
            'api' => [
                'http_client_options' => [
                    'verify' => 'C:/xampp/php/extras/ssl/cacert.pem',
                    'curl' => [
                        CURLOPT_CAINFO => 'C:/xampp/php/extras/ssl/cacert.pem',
                        CURLOPT_SSL_VERIFYPEER => true,
                        CURLOPT_SSL_VERIFYHOST => 2
                    ],
                ],
            ],
        ]);

        // Create a new Cloudinary instance with the configuration
        $this->cloudinary = new Cloudinary($config);

        // Get the Upload API instance
        $this->uploadApi = $this->cloudinary->uploadApi();

        // Log the configuration
        Log::info('Cloudinary service initialized', [
            'cloud_name' => config('filesystems.disks.cloudinary.cloud_name'),
            'api_key' => config('filesystems.disks.cloudinary.api_key'),
            'ssl_verification' => 'disabled'
        ]);
    }

    /**
     * Upload an image to Cloudinary
     *
     * @param UploadedFile $file
     * @param string $folder
     * @return string|null
     */
    public function uploadImage(UploadedFile $file, string $folder = 'student-photos'): ?string
    {
        try {
            // Log the Cloudinary configuration
            Log::info('Cloudinary configuration', [
                'cloud_name' => config('filesystems.disks.cloudinary.cloud_name'),
                'api_key' => config('filesystems.disks.cloudinary.api_key'),
                'api_secret' => config('filesystems.disks.cloudinary.api_secret'),
            ]);

            // No need to set custom cURL options anymore
            $curlOptions = [];

            // Log the file details
            Log::info('Uploading file to Cloudinary', [
                'file_path' => $file->getRealPath(),
                'file_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize(),
                'file_mime' => $file->getMimeType()
            ]);

            // Set the SSL certificate path directly in the environment
            putenv('CURL_CA_BUNDLE=C:/xampp/php/extras/ssl/cacert.pem');

            // Upload the image to Cloudinary using the UploadApi with explicit SSL certificate path
            $result = $this->uploadApi->upload($file->getRealPath(), [
                'folder' => $folder,
                'transformation' => [
                    'quality' => 'auto',
                    'fetch_format' => 'auto',
                ]
            ], [
                'curl_options' => [
                    CURLOPT_CAINFO => 'C:/xampp/php/extras/ssl/cacert.pem',
                    CURLOPT_SSL_VERIFYPEER => true,
                    CURLOPT_SSL_VERIFYHOST => 2
                ]
            ]);

            // Log the full result for debugging
            Log::info('Cloudinary upload raw result', [
                'result' => $result
            ]);

            // Log the successful upload
            Log::info('Cloudinary upload successful', [
                'secure_url' => $result['secure_url'],
                'public_id' => $result['public_id'],
            ]);

            // Return the secure URL
            return $result['secure_url'];
        } catch (\Exception $e) {
            // Get detailed error information
            $errorMessage = $e->getMessage();
            $errorFile = $e->getFile();
            $errorLine = $e->getLine();
            $errorTrace = $e->getTraceAsString();

            // Log the error with detailed information
            Log::error('Cloudinary upload error: ' . $errorMessage, [
                'file' => $errorFile,
                'line' => $errorLine,
                'trace' => $errorTrace,
                'error_class' => get_class($e)
            ]);

            // If it's a specific Cloudinary error, log more details
            if (strpos(get_class($e), 'Cloudinary') !== false) {
                Log::error('Cloudinary specific error details', [
                    'error_type' => get_class($e),
                    'full_message' => $errorMessage
                ]);
            }

            // Return null for fallback handling
            return null;
        }
    }

    /**
     * Delete an image from Cloudinary
     *
     * @param string $publicId
     * @return bool
     */
    public function deleteImage(string $publicId): bool
    {
        try {
            // Extract public ID from URL if needed
            if (strpos($publicId, 'http') === 0) {
                $parts = explode('/', $publicId);
                $filename = end($parts);
                $publicId = pathinfo($filename, PATHINFO_FILENAME);
            }

            // No need to set custom cURL options anymore
            $curlOptions = [];

            // Set the SSL certificate path directly in the environment
            putenv('CURL_CA_BUNDLE=C:/xampp/php/extras/ssl/cacert.pem');

            // Delete the image from Cloudinary with explicit SSL certificate path
            $result = $this->uploadApi->destroy($publicId, [
                'curl_options' => [
                    CURLOPT_CAINFO => 'C:/xampp/php/extras/ssl/cacert.pem',
                    CURLOPT_SSL_VERIFYPEER => true,
                    CURLOPT_SSL_VERIFYHOST => 2
                ]
            ]);

            return $result['result'] === 'ok';
        } catch (\Exception $e) {
            Log::error('Cloudinary delete error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return false;
        }
    }

    /**
     * Get the public ID from a Cloudinary URL
     *
     * @param string $url
     * @return string|null
     */
    public function getPublicIdFromUrl(string $url): ?string
    {
        try {
            // Parse the URL to extract the public ID
            $parsedUrl = parse_url($url);
            $path = $parsedUrl['path'] ?? '';

            // Remove version number if present
            $pathParts = explode('/', $path);
            $filename = end($pathParts);

            // Remove file extension
            $publicId = pathinfo($filename, PATHINFO_FILENAME);

            return $publicId;
        } catch (\Exception $e) {
            Log::error('Error extracting public ID: ' . $e->getMessage());
            return null;
        }
    }
}
