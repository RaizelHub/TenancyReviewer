<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Central') }} · Sign in</title>
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 font-sans text-gray-900 antialiased">
    <main class="grid min-h-screen lg:grid-cols-2">
        <section class="relative hidden overflow-hidden bg-emerald-950 p-10 lg:flex lg:flex-col lg:justify-between">
            <div class="relative z-10 flex items-center gap-3 text-white"><span class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-500 text-lg font-bold">C</span><span><span class="block text-lg font-semibold">Central</span><span class="block text-xs text-emerald-200">Education management platform</span></span></div>
            <img src="https://images.unsplash.com/photo-1509062522246-3755977927d7?auto=format&fit=crop&w=1400&q=85" alt="Educator collaborating with students" class="absolute inset-0 h-full w-full object-cover opacity-35">
            <div class="relative z-10 max-w-lg"><span class="inline-flex rounded-full bg-white/10 px-3 py-1 text-xs font-semibold text-emerald-100">Central administration</span><h1 class="mt-5 text-4xl font-semibold leading-tight text-white">Manage every academy from one calm, capable workspace.</h1><p class="mt-5 max-w-md text-base leading-7 text-emerald-100">Review applications, manage tenants, and keep your learning network running smoothly.</p></div>
            <p class="relative z-10 text-sm text-emerald-200">© {{ date('Y') }} Central. All rights reserved.</p>
        </section>
        <section class="flex items-center justify-center p-6 sm:p-10">
            <div class="w-full max-w-md"><a href="/" class="mb-10 flex items-center gap-3 lg:hidden"><span class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-600 font-bold text-white">C</span><span class="font-semibold text-gray-900">Central</span></a>{{ $slot }}</div>
        </section>
    </main>
</body>
</html>
 