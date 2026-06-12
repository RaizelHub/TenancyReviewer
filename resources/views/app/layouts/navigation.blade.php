@php
    use Illuminate\Support\Facades\Auth;
@endphp
@if(Auth::guard('student')->check())
    <nav x-data="{ open: false, notificationsOpen: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 shadow-sm fixed top-0 left-0 right-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <!-- Left: Mobile menu button and Logo -->
                <div class="flex items-center">
                    <!-- Mobile menu button -->
                    <button type="button" class="md:hidden inline-flex items-center justify-center p-2 rounded-lg text-gray-500 hover:text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:bg-gray-700 focus:outline-none" aria-controls="mobile-menu" @click="$dispatch('toggle-sidebar')">
                        <i class="fas fa-bars"></i>
                    </button>

                    <!-- Page Title (shows current page) -->
                    <div class="ml-4 text-lg font-medium text-gray-800 dark:text-white">
                        @if(request()->routeIs('student.dashboard'))
                            Dashboard
                        @elseif(request()->routeIs('student.subjects'))
                            My Subjects
                        @elseif(request()->routeIs('student.assignments'))
                            Assignments
                        @elseif(request()->routeIs('student.materials'))
                            Learning Materials
                        @elseif(request()->routeIs('student.announcements'))
                            Announcements
                        @else
                            Student Portal
                        @endif
                    </div>
                </div>

                <!-- Right side navigation items -->
                <div class="flex items-center space-x-3">
                    <!-- Notifications -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" type="button" class="bg-white dark:bg-gray-800 p-1.5 rounded-lg text-gray-500 dark:text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 focus:outline-none border border-gray-100 dark:border-gray-700">
                            <span class="sr-only">View notifications</span>
                            <i class="fas fa-bell"></i>
                            <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white dark:ring-gray-800"></span>
                        </button>

                        <!-- Notifications dropdown -->
                        <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="origin-top-right absolute right-0 mt-2 w-80 rounded-lg shadow-lg py-1 bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 focus:outline-none z-50 border border-gray-100 dark:border-gray-700" style="display: none;">
                            <div class="px-4 py-2 border-b border-gray-100 dark:border-gray-700">
                                <h3 class="text-sm font-medium text-gray-900 dark:text-white">Notifications</h3>
                            </div>
                            <div class="max-h-60 overflow-y-auto">
                                <a href="#" class="block px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 border-b border-gray-100 dark:border-gray-700">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <div class="h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-900/20 flex items-center justify-center">
                                                <i class="fas fa-book-open text-blue-600 dark:text-blue-400"></i>
                                            </div>
                                        </div>
                                        <div class="ml-3 flex-1">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">New material available</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Chapter 5 notes added to Mathematics</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">2 hours ago</p>
                                        </div>
                                    </div>
                                </a>
                                <a href="#" class="block px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <div class="h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-900/20 flex items-center justify-center">
                                                <i class="fas fa-bullhorn text-blue-600 dark:text-blue-400"></i>
                                            </div>
                                        </div>
                                        <div class="ml-3 flex-1">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">New announcement</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Class schedule change for next week</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Yesterday</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="px-4 py-2 border-t border-gray-100 dark:border-gray-700 text-center">
                                <a href="#" class="text-xs font-medium text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300">View all notifications</a>
                            </div>
                        </div>
                    </div>

                    <!-- Theme Toggle -->
                    <button type="button" class="bg-white dark:bg-gray-800 p-1.5 rounded-lg text-gray-500 dark:text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 focus:outline-none border border-gray-100 dark:border-gray-700" x-data @click="$dispatch('toggle-theme')">
                        <span class="sr-only">Toggle theme</span>
                        <i x-show="!document.documentElement.classList.contains('dark')" class="fas fa-moon"></i>
                        <i x-show="document.documentElement.classList.contains('dark')" class="fas fa-sun" style="display: none;"></i>
                    </button>

                    <!-- Profile dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" type="button" class="bg-white dark:bg-gray-800 rounded-lg flex text-sm focus:outline-none border border-gray-100 dark:border-gray-700 p-1">
                            @php
                                $profilePhoto = Auth::guard('student')->user()->profile_photo ?? null;
                            @endphp
                            <div class="h-8 w-8 rounded-full bg-blue-100 dark:bg-blue-900/20 flex items-center justify-center text-blue-600 dark:text-blue-400 overflow-hidden">
                                @if($profilePhoto)
                                    <img src="{{ $profilePhoto }}" alt="Avatar" class="object-cover h-full w-full" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode(Auth::guard('student')->user()->name) }}&color=7F9CF5&background=EBF4FF';">
                                @else
                                    {{ strtoupper(substr(Auth::guard('student')->user()->name, 0, 1)) }}
                                @endif
                            </div>
                        </button>

                        <!-- Profile dropdown menu -->
                        <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="origin-top-right absolute right-0 mt-2 w-48 rounded-lg shadow-lg py-1 bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 focus:outline-none z-50 border border-gray-100 dark:border-gray-700" style="display: none;">
                            <div class="px-4 py-2 border-b border-gray-100 dark:border-gray-700">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ Auth::guard('student')->user()->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Student</p>
                            </div>
                            <a href="{{ route('student.settings.edit') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md mx-1">
                                <i class="fas fa-user-cog mr-2 text-blue-500 dark:text-blue-400"></i> Settings
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md mx-1">
                                    <i class="fas fa-sign-out-alt mr-2 text-red-500 dark:text-red-400"></i> Sign out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
@else
<nav x-data="{ open: false, notificationsOpen: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 shadow-sm fixed top-0 left-0 right-0 z-50">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Mobile menu button -->
                <button type="button" class="md:hidden inline-flex items-center justify-center p-2 rounded-lg text-gray-500 hover:text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:bg-gray-700 focus:outline-none" aria-controls="mobile-menu" @click="$dispatch('toggle-sidebar')">
                    <i class="fas fa-bars"></i>
                </button>

                <!-- Page Title -->
                
            </div>

            <div class="flex items-center">
                <!-- Create New Button -->
                <div class="ml-3 relative" x-data="{ open: false }">
                    <div>
                        <button @click="open = !open" type="button" class="bg-blue-600 dark:bg-blue-500 p-1.5 rounded-lg text-white hover:bg-blue-700 dark:hover:bg-blue-600 focus:outline-none shadow-sm">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>

                    <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="origin-top-right absolute right-0 mt-2 w-48 rounded-lg shadow-lg py-1 bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 focus:outline-none border border-gray-100 dark:border-gray-700" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1" style="display: none;">
                        <a href="{{ route('subjects.create') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md mx-1" role="menuitem">
                            <i class="fas fa-book mr-2 text-blue-500 dark:text-blue-400"></i> New Subject
                        </a>
                        <a href="{{ route('students.create') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md mx-1" role="menuitem">
                            <i class="fas fa-user-graduate mr-2 text-blue-500 dark:text-blue-400"></i> New Student
                        </a>
                        <a href="{{ route('activities.create') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md mx-1" role="menuitem">
                            <i class="fas fa-tasks mr-2 text-blue-500 dark:text-blue-400"></i> New Activity
                        </a>
                    </div>
                </div>

                <!-- Notifications -->
                <div class="ml-3 relative" x-data="{ open: false }">
                    <div>
                        <button @click="open = !open" type="button" class="bg-white dark:bg-gray-800 p-1.5 rounded-lg text-gray-500 dark:text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 focus:outline-none border border-gray-100 dark:border-gray-700">
                            <span class="sr-only">View notifications</span>
                            <i class="fas fa-bell"></i>
                            <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white dark:ring-gray-800"></span>
                        </button>
                    </div>

                    <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="origin-top-right absolute right-0 mt-2 w-80 rounded-lg shadow-lg py-1 bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 focus:outline-none border border-gray-100 dark:border-gray-700" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1" style="display: none;">
                        <div class="px-4 py-2 border-b border-gray-100 dark:border-gray-700">
                            <h3 class="text-sm font-medium text-gray-900 dark:text-white">Notifications</h3>
                        </div>
                        <div class="max-h-60 overflow-y-auto">
                            <a href="#" class="block px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 border-b border-gray-100 dark:border-gray-700">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <div class="h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-900/20 flex items-center justify-center">
                                            <i class="fas fa-user-graduate text-blue-600 dark:text-blue-400"></i>
                                        </div>
                                    </div>
                                    
                                </div>
                            </a>
                            <a href="#" class="block px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <div class="h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-900/20 flex items-center justify-center">
                                            <i class="fas fa-tasks text-blue-600 dark:text-blue-400"></i>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="px-4 py-2 border-t border-gray-100 dark:border-gray-700 text-center">
                        </div>
                    </div>
                </div>
               

                <!-- Profile dropdown -->
                <div class="ml-3 relative" x-data="{ open: false }">
                    <div>
                        <button @click="open = !open" type="button" class="bg-white dark:bg-gray-800 rounded-lg flex text-sm focus:outline-none border border-gray-100 dark:border-gray-700 p-1" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                            <span class="sr-only">Open user menu</span>
                            <div class="h-8 w-8 rounded-full bg-blue-100 dark:bg-blue-900/20 flex items-center justify-center text-blue-600 dark:text-blue-400">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                        </button>
                    </div>

                    <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="origin-top-right absolute right-0 mt-2 w-48 rounded-lg shadow-lg py-1 bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 focus:outline-none border border-gray-100 dark:border-gray-700" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1" style="display: none;">
                        <div class="px-4 py-2 border-b border-gray-100 dark:border-gray-700">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</p>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md mx-1" role="menuitem">
                            <i class="fas fa-user mr-2 text-blue-500 dark:text-blue-400"></i> Your Profile
                        </a>
                        <a href="{{ route('tenant.settings.edit') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md mx-1" role="menuitem">
                            <i class="fas fa-cog mr-2 text-blue-500 dark:text-blue-400"></i> Settings
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md mx-1" role="menuitem">
                                <i class="fas fa-sign-out-alt mr-2 text-red-500 dark:text-red-400"></i> Sign out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>


@endif
