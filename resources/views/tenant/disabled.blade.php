<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Domain Disabled</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #4473be 0%, #7da3e0 100%);
        }
        .gradient-text {
            background: linear-gradient(135deg, #4473be 0%, #7da3e0 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen flex flex-col items-center justify-center bg-gray-100 dark:bg-gray-900 p-4">
        <div class="w-full max-w-md p-8 bg-white dark:bg-gray-800 shadow-xl rounded-lg text-center">
            <div class="mb-6">
                <div class="h-20 w-20 mx-auto rounded-full bg-gradient-to-r from-[#4473be] to-[#7da3e0] flex items-center justify-center text-white font-bold text-3xl shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </div>
            
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Domain Disabled</h1>
            
            <p class="text-gray-600 dark:text-gray-400 mb-6">
                Your domain has been disabled. Please contact customer service for assistance.
            </p>
            
            <div class="p-4 bg-yellow-50 dark:bg-yellow-900/30 rounded-lg text-sm text-yellow-700 dark:text-yellow-300 mb-6">
                <p>If you believe this is an error, please contact the system administrator.</p>
            </div>
            
            <div class="flex justify-center">
                <a href="mailto:support@example.com" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    Contact Support
                </a>
            </div>
        </div>
        
        <div class="mt-8 text-center text-sm text-gray-600 dark:text-gray-400">
            &copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.
        </div>
    </div>
</body>
</html>
