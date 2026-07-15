<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' || window.matchMedia('(prefers-color-scheme: dark)').matches }" x-init="$watch('darkMode', val => { localStorage.setItem('darkMode', val); if (val) { document.documentElement.classList.add('dark'); } else { document.documentElement.classList.remove('dark'); } }); if (darkMode) { document.documentElement.classList.add('dark'); }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Central') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { font-family: 'Inter', 'Plus Jakarta Sans', sans-serif; }
            .central-shell { color: #111827; }
            .central-shell .app-surface { border-color: #e5e7eb; }
            .central-shell table thead { background: #f8fafc; }
            .central-shell table tbody tr { transition: background-color .15s ease; }
            .central-shell table tbody tr:hover { background: #ecfdf5; }
        </style>

        @stack('head')
    </head>
    <body class="font-sans antialiased bg-slate-50">
        <x-layouts.navigation>
            <!-- Page Content -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow-sm mb-6 rounded-lg">
                    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            {{ $slot }}
        </x-layouts.navigation>
    </body>
</html>
