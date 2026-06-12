<x-tenant-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                <div class="p-2 rounded-lg bg-purple-100 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400 mr-3">
                    <i class="fas fa-crown"></i>
                </div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Upgrade to Premium') }}
                </h2>
            </div>
            <div class="flex items-center">
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                    <i class="fas fa-user mr-1.5"></i> {{ ucfirst($student->plan) }} Plan
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg shadow-sm" role="alert">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md rounded-xl border border-gray-100 dark:border-gray-700">
                <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gradient-to-r from-gray-50 to-white dark:from-gray-800 dark:to-gray-750">
                    <div class="flex items-center">
                        <div class="p-2 rounded-lg bg-purple-100 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400 mr-3">
                            <i class="fas fa-arrow-circle-up"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Upgrade Your Learning Experience</h3>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Plan Comparison -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                <i class="fas fa-exchange-alt text-blue-500 mr-2"></i> Plan Comparison
                            </h3>

                            <div class="bg-white dark:bg-gray-800 rounded-xl p-5 mb-6 border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow duration-300">
                                <div class="flex justify-between items-center mb-4">
                                    <div>
                                        <h4 class="font-medium text-gray-900 dark:text-white text-lg">Basic Plan</h4>
                                        <div class="flex items-center mt-1">
                                            <span class="text-sm text-gray-500 dark:text-gray-400">Your current plan</span>
                                            @if($student->plan === 'basic')
                                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                                    <i class="fas fa-check-circle mr-1"></i> Active
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                        Free
                                    </span>
                                </div>
                                <ul class="space-y-3 text-sm text-gray-600 dark:text-gray-300">
                                    <li class="flex items-center">
                                        <div class="p-1 rounded-full bg-green-100 dark:bg-green-900/20 text-green-600 dark:text-green-400 mr-3">
                                            <i class="fas fa-check text-xs"></i>
                                        </div>
                                        Access to assigned subjects
                                    </li>
                                    <li class="flex items-center">
                                        <div class="p-1 rounded-full bg-green-100 dark:bg-green-900/20 text-green-600 dark:text-green-400 mr-3">
                                            <i class="fas fa-check text-xs"></i>
                                        </div>
                                        Submit assignments
                                    </li>
                                    <li class="flex items-center">
                                        <div class="p-1 rounded-full bg-green-100 dark:bg-green-900/20 text-green-600 dark:text-green-400 mr-3">
                                            <i class="fas fa-check text-xs"></i>
                                        </div>
                                        View grades and feedback
                                    </li>
                                    <li class="flex items-center">
                                        <div class="p-1 rounded-full bg-green-100 dark:bg-green-900/20 text-green-600 dark:text-green-400 mr-3">
                                            <i class="fas fa-check text-xs"></i>
                                        </div>
                                        View learning materials
                                    </li>
                                    <li class="flex items-center text-gray-400">
                                        <div class="p-1 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-400 mr-3">
                                            <i class="fas fa-times text-xs"></i>
                                        </div>
                                        Download learning materials
                                    </li>
                                    <li class="flex items-center text-gray-400">
                                        <div class="p-1 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-400 mr-3">
                                            <i class="fas fa-times text-xs"></i>
                                        </div>
                                        Access to interactive quizzes
                                    </li>
                                </ul>
                            </div>

                            <div class="bg-gradient-to-br from-purple-50 to-indigo-50 dark:from-purple-900/20 dark:to-indigo-900/20 rounded-xl p-5 border-2 border-purple-500 dark:border-purple-600 shadow-md relative overflow-hidden">
                                <!-- Decorative elements -->
                                <div class="absolute top-0 right-0 w-32 h-32 -mt-10 -mr-10 bg-purple-200 dark:bg-purple-800/30 rounded-full opacity-30"></div>
                                <div class="absolute bottom-0 left-0 w-24 h-24 -mb-8 -ml-8 bg-indigo-200 dark:bg-indigo-800/30 rounded-full opacity-30"></div>

                                <div class="relative">
                                    <div class="absolute -top-6 -right-6">
                                        <div class="bg-gradient-to-r from-amber-500 to-yellow-500 text-white text-xs font-bold px-3 py-1 rounded-bl-lg rounded-tr-lg shadow-md transform rotate-12">
                                            RECOMMENDED
                                        </div>
                                    </div>

                                    <div class="flex justify-between items-center mb-4">
                                        <div>
                                            <h4 class="font-semibold text-gray-900 dark:text-white text-lg">Premium Plan</h4>
                                            <div class="flex items-center mt-1">
                                                <span class="text-sm text-gray-500 dark:text-gray-400">Unlock all features</span>
                                                @if($student->plan === 'premium')
                                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300">
                                                        <i class="fas fa-check-circle mr-1"></i> Active
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300">
                                            <i class="fas fa-crown text-amber-500 mr-1.5"></i> ₱4,999/year
                                        </span>
                                    </div>
                                    <ul class="space-y-3 text-sm text-gray-600 dark:text-gray-300">
                                        <li class="flex items-center">
                                            <div class="p-1 rounded-full bg-green-100 dark:bg-green-900/20 text-green-600 dark:text-green-400 mr-3">
                                                <i class="fas fa-check text-xs"></i>
                                            </div>
                                            Access to assigned subjects
                                        </li>
                                        <li class="flex items-center">
                                            <div class="p-1 rounded-full bg-green-100 dark:bg-green-900/20 text-green-600 dark:text-green-400 mr-3">
                                                <i class="fas fa-check text-xs"></i>
                                            </div>
                                            Submit assignments
                                        </li>
                                        <li class="flex items-center">
                                            <div class="p-1 rounded-full bg-green-100 dark:bg-green-900/20 text-green-600 dark:text-green-400 mr-3">
                                                <i class="fas fa-check text-xs"></i>
                                            </div>
                                            View grades and feedback
                                        </li>
                                        <li class="flex items-center">
                                            <div class="p-1 rounded-full bg-green-100 dark:bg-green-900/20 text-green-600 dark:text-green-400 mr-3">
                                                <i class="fas fa-check text-xs"></i>
                                            </div>
                                            View learning materials
                                        </li>
                                        <li class="flex items-center font-medium text-purple-700 dark:text-purple-300">
                                            <div class="p-1 rounded-full bg-purple-100 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400 mr-3">
                                                <i class="fas fa-check text-xs"></i>
                                            </div>
                                            Download learning materials
                                        </li>
                                        <li class="flex items-center font-medium text-purple-700 dark:text-purple-300">
                                            <div class="p-1 rounded-full bg-purple-100 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400 mr-3">
                                                <i class="fas fa-check text-xs"></i>
                                            </div>
                                            Access to interactive quizzes
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Upgrade Form -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                <i class="fas fa-credit-card text-purple-500 mr-2"></i> Upgrade Your Plan
                            </h3>

                            <form method="POST" action="{{ route('student.upgrade.process') }}" class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-200 dark:border-gray-700 shadow-sm">
                                @csrf

                                <div class="mb-6">
                                    <label for="payment_method" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3 flex items-center">
                                        <i class="fas fa-money-bill-wave text-green-500 mr-2"></i> Select Payment Method
                                    </label>

                                    <div class="grid grid-cols-2 gap-4">
                                        <!-- Credit Card Option -->
                                        <div>
                                            <input type="radio" name="payment_method" id="credit_card" value="credit_card" class="hidden peer" required>
                                            <label for="credit_card" class="flex flex-col items-center justify-between p-4 text-gray-500 bg-white border-2 border-gray-200 rounded-xl cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:border-purple-500 peer-checked:border-purple-600 peer-checked:text-purple-600 hover:border-purple-200 hover:text-purple-600 hover:shadow-md transition-all duration-200 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-750">
                                                <div class="w-full flex justify-center mb-3">
                                                    <div class="p-3 rounded-full bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400">
                                                        <i class="fas fa-credit-card text-xl"></i>
                                                    </div>
                                                </div>
                                                <div class="w-full text-center">
                                                    <div class="w-full text-lg font-semibold">Credit Card</div>
                                                    <div class="w-full text-sm">Visa, Mastercard, etc.</div>
                                                </div>
                                            </label>
                                        </div>

                                        <!-- PayPal Option -->
                                        <div>
                                            <input type="radio" name="payment_method" id="paypal" value="paypal" class="hidden peer">
                                            <label for="paypal" class="flex flex-col items-center justify-between p-4 text-gray-500 bg-white border-2 border-gray-200 rounded-xl cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:border-purple-500 peer-checked:border-purple-600 peer-checked:text-purple-600 hover:border-purple-200 hover:text-purple-600 hover:shadow-md transition-all duration-200 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-750">
                                                <div class="w-full flex justify-center mb-3">
                                                    <div class="p-3 rounded-full bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400">
                                                        <i class="fab fa-paypal text-xl"></i>
                                                    </div>
                                                </div>
                                                <div class="w-full text-center">
                                                    <div class="w-full text-lg font-semibold">PayPal</div>
                                                    <div class="w-full text-sm">Fast & secure</div>
                                                </div>
                                            </label>
                                        </div>

                                        <!-- GCash Option -->
                                        <div>
                                            <input type="radio" name="payment_method" id="gcash" value="gcash" class="hidden peer">
                                            <label for="gcash" class="flex flex-col items-center justify-between p-4 text-gray-500 bg-white border-2 border-gray-200 rounded-xl cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:border-purple-500 peer-checked:border-purple-600 peer-checked:text-purple-600 hover:border-purple-200 hover:text-purple-600 hover:shadow-md transition-all duration-200 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-750">
                                                <div class="w-full flex justify-center mb-3">
                                                    <div class="p-3 rounded-full bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400">
                                                        <i class="fas fa-mobile-alt text-xl"></i>
                                                    </div>
                                                </div>
                                                <div class="w-full text-center">
                                                    <div class="w-full text-lg font-semibold">GCash</div>
                                                    <div class="w-full text-sm">Most popular in PH</div>
                                                </div>
                                            </label>
                                        </div>

                                        <!-- PayMaya Option -->
                                        <div>
                                            <input type="radio" name="payment_method" id="paymaya" value="paymaya" class="hidden peer">
                                            <label for="paymaya" class="flex flex-col items-center justify-between p-4 text-gray-500 bg-white border-2 border-gray-200 rounded-xl cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:border-purple-500 peer-checked:border-purple-600 peer-checked:text-purple-600 hover:border-purple-200 hover:text-purple-600 hover:shadow-md transition-all duration-200 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-750">
                                                <div class="w-full flex justify-center mb-3">
                                                    <div class="p-3 rounded-full bg-orange-50 dark:bg-orange-900/20 text-orange-600 dark:text-orange-400">
                                                        <i class="fas fa-wallet text-xl"></i>
                                                    </div>
                                                </div>
                                                <div class="w-full text-center">
                                                    <div class="w-full text-lg font-semibold">PayMaya</div>
                                                    <div class="w-full text-sm">Maya Philippines</div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>

                                    @error('payment_method')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1.5"></i> {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Payment Summary -->
                                <div class="mb-6 p-5 bg-gradient-to-br from-purple-50 to-indigo-50 dark:from-purple-900/10 dark:to-indigo-900/10 rounded-xl border border-purple-100 dark:border-purple-900/20">
                                    <h4 class="font-medium text-gray-900 dark:text-white mb-4 flex items-center">
                                        <i class="fas fa-receipt text-purple-500 mr-2"></i> Payment Summary
                                    </h4>
                                    <div class="flex justify-between mb-3 text-sm">
                                        <span class="text-gray-600 dark:text-gray-300">Premium Plan (1 year)</span>
                                        <span class="text-gray-900 dark:text-white font-medium">₱4,999.00</span>
                                    </div>
                                    <div class="flex justify-between mb-3 text-sm">
                                        <span class="text-gray-600 dark:text-gray-300">VAT (12%)</span>
                                        <span class="text-gray-900 dark:text-white font-medium">Included</span>
                                    </div>
                                    <div class="border-t border-purple-200 dark:border-purple-800/30 my-3 pt-3">
                                        <div class="flex justify-between font-medium">
                                            <span class="text-gray-900 dark:text-white">Total</span>
                                            <span class="text-xl text-purple-600 dark:text-purple-400">₱4,999.00</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/10 rounded-lg border border-blue-100 dark:border-blue-900/20">
                                    <div class="flex items-start">
                                        <div class="flex items-center h-5 mt-0.5">
                                            <input id="terms_accepted" name="terms_accepted" type="checkbox" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800" required>
                                        </div>
                                        <label for="terms_accepted" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                            I agree to the <a href="#" class="text-blue-600 hover:underline dark:text-blue-500">terms and conditions</a> and understand that my plan will be upgraded immediately.
                                        </label>
                                    </div>
                                    @error('terms_accepted')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1.5"></i> {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div class="flex items-center justify-between">
                                    <a href="{{ route('student.dashboard') }}" class="text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white flex items-center">
                                        <i class="fas fa-arrow-left mr-1.5"></i> Cancel
                                    </a>
                                    <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-lg hover:from-purple-700 hover:to-indigo-700 transition-all duration-200 shadow-sm flex items-center">
                                        <i class="fas fa-crown text-amber-300 mr-2"></i>
                                        Pay ₱4,999 & Upgrade
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-tenant-app-layout>
