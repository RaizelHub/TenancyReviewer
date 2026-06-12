<x-tenant-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                <div class="p-2 rounded-full bg-gradient-to-br from-purple-500 to-indigo-600 text-white mr-3 shadow-md">
                    <i class="fas fa-crown text-lg"></i>
                </div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('My Subscription Plan') }}
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500 dark:border-green-400 text-green-700 dark:text-green-300 p-4 mb-6 rounded-r-xl flex items-start shadow-sm" role="alert">
                    <div class="flex-shrink-0 mr-3">
                        <i class="fas fa-check-circle text-green-500 dark:text-green-400 text-xl"></i>
                    </div>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 dark:border-red-400 text-red-700 dark:text-red-300 p-4 mb-6 rounded-r-xl flex items-start shadow-sm" role="alert">
                    <div class="flex-shrink-0 mr-3">
                        <i class="fas fa-exclamation-circle text-red-500 dark:text-red-400 text-xl"></i>
                    </div>
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <!-- Current Plan Summary -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden border border-gray-100 dark:border-gray-700 mb-8 hover:shadow-lg transition-all duration-300">
                <div class="bg-gradient-to-r
                    {{ $currentPlan == 'Pro' ? 'from-purple-600 to-indigo-600' :
                       ($currentPlan == 'Premium' ? 'from-indigo-600 to-blue-600' :
                       'from-blue-600 to-sky-600') }} text-white p-4">
                    <h3 class="text-lg font-semibold">Your Current Subscription</h3>
                </div>

                <div class="p-6">
                    <div class="flex flex-col md:flex-row md:items-center justify-between">
                        <div class="flex items-center mb-6 md:mb-0">
                            <div class="p-4 rounded-full
                                {{ $currentPlan == 'Pro' ? 'bg-purple-100 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400' :
                                   ($currentPlan == 'Premium' ? 'bg-indigo-100 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400' :
                                   'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400') }} mr-5 shadow-sm">
                                <i class="fas
                                    {{ $currentPlan == 'Pro' ? 'fa-crown' :
                                       ($currentPlan == 'Premium' ? 'fa-star' : 'fa-user') }} text-2xl"></i>
                            </div>
                            <div>
                                <h4 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $currentPlan }} Plan</h4>
                                <p class="text-lg text-gray-500 dark:text-gray-400 mt-1">
                                    {{ $plans[$currentPlan]['price'] }}/{{ $plans[$currentPlan]['period'] }}
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 text-center shadow-sm border border-gray-100 dark:border-gray-700">
                                <div class="text-3xl font-bold
                                    {{ $subjectCount >= $maxSubjects && $maxSubjects !== PHP_INT_MAX ? 'text-red-600 dark:text-red-400' : 'text-gray-700 dark:text-gray-300' }}">
                                    {{ $subjectCount }}
                                    <span class="text-sm text-gray-500 dark:text-gray-400">/</span>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $maxSubjects === PHP_INT_MAX ? '∞' : $maxSubjects }}
                                    </span>
                                </div>
                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">Subjects</div>

                                @if($maxSubjects !== PHP_INT_MAX)
                                    <div class="mt-2 h-2 bg-gray-200 dark:bg-gray-600 rounded-full overflow-hidden">
                                        <div class="h-full {{ $subjectCount >= $maxSubjects ? 'bg-red-500' : 'bg-green-500' }}"
                                            style="width: {{ min(100, ($subjectCount / $maxSubjects) * 100) }}%"></div>
                                    </div>
                                @endif
                            </div>

                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 text-center shadow-sm border border-gray-100 dark:border-gray-700">
                                <div class="text-3xl font-bold
                                    {{ $studentCount >= $maxStudents && $maxStudents !== PHP_INT_MAX ? 'text-red-600 dark:text-red-400' : 'text-gray-700 dark:text-gray-300' }}">
                                    {{ $studentCount }}
                                    <span class="text-sm text-gray-500 dark:text-gray-400">/</span>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $maxStudents === PHP_INT_MAX ? '∞' : $maxStudents }}
                                    </span>
                                </div>
                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">Students</div>

                                @if($maxStudents !== PHP_INT_MAX)
                                    <div class="mt-2 h-2 bg-gray-200 dark:bg-gray-600 rounded-full overflow-hidden">
                                        <div class="h-full {{ $studentCount >= $maxStudents ? 'bg-red-500' : 'bg-green-500' }}"
                                            style="width: {{ min(100, ($studentCount / $maxStudents) * 100) }}%"></div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Available Plans -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden border border-gray-100 dark:border-gray-700">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 p-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Available Plans</h3>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach(['Basic', 'Premium', 'Pro'] as $plan)
                            <div class="border rounded-xl overflow-hidden hover:shadow-md transition-all duration-300
                                {{ $currentPlan === $plan ? 'border-2 border-' . $plans[$plan]['color'] . '-500 dark:border-' . $plans[$plan]['color'] . '-400 shadow-md' : 'border-gray-200 dark:border-gray-700' }}">
                                <div class="p-5 bg-gradient-to-r
                                    {{ $plan == 'Pro' ? 'from-purple-500 to-indigo-600' :
                                       ($plan == 'Premium' ? 'from-indigo-500 to-blue-600' :
                                       'from-blue-500 to-sky-600') }} text-white">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-lg font-bold">{{ $plan }}</h4>
                                        <div class="p-2 rounded-full bg-white/20 text-white">
                                            <i class="fas {{ $plans[$plan]['icon'] }}"></i>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <span class="text-2xl font-bold">{{ $plans[$plan]['price'] }}</span>
                                        <span class="text-white/80">/{{ $plans[$plan]['period'] }}</span>
                                    </div>
                                </div>

                                <div class="p-5">
                                    <ul class="space-y-3">
                                        <li class="flex items-center text-gray-700 dark:text-gray-300">
                                            <div class="w-6 h-6 rounded-full flex items-center justify-center bg-{{ $plans[$plan]['color'] }}-100 dark:bg-{{ $plans[$plan]['color'] }}-900/30 text-{{ $plans[$plan]['color'] }}-600 dark:text-{{ $plans[$plan]['color'] }}-400 mr-3">
                                                <i class="fas fa-book text-xs"></i>
                                            </div>
                                            <span>{{ $plans[$plan]['max_subjects'] }} Subjects</span>
                                        </li>
                                        <li class="flex items-center text-gray-700 dark:text-gray-300">
                                            <div class="w-6 h-6 rounded-full flex items-center justify-center bg-{{ $plans[$plan]['color'] }}-100 dark:bg-{{ $plans[$plan]['color'] }}-900/30 text-{{ $plans[$plan]['color'] }}-600 dark:text-{{ $plans[$plan]['color'] }}-400 mr-3">
                                                <i class="fas fa-user-graduate text-xs"></i>
                                            </div>
                                            <span>{{ $plans[$plan]['max_students'] }} Students</span>
                                        </li>
                                        @foreach($plans[$plan]['features'] as $feature)
                                            <li class="flex items-center text-gray-700 dark:text-gray-300">
                                                <div class="w-6 h-6 rounded-full flex items-center justify-center bg-{{ $plans[$plan]['color'] }}-100 dark:bg-{{ $plans[$plan]['color'] }}-900/30 text-{{ $plans[$plan]['color'] }}-600 dark:text-{{ $plans[$plan]['color'] }}-400 mr-3">
                                                    <i class="fas fa-check text-xs"></i>
                                                </div>
                                                <span>{{ $feature }}</span>
                                            </li>
                                        @endforeach
                                    </ul>

                                    <div class="mt-6">
                                        @if($currentPlan === $plan)
                                            <button disabled class="w-full inline-flex items-center justify-center px-4 py-3 bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-300 rounded-lg cursor-not-allowed">
                                                <i class="fas fa-check-circle mr-2"></i>
                                                Current Plan
                                            </button>
                                        @else
                                            <form action="{{ route('subscription.change-plan') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="plan" value="{{ $plan }}">
                                                <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-3
                                                    {{ $currentPlan < $plan ?
                                                        'bg-gradient-to-r from-' . $plans[$plan]['color'] . '-500 to-' . $plans[$plan]['color'] . '-600' :
                                                        'bg-gray-600' }}
                                                    text-white rounded-lg hover:shadow-md transition-all duration-300">
                                                    <i class="fas {{ $currentPlan < $plan ? 'fa-arrow-up' : 'fa-arrow-down' }} mr-2"></i>
                                                    {{ $currentPlan < $plan ? 'Upgrade' : 'Downgrade' }} to {{ $plan }}
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-tenant-app-layout>
