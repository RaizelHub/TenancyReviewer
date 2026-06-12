<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use GuzzleHttp\Client;

class SendbirdServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('sendbird', function ($app) {
            // Get configuration from config file
            $appId = strtoupper(config('sendbird.app_id')); // Ensure app ID is uppercase
            $apiUrl = config('sendbird.api_url');
            $apiToken = config('sendbird.api_token');

            // Log the configuration for debugging
            \Illuminate\Support\Facades\Log::info('Sendbird configuration', [
                'api_url' => $apiUrl,
                'app_id' => $appId
            ]);

            // Make sure the base URI ends with a slash to properly resolve relative URLs
            // Ensure the API URL uses the same case as the app ID
            $formattedAppId = strtoupper($appId);
            $baseUri = 'https://api-' . $formattedAppId . '.sendbird.com/v3/';

            // Log the actual base URI being used
            \Illuminate\Support\Facades\Log::info('Sendbird base URI', [
                'base_uri' => $baseUri
            ]);

            // Create a client with proper configuration
            $client = new Client([
                'base_uri' => $baseUri,
                'headers' => [
                    'Api-Token' => $apiToken,
                    'Content-Type' => 'application/json',
                ],
                // Add some additional options for better reliability
                'http_errors' => true, // Enable exceptions for 4xx/5xx responses
                'connect_timeout' => 5, // 5 second connection timeout
                'timeout' => 10, // 10 second request timeout
                'verify' => true, // Verify SSL certificates
            ]);

            return new \App\Services\SendbirdService($client, $appId);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../../config/sendbird.php' => config_path('sendbird.php'),
        ], 'sendbird-config');
    }
}
