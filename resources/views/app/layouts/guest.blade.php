<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' || window.matchMedia('(prefers-color-scheme: dark)').matches }" x-init="$watch('darkMode', val => { localStorage.setItem('darkMode', val); if (val) { document.documentElement.classList.add('dark'); } else { document.documentElement.classList.remove('dark'); } }); if (darkMode) { document.documentElement.classList.add('dark'); }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ tenant() ? tenant()->name : config('app.name', 'Tenant Portal') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=poppins:300,400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

        <!-- Alpine.js -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body {
                font-family: 'Poppins', sans-serif;
            }

            .bg-auth-pattern {
                position: relative;
                overflow: hidden;
                background-color: #f9fafb;
            }

            .dark .bg-auth-pattern {
                background-color: #111827;
            }

            .bg-auth-pattern::before,
            .bg-auth-pattern::after {
                content: '';
                position: absolute;
                border-radius: 50%;
                opacity: 0.15;
                filter: blur(80px);
            }

            .bg-auth-pattern::before {
                width: 300px;
                height: 300px;
                background: #4473be;
                top: -50px;
                left: -50px;
                animation: float 8s ease-in-out infinite;
            }

            .bg-auth-pattern::after {
                width: 350px;
                height: 350px;
                background: #7da3e0;
                bottom: -80px;
                right: -50px;
                animation: float 10s ease-in-out infinite reverse;
            }

            @keyframes float {
                0% { transform: translateY(0px); }
                50% { transform: translateY(-20px); }
                100% { transform: translateY(0px); }
            }

            .auth-card {
                backdrop-filter: blur(10px);
                background-color: rgba(255, 255, 255, 0.8);
                border: 1px solid rgba(209, 213, 219, 0.3);
                transition: all 0.3s ease;
            }

            .dark .auth-card {
                background-color: rgba(31, 41, 55, 0.8);
                border: 1px solid rgba(55, 65, 81, 0.3);
            }

            .auth-input {
                background-color: rgba(255, 255, 255, 0.9);
                border: 1px solid rgba(209, 213, 219, 0.5);
                transition: all 0.3s ease;
            }

            .dark .auth-input {
                background-color: rgba(17, 24, 39, 0.8);
                border: 1px solid rgba(55, 65, 81, 0.5);
            }

            .auth-input:focus {
                background-color: white;
                border-color: rgba(68, 115, 190, 0.5);
                box-shadow: 0 0 0 3px rgba(68, 115, 190, 0.25);
            }

            .dark .auth-input:focus {
                background-color: rgba(17, 24, 39, 1);
                border-color: rgba(68, 115, 190, 0.5);
                box-shadow: 0 0 0 3px rgba(68, 115, 190, 0.25);
            }

            .auth-button {
                transition: all 0.3s ease;
                background-image: linear-gradient(to right, #4473be, #7da3e0);
            }

            .auth-button:hover {
                transform: translateY(-1px);
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
                background-image: linear-gradient(to right, #3a5fa0, #6b91ce);
            }

            .auth-button:active {
                transform: translateY(0);
            }

            .gradient-text {
                background: linear-gradient(to right, #4473be, #7da3e0);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                color: transparent;
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 dark:text-gray-100 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-auth-pattern relative">
           
            <div class="z-10">
                <a href="/" class="flex items-center justify-center">
                    <div class="h-14 w-14 rounded-full bg-gradient-to-r from-[#4473be] to-[#7da3e0] flex items-center justify-center text-white font-bold text-2xl shadow-lg">
                        {{ tenant() ? substr(tenant()->name, 0, 1) : 'T' }}
                    </div>
                    <span class="ml-3 text-2xl font-bold gradient-text">{{ tenant() ? tenant()->name : config('app.name', 'Tenant Portal') }}</span>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-8 px-8 py-8 bg-white dark:bg-gray-800 shadow-2xl rounded-2xl auth-card z-10">
                {{ $slot }}
            </div>

            <div class="mt-8 text-center text-sm text-gray-600 dark:text-gray-400 z-10">
                &copy; {{ date('Y') }} {{ config('app.name', 'Tenant Portal') }}. All rights reserved.
            </div>
        </div>
    </body>
</html>
