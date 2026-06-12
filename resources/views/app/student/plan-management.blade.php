<x-tenant-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                <div class="p-2 rounded-lg bg-purple-100 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400 mr-3">
                    <i class="fas fa-crown"></i>
                </div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Manage Your Plan') }}
                </h2>
            </div>
            <div class="flex items-center">
                @if($student->plan === 'premium')
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-gradient-to-r from-purple-600 to-indigo-600 text-white shadow-sm">
                        <i class="fas fa-crown text-amber-300 mr-1.5"></i> Premium Plan
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                        <i class="fas fa-user mr-1.5"></i> Basic Plan
                    </span>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow-sm" role="alert">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
            @endif

            @if(session('info'))
            <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6 rounded-lg shadow-sm" role="alert">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm">{{ session('info') }}</p>
                    </div>
                </div>
            </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md rounded-xl border border-gray-100 dark:border-gray-700">
                <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gradient-to-r from-gray-50 to-white dark:from-gray-800 dark:to-gray-750">
                    <div class="flex items-center">
                        <div class="p-2 rounded-lg bg-purple-100 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400 mr-3">
                            <i class="fas fa-cog"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Plan Settings</h3>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Current Plan -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                <i class="fas fa-id-card text-blue-500 mr-2"></i> Your Current Plan
                            </h3>

                            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 mb-6 border-2 {{ $student->plan === 'premium' ? 'border-purple-500 dark:border-purple-600' : 'border-blue-500 dark:border-blue-600' }} shadow-md relative overflow-hidden">
                                <!-- Decorative elements -->
                                @if($student->plan === 'premium')
                                    <div class="absolute top-0 right-0 w-32 h-32 -mt-10 -mr-10 bg-purple-200 dark:bg-purple-800/30 rounded-full opacity-20"></div>
                                    <div class="absolute bottom-0 left-0 w-24 h-24 -mb-8 -ml-8 bg-indigo-200 dark:bg-indigo-800/30 rounded-full opacity-20"></div>
                                @else
                                    <div class="absolute top-0 right-0 w-32 h-32 -mt-10 -mr-10 bg-blue-200 dark:bg-blue-800/30 rounded-full opacity-20"></div>
                                    <div class="absolute bottom-0 left-0 w-24 h-24 -mb-8 -ml-8 bg-indigo-200 dark:bg-indigo-800/30 rounded-full opacity-20"></div>
                                @endif

                                <div class="relative">
                                    <div class="flex justify-between items-center mb-5">
                                        <div>
                                            <h4 class="font-semibold text-xl text-gray-900 dark:text-white flex items-center">
                                                @if($student->plan === 'premium')
                                                    <i class="fas fa-crown text-amber-500 mr-2"></i>
                                                @else
                                                    <i class="fas fa-user text-blue-500 mr-2"></i>
                                                @endif
                                                {{ ucfirst($student->plan) }} Plan
                                            </h4>
                                            @if($student->plan === 'premium' && $activeSubscription)
                                                <div class="flex items-center mt-2 text-sm text-gray-500 dark:text-gray-400">
                                                    <i class="far fa-calendar-alt mr-2"></i>
                                                    <span>Valid until: <span class="font-medium">{{ Carbon\Carbon::parse($activeSubscription->end_date)->format('F d, Y') }}</span></span>
                                                </div>
                                                <div class="flex items-center mt-2 text-sm text-gray-500 dark:text-gray-400">
                                                    <i class="fas fa-credit-card mr-2"></i>
                                                    <span>Payment method:
                                                        <span class="font-medium">
                                                            @if($activeSubscription->payment_method === 'credit_card')
                                                                Credit Card
                                                            @elseif($activeSubscription->payment_method === 'paypal')
                                                                PayPal
                                                            @elseif($activeSubscription->payment_method === 'gcash')
                                                                GCash
                                                            @elseif($activeSubscription->payment_method === 'paymaya')
                                                                PayMaya
                                                            @else
                                                                {{ ucfirst($activeSubscription->payment_method) }}
                                                            @endif
                                                        </span>
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium {{ $student->plan === 'premium' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300' : 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300' }}">
                                            {{ $student->plan === 'premium' ? '₱4,999/year' : 'Free' }}
                                        </span>
                                    </div>

                                    <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-4 mb-5">
                                        <h5 class="font-medium text-gray-900 dark:text-white mb-3 flex items-center">
                                            <i class="fas fa-list-check text-green-500 mr-2"></i> Features Included
                                        </h5>
                                        <div class="space-y-3 text-sm text-gray-600 dark:text-gray-300">
                                            <div class="flex items-center">
                                                <div class="p-1 rounded-full bg-green-100 dark:bg-green-900/20 text-green-600 dark:text-green-400 mr-3">
                                                    <i class="fas fa-check text-xs"></i>
                                                </div>
                                                Access to assigned subjects
                                            </div>
                                            <div class="flex items-center">
                                                <div class="p-1 rounded-full bg-green-100 dark:bg-green-900/20 text-green-600 dark:text-green-400 mr-3">
                                                    <i class="fas fa-check text-xs"></i>
                                                </div>
                                                Submit assignments
                                            </div>
                                            <div class="flex items-center">
                                                <div class="p-1 rounded-full bg-green-100 dark:bg-green-900/20 text-green-600 dark:text-green-400 mr-3">
                                                    <i class="fas fa-check text-xs"></i>
                                                </div>
                                                View grades and feedback
                                            </div>
                                            <div class="flex items-center">
                                                <div class="p-1 rounded-full bg-green-100 dark:bg-green-900/20 text-green-600 dark:text-green-400 mr-3">
                                                    <i class="fas fa-check text-xs"></i>
                                                </div>
                                                View and download learning materials
                                            </div>
                                            <div class="flex items-center {{ $student->plan === 'premium' ? 'text-green-600 dark:text-green-400 font-medium' : 'text-gray-400 dark:text-gray-500' }}">
                                                @if($student->plan === 'premium')
                                                    <div class="p-1 rounded-full bg-green-100 dark:bg-green-900/20 text-green-600 dark:text-green-400 mr-3">
                                                        <i class="fas fa-check text-xs"></i>
                                                    </div>
                                                @else
                                                    <div class="p-1 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500 mr-3">
                                                        <i class="fas fa-times text-xs"></i>
                                                    </div>
                                                @endif
                                                Access to interactive quizzes
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-6">
                                        @if($student->plan === 'basic')
                                            <a href="{{ route('student.upgrade') }}" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-lg hover:from-purple-700 hover:to-indigo-700 transition-all duration-200 shadow-sm">
                                                <i class="fas fa-arrow-circle-up mr-2"></i>
                                                Upgrade to Premium
                                            </a>
                                        @else
                                            <form method="POST" action="{{ route('student.downgrade') }}" class="inline">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-all duration-200 shadow-sm" onclick="return confirm('Are you sure you want to downgrade to the Basic plan? You will lose access to premium features.')">
                                                    <i class="fas fa-arrow-circle-down mr-2"></i>
                                                    Downgrade to Basic
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Plan Comparison -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                <i class="fas fa-exchange-alt text-purple-500 mr-2"></i> Plan Comparison
                            </h3>

                            <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                                <div class="overflow-x-auto">
                                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                        <thead class="text-xs font-medium uppercase bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 rounded-tl-lg">Feature</th>
                                                <th scope="col" class="px-6 py-3 bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300">Basic</th>
                                                <th scope="col" class="px-6 py-3 bg-purple-50 dark:bg-purple-900/20 text-purple-700 dark:text-purple-300 rounded-tr-lg">Premium</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="bg-white dark:bg-gray-800 border-b dark:border-gray-700">
                                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                                    Price
                                                </th>
                                                <td class="px-6 py-4 bg-blue-50/50 dark:bg-blue-900/10 font-medium text-blue-700 dark:text-blue-300">
                                                    Free
                                                </td>
                                                <td class="px-6 py-4 bg-purple-50/50 dark:bg-purple-900/10 font-medium text-purple-700 dark:text-purple-300">
                                                    ₱4,999/year
                                                </td>
                                            </tr>
                                            <tr class="bg-white dark:bg-gray-800 border-b dark:border-gray-700">
                                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                                    Access to subjects
                                                </th>
                                                <td class="px-6 py-4 bg-blue-50/50 dark:bg-blue-900/10">
                                                    <div class="p-1 rounded-full bg-green-100 dark:bg-green-900/20 text-green-600 dark:text-green-400 w-7 h-7 flex items-center justify-center">
                                                        <i class="fas fa-check text-xs"></i>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 bg-purple-50/50 dark:bg-purple-900/10">
                                                    <div class="p-1 rounded-full bg-green-100 dark:bg-green-900/20 text-green-600 dark:text-green-400 w-7 h-7 flex items-center justify-center">
                                                        <i class="fas fa-check text-xs"></i>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="bg-white dark:bg-gray-800 border-b dark:border-gray-700">
                                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                                    Submit assignments
                                                </th>
                                                <td class="px-6 py-4 bg-blue-50/50 dark:bg-blue-900/10">
                                                    <div class="p-1 rounded-full bg-green-100 dark:bg-green-900/20 text-green-600 dark:text-green-400 w-7 h-7 flex items-center justify-center">
                                                        <i class="fas fa-check text-xs"></i>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 bg-purple-50/50 dark:bg-purple-900/10">
                                                    <div class="p-1 rounded-full bg-green-100 dark:bg-green-900/20 text-green-600 dark:text-green-400 w-7 h-7 flex items-center justify-center">
                                                        <i class="fas fa-check text-xs"></i>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="bg-white dark:bg-gray-800 border-b dark:border-gray-700">
                                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                                    View & download materials
                                                </th>
                                                <td class="px-6 py-4 bg-blue-50/50 dark:bg-blue-900/10">
                                                    <div class="p-1 rounded-full bg-green-100 dark:bg-green-900/20 text-green-600 dark:text-green-400 w-7 h-7 flex items-center justify-center">
                                                        <i class="fas fa-check text-xs"></i>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 bg-purple-50/50 dark:bg-purple-900/10">
                                                    <div class="p-1 rounded-full bg-green-100 dark:bg-green-900/20 text-green-600 dark:text-green-400 w-7 h-7 flex items-center justify-center">
                                                        <i class="fas fa-check text-xs"></i>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="bg-white dark:bg-gray-800 border-b dark:border-gray-700">
                                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                                    Interactive quizzes
                                                </th>
                                                <td class="px-6 py-4 bg-blue-50/50 dark:bg-blue-900/10">
                                                    <div class="p-1 rounded-full bg-red-100 dark:bg-red-900/20 text-red-600 dark:text-red-400 w-7 h-7 flex items-center justify-center">
                                                        <i class="fas fa-times text-xs"></i>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 bg-purple-50/50 dark:bg-purple-900/10">
                                                    <div class="p-1 rounded-full bg-green-100 dark:bg-green-900/20 text-green-600 dark:text-green-400 w-7 h-7 flex items-center justify-center">
                                                        <i class="fas fa-check text-xs"></i>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="bg-white dark:bg-gray-800">
                                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white rounded-bl-lg">
                                                    Payment methods
                                                </th>
                                                <td class="px-6 py-4 bg-blue-50/50 dark:bg-blue-900/10">
                                                    <span class="text-xs text-gray-500 dark:text-gray-400">N/A</span>
                                                </td>
                                                <td class="px-6 py-4 bg-purple-50/50 dark:bg-purple-900/10 rounded-br-lg">
                                                    <div class="flex flex-wrap gap-1.5">
                                                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded">
                                                            <i class="fas fa-credit-card mr-1 text-blue-500"></i> Credit Card
                                                        </span>
                                                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded">
                                                            <i class="fab fa-paypal mr-1 text-indigo-500"></i> PayPal
                                                        </span>
                                                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded">
                                                            <i class="fas fa-mobile-alt mr-1 text-green-500"></i> GCash
                                                        </span>
                                                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded">
                                                            <i class="fas fa-wallet mr-1 text-orange-500"></i> PayMaya
                                                        </span>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-tenant-app-layout>
