<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Subscription Successful</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <link href="https://fonts.bunny.net/css?family=poppins:300,400,500,600,700" rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <!-- Styles -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            scroll-behavior: smooth;
        }
        .bg-enhance::before,
        .bg-enhance::after {
            content: '';
            position: absolute;
            z-index: 0;
            border-radius: 9999px;
            opacity: 0.2;
            filter: blur(100px);
        }
        .bg-enhance::before {
            width: 400px;
            height: 400px;
            background: #7da3e0;
            top: -100px;
            left: -100px;
            animation: float 8s ease-in-out infinite;
        }
        .bg-enhance::after {
            width: 500px;
            height: 500px;
            background: #ffe5f3;
            bottom: -120px;
            right: -100px;
            animation: float 10s ease-in-out infinite reverse;
        }
        @keyframes float {
            0% { transform: translateY(0) translateX(0); }
            50% { transform: translateY(-20px) translateX(20px); }
            100% { transform: translateY(0) translateX(0); }
        }
        .gradient-text {
            background: linear-gradient(90deg, #4473be, #7da3e0);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
    </style>
</head>
<body class="relative bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] min-h-screen flex flex-col items-center overflow-x-hidden">
    <!-- Background layer -->
    <div class="absolute inset-0 bg-enhance pointer-events-none"></div>

    <!-- Header -->
    <header class="w-full max-w-7xl py-6 px-6 lg:px-8 mb-8 z-20 sticky top-0 bg-[#FDFDFC]/80 dark:bg-[#0a0a0a]/80 backdrop-blur-md">
        <div class="flex justify-between items-center">
            <!-- Logo -->
            <div class="flex items-center">
                <div class="h-10 w-10 rounded-full bg-gradient-to-r from-[#4473be] to-[#7da3e0] flex items-center justify-center text-white font-bold text-xl">C</div>
                <span class="ml-3 text-xl font-bold gradient-text">Central</span>
            </div>

            <!-- Navigation -->
            <nav class="hidden md:flex items-center gap-8">
                <a href="{{ route('welcome') }}" class="text-[#1b1b18] dark:text-[#EDEDEC] hover:text-[#4473be] dark:hover:text-[#7da3e0] transition">Home</a>
            </nav>
        </div>
    </header>

    <!-- Success Content -->
    <div class="w-full max-w-4xl px-6 lg:px-8 py-16 z-10 flex flex-col items-center justify-center">
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-xl w-full max-w-2xl text-center">
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-check text-4xl text-green-500"></i>
            </div>
            
            <h1 class="text-3xl font-bold mb-4 gradient-text">Application Submitted Successfully!</h1>
            
            <p class="text-lg text-gray-600 dark:text-gray-400 mb-6">
                Thank you for your interest in our platform. Your application has been received and is pending review.
            </p>
            
            <div class="bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500 p-4 text-blue-700 dark:text-blue-300 text-left mb-6">
                <p class="font-semibold">What happens next?</p>
                <ul class="list-disc ml-5 mt-2">
                    <li>Our team will review your application</li>
                    <li>You'll receive an email notification when your application is approved</li>
                    <li>The email will contain your login credentials and domain information</li>
                </ul>
            </div>
            
            <a href="{{ route('welcome') }}" class="inline-block px-6 py-3 bg-gradient-to-r from-[#4473be] to-[#7da3e0] text-white font-medium rounded-lg hover:from-[#3a5fa0] hover:to-[#5b86cc] transition">
                Return to Home
            </a>
        </div>
    </div>

    <!-- Footer -->
    <footer class="w-full max-w-7xl px-6 lg:px-8 mt-auto mb-8 z-10">
        <div class="border-t border-gray-200 dark:border-gray-800 pt-8 text-center text-gray-600 dark:text-gray-400">
            <p>&copy; {{ date('Y') }} Central. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
