@php
    use Illuminate\Support\Facades\Auth;
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ tenant('name') ?? config('app.name', 'Laravel') }} - Classroom</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Google Fonts - Poppins -->
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Custom Styles -->
        <style>
            :root {
                /* Modern color palette */
                --primary-color: #6366f1;
                --primary-hover: #4f46e5;
                --secondary-color: #06b6d4;
                --accent-color: #8b5cf6;
                --success-color: #10b981;
                --warning-color: #f59e0b;
                --danger-color: #ef4444;
                --info-color: #3b82f6;

                /* Neutral colors */
                --gray-50: #f9fafb;
                --gray-100: #f3f4f6;
                --gray-200: #e5e7eb;
                --gray-300: #d1d5db;
                --gray-400: #9ca3af;
                --gray-500: #6b7280;
                --gray-600: #4b5563;
                --gray-700: #374151;
                --gray-800: #1f2937;
                --gray-900: #111827;

                /* Shadows */
                --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
                --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
                --shadow-md: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
                --shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);

                /* Border radius */
                --radius-sm: 0.125rem;
                --radius: 0.25rem;
                --radius-md: 0.375rem;
                --radius-lg: 0.5rem;
                --radius-xl: 0.75rem;
                --radius-2xl: 1rem;
                --radius-3xl: 1.5rem;
                --radius-full: 9999px;
            }

            body {
                font-family: 'Poppins', sans-serif;
            }

            .bg-primary {
                background-color: var(--primary-color);
            }

            .text-primary {
                color: var(--primary-color);
            }

            .border-primary {
                border-color: var(--primary-color);
            }

            .hover\:bg-primary-hover:hover {
                background-color: var(--primary-hover);
            }

            /* Glass effect */
            .glass {
                background: rgba(255, 255, 255, 0.25);
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.18);
            }

            .dark .glass {
                background: rgba(17, 24, 39, 0.75);
                border: 1px solid rgba(255, 255, 255, 0.08);
            }

            /* Custom scrollbar */
            ::-webkit-scrollbar {
                width: 6px;
                height: 6px;
            }

            ::-webkit-scrollbar-track {
                background: #f1f1f1;
                border-radius: var(--radius-full);
            }

            ::-webkit-scrollbar-thumb {
                background: #c5c5c5;
                border-radius: var(--radius-full);
            }

            ::-webkit-scrollbar-thumb:hover {
                background: #a8a8a8;
            }

            .dark ::-webkit-scrollbar-track {
                background: #1f2937;
            }

            .dark ::-webkit-scrollbar-thumb {
                background: #4b5563;
            }

            .dark ::-webkit-scrollbar-thumb:hover {
                background: #6b7280;
            }

            /* Card hover effects */
            .card-hover {
                transition: all 0.3s ease;
            }

            .card-hover:hover {
                transform: translateY(-5px);
                box-shadow: var(--shadow-lg);
            }

            /* Soft shadows */
            .shadow-soft {
                box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
            }

            .dark .shadow-soft {
                box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.2), 0 10px 10px -5px rgba(0, 0, 0, 0.1);
            }

            /* Animated gradient background */
            .animated-gradient {
                background: linear-gradient(-45deg, var(--primary-color), var(--secondary-color), var(--accent-color), #ec4899);
                background-size: 400% 400%;
                animation: gradient 15s ease infinite;
                background-image: url('https://images.unsplash.com/photo-1571260899304-425eee4c7efc?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1470&q=80');
                background-blend-mode: overlay;
                background-position: center;
                background-size: cover;
            }

            /* Soft gradient backgrounds */
            .bg-gradient-blue-purple {
                background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            }

            .bg-gradient-green-blue {
                background: linear-gradient(135deg, #10b981 0%, #3b82f6 100%);
            }

            .bg-gradient-orange-pink {
                background: linear-gradient(135deg, #f59e0b 0%, #ec4899 100%);
            }

            .bg-gradient-blue-teal {
                background: linear-gradient(135deg, #3b82f6 0%, #06b6d4 100%);
            }

            /* Enhanced gradients for student dashboard */
            .bg-gradient-indigo-blue {
                background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
            }

            .bg-gradient-purple-indigo {
                background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%);
            }

            .bg-gradient-blue-purple {
                background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
            }

            @keyframes gradient {
                0% {
                    background-position: 0% 50%;
                }
                50% {
                    background-position: 100% 50%;
                }
                100% {
                    background-position: 0% 50%;
                }
            }

            /* Pulse animation */
            @keyframes pulse {
                0%, 100% {
                    opacity: 1;
                }
                50% {
                    opacity: 0.5;
                }
            }

            .animate-pulse {
                animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
            }

            /* Fade in animation */
            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(10px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .fade-in {
                animation: fadeIn 0.5s ease-out forwards;
            }

            /* Staggered fade in for children */
            .stagger-fade-in > * {
                opacity: 0;
                animation: fadeIn 0.5s ease-out forwards;
            }

            .stagger-fade-in > *:nth-child(1) { animation-delay: 0.1s; }
            .stagger-fade-in > *:nth-child(2) { animation-delay: 0.2s; }
            .stagger-fade-in > *:nth-child(3) { animation-delay: 0.3s; }
            .stagger-fade-in > *:nth-child(4) { animation-delay: 0.4s; }
            .stagger-fade-in > *:nth-child(5) { animation-delay: 0.5s; }
            .stagger-fade-in > *:nth-child(6) { animation-delay: 0.6s; }

            /* Line clamp utilities */
            .line-clamp-1 {
                display: -webkit-box;
                -webkit-line-clamp: 1;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }

            .line-clamp-2 {
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }

            .line-clamp-3 {
                display: -webkit-box;
                -webkit-line-clamp: 3;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }

            /* Hover transitions */
            .hover-lift {
                transition: transform 0.2s ease-out;
            }

            .hover-lift:hover {
                transform: translateY(-3px);
            }

            /* Custom dark mode color */
            .dark .dark\:bg-gray-750 {
                background-color: #283141;
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <!-- Icon Persistence Script - Load Early -->
        <script src="{{ tenant_asset('js/icon-persistence.js') }}"></script>
        <div class="bg-gray-50 dark:bg-gray-900">
            <!-- Top Navigation -->
            @if(Auth::guard('student')->check())
                <div class="fixed top-0 left-0 right-0 z-50">
                    @include('app.layouts.student-topbar')
                </div>
            @else
                @include('app.layouts.navigation')
            @endif

            <div class="flex pt-16 min-h-screen"> <!-- Main container with padding-top for fixed navbar -->
                <!-- Sidebar -->
                @if(Auth::guard('student')->check())
                    <div class="fixed left-0 top-16 bottom-0 z-40">
                        @include('app.layouts.student-sidebar')
                    </div>
                    <div class="w-64 flex-shrink-0"></div> <!-- Spacer for fixed sidebar -->
                @else
                    @include('app.layouts.sidebar')
                @endif

                <!-- Main Content -->
                <div class="flex-1 flex flex-col overflow-visible">

                    <!-- Page Heading -->
                    @isset($header)
                        <header class="bg-white dark:bg-gray-800 shadow-sm">
                            <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                                {{ $header }}
                            </div>
                        </header>
                    @endisset

                    <!-- Page Content -->
                    <main class="flex-1 p-4 mt-0 bg-gray-50 dark:bg-gray-900">
                        {{ $slot }}
                    </main>

                    <!-- Footer -->
                    <footer class="bg-white dark:bg-gray-800 shadow-inner py-3 px-4 sm:px-6 lg:px-8 mt-auto">
                        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center">
                            <div class="text-sm text-gray-500 dark:text-gray-400 mb-2 md:mb-0">
                                &copy; {{ date('Y') }} {{ tenant('name') ?? config('app.name') }}. All rights reserved.
                            </div>
                            <div class="flex space-x-4">
                                <a href="#" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                                    <i class="fab fa-facebook"></i>
                                </a>
                                <a href="#" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="#" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                                    <i class="fab fa-instagram"></i>
                                </a>
                            </div>
                        </div>
                    </footer>
                </div>
            </div>
        </div>
        <!-- Emergency fix for icon selector -->
        <script src="{{ asset('js/fix-icon-selector.js') }}"></script>
        @stack('scripts')
    </body>
</html>
