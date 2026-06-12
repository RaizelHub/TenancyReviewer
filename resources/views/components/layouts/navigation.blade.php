<div x-data="{
    sidebarOpen: localStorage.getItem('sidebarOpen') === 'true' || window.innerWidth >= 1024,
    darkMode: localStorage.getItem('darkMode') === 'true' || window.matchMedia('(prefers-color-scheme: dark)').matches,
    notificationsOpen: false,
    notifications: [
        { id: 1, title: 'New application received', time: '5 minutes ago', read: false },
        { id: 2, title: 'Tenant domain activated', time: '1 hour ago', read: false },
        { id: 3, title: 'System update completed', time: '2 days ago', read: true }
    ],
    toggleSidebar() {
        this.sidebarOpen = !this.sidebarOpen;
        localStorage.setItem('sidebarOpen', this.sidebarOpen);
    },
    toggleDarkMode() {
        this.darkMode = !this.darkMode;
        localStorage.setItem('darkMode', this.darkMode);
        if (this.darkMode) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    },
    initDarkMode() {
        if (this.darkMode) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    }
}" x-init="initDarkMode()" class="min-h-screen flex flex-col">
    <!-- Sidebar -->
    <div class="flex flex-1 overflow-hidden">
        <aside :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}"
            class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-gray-900 shadow-xl transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-auto lg:w-64 flex flex-col border-r border-gray-100 dark:border-gray-800">
            <!-- Logo and Toggle -->
            <div class="flex items-center justify-between h-16 px-4 border-b border-gray-100 dark:border-gray-800 bg-gradient-to-r from-indigo-600 to-purple-600 dark:from-indigo-800 dark:to-purple-800">
                <a href="{{ route('dashboard') }}" class="flex items-center">
                    <div class="h-10 w-10 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center text-white font-bold text-xl shadow-lg">
                        C
                    </div>
                    <span class="ml-3 text-xl font-bold text-white">Central</span>
                </a>
                <button @click="toggleSidebar" class="lg:hidden text-white hover:text-gray-200 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                <div class="mb-2">
                    <h3 class="px-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Main Menu
                    </h3>
                </div>

                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-indigo-500 to-purple-500 text-white shadow-md' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-indigo-600 dark:hover:text-indigo-400' }} rounded-lg transition-all duration-200 group">
                    <div class="flex items-center justify-center w-8 h-8 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-white/20 text-white' : 'bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 group-hover:bg-indigo-200 dark:group-hover:bg-indigo-800/40' }}">
                        <i class="fas fa-home"></i>
                    </div>
                    <span class="ml-3 font-medium">Dashboard</span>
                </a>

                <!-- Tenant Management Link -->
                @if(auth()->check() && !tenant())
                <a href="{{ route('tenants.index') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('tenants.*') ? 'bg-gradient-to-r from-indigo-500 to-purple-500 text-white shadow-md' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-blue-600 dark:hover:text-blue-400' }} rounded-lg transition-all duration-200 group">
                    <div class="flex items-center justify-center w-8 h-8 rounded-lg {{ request()->routeIs('tenants.*') ? 'bg-white/20 text-white' : 'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 group-hover:bg-blue-200 dark:group-hover:bg-blue-800/40' }}">
                        <i class="fas fa-building"></i>
                    </div>
                    <span class="ml-3 font-medium">Tenant Management</span>
                </a>
                @endif

                @if(auth()->check() && !tenant())
                <a href="{{ route('applications.index') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('applications.*') ? 'bg-gradient-to-r from-indigo-500 to-purple-500 text-white shadow-md' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-purple-600 dark:hover:text-purple-400' }} rounded-lg transition-all duration-200 group">
                    <div class="flex items-center justify-center w-8 h-8 rounded-lg {{ request()->routeIs('applications.*') ? 'bg-white/20 text-white' : 'bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 group-hover:bg-purple-200 dark:group-hover:bg-purple-800/40' }}">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <span class="ml-3 font-medium">Applications</span>
                    @php
                        $pendingCount = \App\Models\TenantApplication::where('status', 'pending')->count();
                    @endphp
                    @if($pendingCount > 0)
                        <span class="ml-auto inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300">
                            {{ $pendingCount }}
                        </span>
                    @endif
                </a>
                @endif

                <div class="pt-6 mt-6 border-t border-gray-200 dark:border-gray-800">
                    <h3 class="px-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Settings
                    </h3>
                </div>

                <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('profile.*') ? 'bg-gradient-to-r from-indigo-500 to-purple-500 text-white shadow-md' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-green-600 dark:hover:text-green-400' }} rounded-lg transition-all duration-200 group">
                    <div class="flex items-center justify-center w-8 h-8 rounded-lg {{ request()->routeIs('profile.*') ? 'bg-white/20 text-white' : 'bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 group-hover:bg-green-200 dark:group-hover:bg-green-800/40' }}">
                        <i class="fas fa-user-cog"></i>
                    </div>
                    <span class="ml-3 font-medium">Profile Settings</span>
                </a>

                <form method="POST" action="{{ route('logout') }}" class="mt-auto">
                    @csrf
                    <button type="submit" class="w-full flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-red-100 dark:hover:bg-red-900/20 hover:text-red-600 dark:hover:text-red-400 rounded-lg transition-all duration-200 group">
                        <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 group-hover:bg-red-200 dark:group-hover:bg-red-800/40">
                            <i class="fas fa-sign-out-alt"></i>
                        </div>
                        <span class="ml-3 font-medium">Log Out</span>
                    </button>
                </form>
            </nav>

            <!-- User Profile -->
            <div class="p-4 border-t border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-900/50">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-10 w-10 rounded-full bg-gradient-to-r from-indigo-500 to-purple-500 flex items-center justify-center text-white font-bold text-lg shadow-md">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ Auth::user()->email }}</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 dark:bg-gray-900">
            <!-- Top Navigation -->
            <div class="bg-white dark:bg-gray-800 shadow-md sticky top-0 z-10">
                <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                    <!-- Left: Hamburger and Page Title -->
                    <div class="flex items-center">
                        <button @click="toggleSidebar" class="text-gray-500 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400 focus:outline-none lg:hidden">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <div class="ml-4">
                            <span class="text-lg font-semibold text-gray-800 dark:text-gray-200">{{ request()->routeIs('dashboard') ? 'Dashboard' : (request()->routeIs('tenants.*') ? 'Tenant Management' : (request()->routeIs('applications.*') ? 'Applications' : 'Central')) }}</span>
                        </div>
                    </div>

                    <!-- Right: Actions -->
                    <div class="flex items-center space-x-4">
                        <!-- Search -->
                        <div class="relative hidden md:block">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text" class="block w-full pl-10 pr-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg leading-5 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition duration-150 ease-in-out" placeholder="Search...">
                        </div>

                        <!-- Notifications -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="flex items-center p-1.5 rounded-lg text-gray-500 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400 focus:outline-none hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                <span class="relative">
                                    <i class="fas fa-bell text-xl"></i>
                                    <span class="absolute top-0 right-0 h-2 w-2 rounded-full bg-red-500 ring-2 ring-white dark:ring-gray-800"></span>
                                </span>
                            </button>

                            <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="origin-top-right absolute right-0 mt-2 w-80 rounded-lg shadow-lg">
                                <div class="rounded-lg bg-white dark:bg-gray-800 shadow-xl overflow-hidden border border-gray-100 dark:border-gray-700">
                                    <div class="p-3 border-b border-gray-100 dark:border-gray-700">
                                        <div class="flex items-center justify-between">
                                            <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Notifications</h3>
                                            <button class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline">Mark all as read</button>
                                        </div>
                                    </div>
                                    <div class="divide-y divide-gray-100 dark:divide-gray-700 max-h-64 overflow-y-auto">
                                        <template x-for="notification in notifications" :key="notification.id">
                                            <a href="#" class="block px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150 ease-in-out">
                                                <div class="flex items-start">
                                                    <div class="flex-shrink-0">
                                                        <div :class="notification.read ? 'bg-gray-100 dark:bg-gray-700' : 'bg-indigo-100 dark:bg-indigo-900/30'" class="h-8 w-8 rounded-full flex items-center justify-center">
                                                            <i class="fas fa-bell" :class="notification.read ? 'text-gray-500 dark:text-gray-400' : 'text-indigo-600 dark:text-indigo-400'"></i>
                                                        </div>
                                                    </div>
                                                    <div class="ml-3 w-0 flex-1">
                                                        <p x-text="notification.title" :class="notification.read ? 'text-gray-500 dark:text-gray-400' : 'text-gray-900 dark:text-gray-100 font-medium'"></p>
                                                        <p x-text="notification.time" class="mt-1 text-xs text-gray-500 dark:text-gray-400"></p>
                                                    </div>
                                                    <div x-show="!notification.read" class="ml-2 flex-shrink-0">
                                                        <span class="inline-block h-2 w-2 rounded-full bg-indigo-600 dark:bg-indigo-400"></span>
                                                    </div>
                                                </div>
                                            </a>
                                        </template>
                                    </div>
                                    <div class="p-3 border-t border-gray-100 dark:border-gray-700 text-center">
                                        <a href="#" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">View all notifications</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Dark Mode Toggle -->
                        <button @click="toggleDarkMode" class="p-1.5 rounded-lg text-gray-500 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400 focus:outline-none hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <i x-show="!darkMode" class="fas fa-moon text-xl"></i>
                            <i x-show="darkMode" class="fas fa-sun text-xl"></i>
                        </button>

                        <!-- Profile Menu -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="flex items-center p-1 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                <div class="h-8 w-8 rounded-full bg-gradient-to-r from-indigo-500 to-purple-500 flex items-center justify-center text-white font-bold shadow-md">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                            </button>

                            <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="origin-top-right absolute right-0 mt-2 w-48 rounded-lg shadow-lg">
                                <div class="py-1 rounded-lg bg-white dark:bg-gray-800 shadow-xl border border-gray-100 dark:border-gray-700">
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <i class="fas fa-user-cog mr-2"></i> Profile Settings
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                            <i class="fas fa-sign-out-alt mr-2"></i> Log Out
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <div class="py-6">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    {{ $slot }}
                </div>
            </div>
        </main>
    </div>
</div>
