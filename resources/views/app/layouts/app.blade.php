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
        @stack('styles')

        <!-- Google Fonts - Poppins -->
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Custom Styles -->
        <style>
            :root { --primary-color: #059669; --primary-hover: #047857; --success-color: #10b981; --warning-color: #f59e0b; --danger-color: #ef4444; --info-color: #0ea5e9; }
            .app-sidebar { background: #064e3b !important; border-color: rgba(255,255,255,.12) !important; }
            .app-sidebar nav { padding-top: 1.25rem; }
            .app-sidebar a { color: #d1fae5 !important; }
            .app-sidebar a:hover { background: #065f46 !important; color: #fff !important; }
            .app-sidebar [\:class] { transition: background-color .15s ease, color .15s ease; }
            .app-sidebar h3, .app-sidebar .text-gray-500, .app-sidebar .text-gray-400 { color: #a7f3d0 !important; }
            .app-sidebar .bg-indigo-100, .app-sidebar .bg-blue-100, .app-sidebar .bg-green-100, .app-sidebar .bg-purple-100, .app-sidebar .bg-amber-100, .app-sidebar .bg-red-100, .app-sidebar .bg-teal-100 { background-color: rgba(255,255,255,.12) !important; color: #d1fae5 !important; }
            .app-sidebar .bg-indigo-600 { background-color: #059669 !important; }
            .app-sidebar .text-indigo-600, .app-sidebar .text-blue-600, .app-sidebar .text-green-600, .app-sidebar .text-purple-600, .app-sidebar .text-amber-600, .app-sidebar .text-red-600, .app-sidebar .text-teal-600 { color: #d1fae5 !important; }
            .app-sidebar .text-gray-800, .app-sidebar .text-gray-900, .app-sidebar .text-gray-700, .app-sidebar .dark\:text-white { color: #ffffff !important; }
            .app-sidebar > div:first-of-type { border-color: rgba(255,255,255,.16) !important; }
            .app-sidebar a { min-height: 44px; border-radius: .65rem; }
            .app-sidebar .group:hover { background-color: #065f46 !important; }
            .app-sidebar .bg-white\/20 { background-color: rgba(255,255,255,.16) !important; }
            .tenant-shell main { background: #f8fafc; }
            .tenant-shell main .max-w-7xl { max-width: 80rem; }
            .tenant-shell main .bg-white { border-color: #e5e7eb; }
            .tenant-shell main table { border-collapse: separate; border-spacing: 0; width: 100%; }
            .tenant-shell main table thead { background: #f8fafc; }
            .tenant-shell main table th { color: #64748b; font-weight: 600; }
            .tenant-shell main table tbody tr { transition: background-color .15s ease; }
            .tenant-shell main table tbody tr:hover { background: #ecfdf5; }
            .tenant-shell main input:focus, .tenant-shell main select:focus, .tenant-shell main textarea:focus { border-color: #059669 !important; box-shadow: 0 0 0 1px #059669 !important; }
            .tenant-shell main > .py-12 { padding-top: .5rem; padding-bottom: 1.5rem; }
            .tenant-shell main .rounded-xl.bg-white, .tenant-shell main .rounded-lg.bg-white { border-color: #e5e7eb; box-shadow: 0 1px 2px rgba(15,23,42,.05); }
            .tenant-shell main .shadow-lg, .tenant-shell main .shadow-xl, .tenant-shell main .shadow-2xl { box-shadow: 0 4px 12px rgba(15,23,42,.08); }
            .tenant-shell main .hover-lift:hover, .tenant-shell main .hover\:shadow-lg:hover { transform: none; }
            .tenant-shell main .divide-y > * { border-color: #e5e7eb; }
            .tenant-shell main .bg-gray-50 { background-color: #f8fafc; }
            .teacher-portal main .bg-indigo-600, .teacher-portal main .bg-blue-600, .teacher-portal main .bg-purple-600 { background-color: #059669; }
            .teacher-portal main .hover\:bg-indigo-700:hover, .teacher-portal main .hover\:bg-blue-700:hover, .teacher-portal main .hover\:bg-purple-700:hover { background-color: #047857; }
            .teacher-portal main .text-indigo-600, .teacher-portal main .text-blue-600, .teacher-portal main .text-purple-600 { color: #059669; }
            .student-portal main .bg-indigo-600, .student-portal main .bg-blue-600, .student-portal main .bg-purple-600 { background-color: #059669; }
            .student-portal main .hover\:bg-indigo-700:hover, .student-portal main .hover\:bg-blue-700:hover, .student-portal main .hover\:bg-purple-700:hover { background-color: #047857; }
            .student-portal main .text-indigo-600, .student-portal main .text-blue-600, .student-portal main .text-purple-600 { color: #059669; }
            @media (max-width: 767px) { .app-sidebar { position: fixed !important; inset: 4rem auto 0 0; z-index: 60; height: auto !important; } .app-sidebar.w-20 { transform: translateX(-100%); } }
        </style>
    </head>
    <body class="tenant-shell {{ Auth::guard('student')->check() ? 'student-portal' : 'teacher-portal' }} font-sans antialiased">
        <!-- Icon Persistence Script - Load Early -->
        <script src="{{ tenant_asset('js/icon-persistence.js') }}"></script>
        <div class="min-h-screen bg-slate-50">
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
                    <div class="hidden w-64 flex-shrink-0 md:block"></div> <!-- Spacer for fixed sidebar -->
                @else
                    @include('app.layouts.sidebar')
                @endif

                <!-- Main Content -->
                <div class="flex-1 flex flex-col overflow-visible">

                    <!-- Page Heading -->
                    @isset($header)
                        <header class="app-page-header">
                            <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                                {{ $header }}
                            </div>
                        </header>
                    @endisset

                    <!-- Page Content -->
                    <main class="flex-1 bg-slate-50 p-4 sm:p-6 lg:p-8">
                        {{ $slot }}
                    </main>

                    <!-- Footer -->
                    <footer class="mt-auto border-t border-gray-200 bg-white py-4 px-4 sm:px-6 lg:px-8">
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
