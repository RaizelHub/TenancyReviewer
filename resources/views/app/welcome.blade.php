<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ tenant('name') ?? config('app.name', 'Classroom') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 font-sans text-gray-900 antialiased">
    <div class="min-h-screen">
        <header class="border-b border-gray-200 bg-white">
            <div class="mx-auto flex h-20 max-w-7xl items-center justify-between px-6 lg:px-8">
                <a href="/" class="flex min-w-0 items-center gap-3"><span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-600 text-lg font-bold text-white">{{ strtoupper(substr(tenant('name') ?? 'C', 0, 1)) }}</span><span class="min-w-0"><span class="block truncate text-base font-semibold text-gray-900">{{ tenant('name') ?? config('app.name', 'Classroom') }}</span><span class="block text-xs text-gray-500">Learning workspace</span></span></a>
                @auth<a href="{{ url('/dashboard') }}" class="app-btn-primary">Open dashboard <i class="fas fa-arrow-right"></i></a>@else<a href="{{ route('login', [], false) }}" class="app-btn-primary">Sign in <i class="fas fa-arrow-right-to-bracket"></i></a>@endauth
            </div>
        </header>

        <main>
            <section class="mx-auto grid max-w-7xl items-center gap-12 px-6 py-16 lg:grid-cols-2 lg:px-8 lg:py-24">
                <div><span class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700"><span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>Connected learning</span><h1 class="mt-6 text-4xl font-semibold tracking-tight text-gray-900 sm:text-5xl">A better home for <span class="text-emerald-700">teaching and learning.</span></h1><p class="mt-6 max-w-xl text-lg leading-8 text-gray-600">{{ tenant('name') ?? 'Your classroom' }} brings subjects, activities, materials, and classroom communication into one focused workspace.</p><div class="mt-8 flex flex-col gap-3 sm:flex-row">@auth<a href="{{ url('/dashboard') }}" class="app-btn-primary">Go to dashboard <i class="fas fa-arrow-right"></i></a>@else<a href="{{ route('login', [], false) }}" class="app-btn-primary">Sign in to continue <i class="fas fa-arrow-right-to-bracket"></i></a>@endauth<a href="#features" class="app-btn-secondary">Explore features</a></div><div class="mt-10 flex flex-wrap gap-x-6 gap-y-3 text-sm text-gray-600"><span><i class="fas fa-check mr-2 text-emerald-600"></i>Organized learning</span><span><i class="fas fa-check mr-2 text-emerald-600"></i>Secure access</span><span><i class="fas fa-check mr-2 text-emerald-600"></i>Built for focus</span></div></div>
                <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white p-3 shadow-md">
    <div class="flex h-[24rem] flex-col rounded-xl bg-emerald-950 p-5 sm:h-[30rem] sm:p-7" role="img" aria-label="Illustration of an organized digital classroom workspace">
        <div class="flex items-center justify-between"><div class="flex items-center gap-2"><span class="h-3 w-3 rounded-full bg-emerald-300"></span><span class="text-sm font-semibold text-emerald-50">Classroom workspace</span></div><span class="rounded-lg bg-white/10 px-3 py-1 text-xs font-medium text-emerald-100">Today</span></div>
        <div class="mt-7 grid flex-1 grid-cols-5 gap-4"><div class="col-span-2 rounded-xl bg-white p-4"><div class="h-3 w-20 rounded bg-emerald-100"></div><div class="mt-5 space-y-3"><div class="rounded-lg border border-gray-100 p-3"><div class="h-2 w-16 rounded bg-gray-200"></div><div class="mt-2 h-2 w-full rounded bg-gray-100"></div></div><div class="rounded-lg border border-gray-100 p-3"><div class="h-2 w-12 rounded bg-gray-200"></div><div class="mt-2 h-2 w-4/5 rounded bg-gray-100"></div></div></div></div><div class="col-span-3 flex flex-col rounded-xl bg-emerald-800 p-5"><span class="inline-flex w-fit rounded-full bg-emerald-700 px-2.5 py-1 text-xs font-semibold text-emerald-100">Active learning</span><div class="mt-auto"><div class="h-3 w-3/4 rounded bg-emerald-200"></div><div class="mt-3 h-2 w-full rounded bg-emerald-700"></div><div class="mt-2 h-2 w-2/3 rounded bg-emerald-700"></div><div class="mt-5 grid grid-cols-3 gap-2"><span class="h-12 rounded-lg bg-emerald-700"></span><span class="h-12 rounded-lg bg-emerald-700"></span><span class="h-12 rounded-lg bg-emerald-700"></span></div></div></div></div>
        <div class="mt-5 flex items-center gap-3"><span class="flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-500 text-white"><i class="fas fa-graduation-cap"></i></span><div><p class="text-sm font-medium text-white">Everything in one place</p><p class="text-xs text-emerald-200">Subjects, activities, and progress</p></div></div>
    </div>
</div>
            </section>

            <section id="features" class="border-y border-gray-200 bg-white">
                <div class="mx-auto max-w-7xl px-6 py-16 lg:px-8"><div class="max-w-2xl"><p class="text-sm font-semibold text-emerald-700">Everything in one place</p><h2 class="mt-3 text-3xl font-semibold tracking-tight text-gray-900">Designed around the academic day.</h2><p class="mt-4 text-base leading-7 text-gray-600">A clean, reliable workspace for instructors and students to keep learning moving forward.</p></div>
                    <div class="mt-10 grid grid-cols-1 gap-5 md:grid-cols-3"><article class="rounded-2xl border border-gray-200 p-6"><span class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700"><i class="fas fa-book-open"></i></span><h3 class="mt-5 font-semibold text-gray-900">Structured subjects</h3><p class="mt-2 text-sm leading-6 text-gray-600">Keep classes, materials, activities, and student work organized by subject.</p></article><article class="rounded-2xl border border-gray-200 p-6"><span class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700"><i class="fas fa-list-check"></i></span><h3 class="mt-5 font-semibold text-gray-900">Clear progress</h3><p class="mt-2 text-sm leading-6 text-gray-600">Stay on top of assignments, feedback, submissions, and learning milestones.</p></article><article class="rounded-2xl border border-gray-200 p-6"><span class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700"><i class="fas fa-comments"></i></span><h3 class="mt-5 font-semibold text-gray-900">Better communication</h3><p class="mt-2 text-sm leading-6 text-gray-600">Use focused classroom conversations to keep people connected and informed.</p></article></div>
                </div>
            </section>
            <section class="mx-auto max-w-7xl px-6 py-16 lg:px-8"><div class="flex flex-col items-start justify-between gap-6 rounded-2xl border border-emerald-800 bg-emerald-700 p-8 text-white md:flex-row md:items-center md:p-10"><div><h2 class="text-2xl font-semibold">Ready to continue?</h2><p class="mt-2 text-emerald-100">Sign in to access your personalized workspace.</p></div>@auth<a href="{{ url('/dashboard') }}" class="app-btn-secondary">Open dashboard</a>@else<a href="{{ route('login', [], false) }}" class="app-btn-secondary">Sign in</a>@endauth</div></section>
        </main>
        <footer class="border-t border-gray-200 bg-white"><div class="mx-auto flex max-w-7xl flex-col gap-3 px-6 py-6 text-sm text-gray-500 sm:flex-row sm:items-center sm:justify-between lg:px-8"><span>© {{ date('Y') }} {{ tenant('name') ?? config('app.name', 'Classroom') }}.</span><span>Learning, made more focused.</span></div></footer>
    </div>
</body>
</html>
