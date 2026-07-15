@php
    $isCentral = auth()->check() && !tenant();
    $pendingCount = $isCentral ? \App\Models\TenantApplication::where('status', 'pending')->count() : 0;
@endphp
<div x-data="{
    sidebarOpen: window.innerWidth >= 1024,
    profileOpen: false,
    toggleSidebar() { this.sidebarOpen = !this.sidebarOpen; }
}" class="central-shell min-h-screen bg-slate-50">
    <div class="flex min-h-screen">
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-slate-950/40 lg:hidden" x-transition.opacity></div>

        <aside class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col bg-emerald-950 text-emerald-50 transition-transform duration-200 lg:static lg:translate-x-0" :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            <div class="flex h-20 items-center border-b border-white/10 px-5">
                <a href="{{ route('dashboard') }}" class="flex min-w-0 items-center gap-3">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-500 text-lg font-bold text-white shadow-sm">C</span>
                    <span class="min-w-0">
                        <span class="block truncate text-base font-semibold text-white">Central</span>
                        <span class="block text-xs text-emerald-200">Super Admin Console</span>
                    </span>
                </a>
                <button @click="toggleSidebar" class="ml-auto rounded-lg p-2 text-emerald-100 hover:bg-white/10 lg:hidden" aria-label="Close navigation">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-6" aria-label="Primary navigation">
                <p class="px-3 pb-2 text-[11px] font-semibold uppercase tracking-[0.12em] text-emerald-300">Workspace</p>
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition-colors {{ request()->routeIs('dashboard') ? 'bg-emerald-500 text-white shadow-sm' : 'text-emerald-100 hover:bg-white/10 hover:text-white' }}">
                    <i class="fas fa-grid-2 w-5 text-center"></i><span>Overview</span>
                </a>

                @if($isCentral)
                    <a href="{{ route('tenants.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition-colors {{ request()->routeIs('tenants.*') ? 'bg-emerald-500 text-white shadow-sm' : 'text-emerald-100 hover:bg-white/10 hover:text-white' }}">
                        <i class="fas fa-building w-5 text-center"></i><span>Tenant management</span>
                    </a>
                    <a href="{{ route('applications.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition-colors {{ request()->routeIs('applications.*') ? 'bg-emerald-500 text-white shadow-sm' : 'text-emerald-100 hover:bg-white/10 hover:text-white' }}">
                        <i class="fas fa-file-circle-check w-5 text-center"></i><span>Applications</span>
                        @if($pendingCount > 0)<span class="ml-auto rounded-full bg-white/20 px-2 py-0.5 text-xs font-semibold text-white">{{ $pendingCount }}</span>@endif
                    </a>
                    <a href="{{ route('logs.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition-colors {{ request()->routeIs('logs.*') ? 'bg-emerald-500 text-white shadow-sm' : 'text-emerald-100 hover:bg-white/10 hover:text-white' }}">
                        <i class="fas fa-history w-5 text-center"></i><span>Audit trail</span>
                    </a>
                    <a href="{{ route('settings.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition-colors {{ request()->routeIs('settings.*') ? 'bg-emerald-500 text-white shadow-sm' : 'text-emerald-100 hover:bg-white/10 hover:text-white' }}">
                        <i class="fas fa-cogs w-5 text-center"></i><span>Settings</span>
                    </a>
                @endif

                <p class="mt-8 px-3 pb-2 text-[11px] font-semibold uppercase tracking-[0.12em] text-emerald-300">Account</p>
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition-colors {{ request()->routeIs('profile.*') ? 'bg-emerald-500 text-white shadow-sm' : 'text-emerald-100 hover:bg-white/10 hover:text-white' }}">
                    <i class="fas fa-user-gear w-5 text-center"></i><span>Profile settings</span>
                </a>
            </nav>

            <div class="border-t border-white/10 p-3">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex w-full items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium text-emerald-100 transition-colors hover:bg-white/10 hover:text-white">
                        <i class="fas fa-arrow-right-from-bracket w-5 text-center"></i><span>Sign out</span>
                    </button>
                </form>
            </div>
        </aside>

        <main class="min-w-0 flex-1">
            <header class="sticky top-0 z-30 flex h-20 items-center justify-between border-b border-gray-200 bg-white/95 px-4 backdrop-blur sm:px-6 lg:px-8">
                <div class="flex items-center gap-3">
                    <button @click="toggleSidebar" class="rounded-xl border border-gray-200 p-2.5 text-gray-600 hover:bg-gray-50 lg:hidden" aria-label="Open navigation"><i class="fas fa-bars"></i></button>
                    <div>
                        <p class="text-xs font-medium text-gray-500">Super Admin</p>
                        <h1 class="text-lg font-semibold text-gray-900">{{ request()->routeIs('dashboard') ? 'Overview' : (request()->routeIs('tenants.*') ? 'Tenant Management' : (request()->routeIs('applications.*') ? 'Applications' : 'Central')) }}</h1>
                    </div>
                </div>
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center gap-3 rounded-xl p-1.5 pr-3 transition-colors hover:bg-gray-50" aria-label="Open profile menu">
                        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-100 text-sm font-bold text-emerald-700">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                        <span class="hidden text-left sm:block"><span class="block text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</span><span class="block text-xs text-gray-500">Administrator</span></span>
                        <i class="fas fa-chevron-down text-xs text-gray-400"></i>
                    </button>
                    <div x-show="open" @click.outside="open = false" x-transition class="absolute right-0 mt-2 w-52 overflow-hidden rounded-xl border border-gray-200 bg-white p-1 shadow-md" style="display:none">
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 rounded-lg px-3 py-2.5 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-700"><i class="fas fa-user-gear w-4"></i>Profile settings</a>
                        <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="flex w-full items-center gap-2 rounded-lg px-3 py-2.5 text-sm text-red-600 hover:bg-red-50"><i class="fas fa-arrow-right-from-bracket w-4"></i>Sign out</button></form>
                    </div>
                </div>
            </header>
            <div class="px-4 py-6 sm:px-6 lg:px-8">
                @isset($header)<div class="mb-6">{{ $header }}</div>@endisset
                {{ $slot }}
            </div>
        </main>
    </div>
</div>