@php
    use Illuminate\Support\Facades\Auth;
@endphp
<nav x-data="{ notificationsOpen: false, profileOpen: false }" class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 shadow-sm w-full">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <!-- Left side: Logo/Title -->
            <div class="flex items-center">
                <div class="flex-shrink-0 flex items-center">
                    <div class="h-8 w-8 rounded-full bg-indigo-600 flex items-center justify-center shadow-sm">
                        <span class="text-white font-bold">S</span>
                    </div>
                </div>
            </div>

            <!-- Right side: Notifications, My Plan, Settings, Logout -->
            <div class="flex items-center space-x-4">
                <!-- Notifications -->
                <div class="relative">
                    <button @click="notificationsOpen = !notificationsOpen" class="p-1 rounded-full text-gray-500 hover:text-gray-600 dark:text-gray-400 dark:hover:text-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                        <span class="sr-only">View notifications</span>
                        <div class="relative">
                            <i class="fas fa-bell text-xl"></i>
                            @php
                                $notificationCount = 0;
                                $student = Auth::guard('student')->user();
                                if ($student) {
                                    // Count announcements
                                    foreach($student->subjects as $subject) {
                                        $notificationCount += $subject->activities->where('type', 'announcement')->count();
                                    }

                                    // Count pending assignments
                                    foreach($student->subjects as $subject) {
                                        $notificationCount += $subject->activities->where('type', 'assignment')->count();
                                    }
                                }
                            @endphp
                            @if($notificationCount > 0)
                                <span class="absolute -top-1 -right-1 inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-500 rounded-full">
                                    {{ $notificationCount > 9 ? '9+' : $notificationCount }}
                                </span>
                            @endif
                        </div>
                    </button>

                    <!-- Notifications dropdown -->
                    <div x-show="notificationsOpen"
                         @click.away="notificationsOpen = false"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="origin-top-right absolute right-0 mt-2 w-80 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 divide-y divide-gray-200 dark:divide-gray-700 focus:outline-none z-50">
                        <div class="px-4 py-3">
                            <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">Notifications</h3>
                        </div>
                        <div class="max-h-60 overflow-y-auto">
                            @if($notificationCount > 0)
                                @php
                                    $announcements = collect();
                                    $assignments = collect();

                                    if ($student) {
                                        foreach($student->subjects as $subject) {
                                            $subjectAnnouncements = $subject->activities->where('type', 'announcement');
                                            foreach($subjectAnnouncements as $announcement) {
                                                $announcements->push([
                                                    'title' => "New announcement in {$subject->name}",
                                                    'time' => $announcement->created_at->diffForHumans(),
                                                    'type' => 'announcement',
                                                    'icon' => 'bullhorn',
                                                    'color' => 'amber'
                                                ]);
                                            }

                                            $subjectAssignments = $subject->activities->where('type', 'assignment');
                                            foreach($subjectAssignments as $assignment) {
                                                $assignments->push([
                                                    'title' => "Assignment due in {$subject->name}",
                                                    'time' => $assignment->due_date ? "Due " . \Carbon\Carbon::parse($assignment->due_date)->diffForHumans() : 'No due date',
                                                    'type' => 'assignment',
                                                    'icon' => 'tasks',
                                                    'color' => 'green'
                                                ]);
                                            }
                                        }
                                    }

                                    $allNotifications = $announcements->merge($assignments)->sortByDesc('time')->take(10);
                                @endphp

                                @foreach($allNotifications as $notification)
                                    <a href="#" class="block px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0">
                                                <div class="h-8 w-8 rounded-full bg-{{ $notification['color'] }}-100 dark:bg-{{ $notification['color'] }}-900/30 flex items-center justify-center text-{{ $notification['color'] }}-600 dark:text-{{ $notification['color'] }}-400">
                                                    <i class="fas fa-{{ $notification['icon'] }}"></i>
                                                </div>
                                            </div>
                                            <div class="ml-3 w-0 flex-1">
                                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $notification['title'] }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $notification['time'] }}</p>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            @else
                                <div class="px-4 py-6 text-center">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">No new notifications</p>
                                </div>
                            @endif
                        </div>
                        <div class="px-4 py-2">
                            <a href="#" class="text-xs text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-medium">View all notifications</a>
                        </div>
                    </div>
                </div>

                <!-- My Plan Button -->
                <a href="{{ route('student.plan') }}" class="inline-flex items-center px-3 py-1.5 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors duration-200">
                    <i class="fas fa-crown mr-1.5 text-amber-500"></i>
                    <span>My Plan</span>
                    @if(Auth::guard('student')->user()->plan === 'premium')
                        <span class="ml-1.5 inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300">
                            Premium
                        </span>
                    @else
                        <span class="ml-1.5 inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                            Basic
                        </span>
                    @endif
                </a>

                <!-- Settings Button -->
                

                <!-- Logout Button -->

                <!-- User Profile -->
                <div class="relative ml-2">
                    <button @click="profileOpen = !profileOpen" class="flex items-center focus:outline-none">
                        <div class="h-8 w-8 rounded-full bg-gradient-to-r from-indigo-500 to-purple-500 flex items-center justify-center shadow-sm overflow-hidden">                                                                                                                            
                            @php
                                $profilePhoto = Auth::guard('student')->user()->profile_photo ?? null;
                            @endphp
                            @if($profilePhoto)
                                <img src="{{ $profilePhoto }}" alt="Avatar" class="object-cover h-full w-full">
                            @else
                                <span class="text-sm font-bold text-white">{{ strtoupper(substr(Auth::guard('student')->user()->name ?? 'S', 0, 1)) }}</span>
                            @endif
                        </div>
                    </button>

                    <!-- Profile dropdown -->
                    <div x-show="profileOpen"
                         @click.away="profileOpen = false"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
                        <div class="py-1">
                            <div class="px-4 py-2 border-b border-gray-200 dark:border-gray-700">
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ Auth::guard('student')->user()->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ Auth::guard('student')->user()->email }}</p>
                            </div>
                            <a href="{{ route('student.settings.edit') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Profile Settings</a>
                            <a href="{{ route('student.plan') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Subscription Plan</a>
                            <form method="POST" action="{{ route('logout') }}" class="block w-full text-left">
                                @csrf
                                <button type="submit" class="block w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 text-left">Sign out</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
