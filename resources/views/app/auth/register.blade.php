<x-tenant-guest-layout>
    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Create Your Account</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-2">Join our platform to get started</p>
    </div>

    <form method="POST" action="{{ route('register', [], false) }}" class="space-y-6">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                <span class="flex items-center">
                    <i class="far fa-user text-[#4473be] mr-2"></i>
                    {{ __('Full Name') }}
                </span>
            </label>
            <div class="relative">
                <input id="name"
                    class="auth-input block w-full px-4 py-3 rounded-lg focus:outline-none @error('name') border-red-500 dark:border-red-500 @enderror"
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    autofocus
                    autocomplete="name"
                    placeholder="John Doe" />
                @error('name')
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <i class="fas fa-exclamation-circle text-red-500"></i>
                    </div>
                @enderror
            </div>
            @error('name')
                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                <span class="flex items-center">
                    <i class="far fa-envelope text-[#4473be] mr-2"></i>
                    {{ __('Email Address') }}
                </span>
            </label>
            <div class="relative">
                <input id="email"
                    class="auth-input block w-full px-4 py-3 rounded-lg focus:outline-none @error('email') border-red-500 dark:border-red-500 @enderror"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autocomplete="username"
                    placeholder="name@example.com" />
                @error('email')
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <i class="fas fa-exclamation-circle text-red-500"></i>
                    </div>
                @enderror
            </div>
            @error('email')
                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                <span class="flex items-center">
                    <i class="far fa-lock text-[#4473be] mr-2"></i>
                    {{ __('Password') }}
                </span>
            </label>
            <div class="relative" x-data="{ showPassword: false }">
                <input id="password"
                    class="auth-input block w-full px-4 py-3 rounded-lg focus:outline-none @error('password') border-red-500 dark:border-red-500 @enderror"
                    :type="showPassword ? 'text' : 'password'"
                    name="password"
                    required
                    autocomplete="new-password"
                    placeholder="••••••••" />
                <button type="button"
                    @click="showPassword = !showPassword"
                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 dark:text-gray-400 hover:text-[#4473be] dark:hover:text-[#7da3e0] focus:outline-none">
                    <i class="fas" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                </button>
                @error('password')
                    <div class="absolute inset-y-0 right-8 flex items-center pointer-events-none">
                        <i class="fas fa-exclamation-circle text-red-500"></i>
                    </div>
                @enderror
            </div>
            @error('password')
                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                Password must be at least 8 characters and contain letters and numbers
            </p>
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                <span class="flex items-center">
                    <i class="far fa-check-circle text-[#4473be] mr-2"></i>
                    {{ __('Confirm Password') }}
                </span>
            </label>
            <div class="relative" x-data="{ showPassword: false }">
                <input id="password_confirmation"
                    class="auth-input block w-full px-4 py-3 rounded-lg focus:outline-none @error('password_confirmation') border-red-500 dark:border-red-500 @enderror"
                    :type="showPassword ? 'text' : 'password'"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                    placeholder="••••••••" />
                <button type="button"
                    @click="showPassword = !showPassword"
                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 dark:text-gray-400 hover:text-[#4473be] dark:hover:text-[#7da3e0] focus:outline-none">
                    <i class="fas" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                </button>
                @error('password_confirmation')
                    <div class="absolute inset-y-0 right-8 flex items-center pointer-events-none">
                        <i class="fas fa-exclamation-circle text-red-500"></i>
                    </div>
                @enderror
            </div>
            @error('password_confirmation')
                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Terms and Conditions -->
        <div class="flex items-start">
            <div class="flex items-center h-5">
                <input id="terms" name="terms" type="checkbox" required class="rounded-sm border-gray-300 dark:border-gray-700 text-[#4473be] shadow-sm focus:ring-[#4473be] dark:focus:ring-[#4473be] dark:focus:ring-offset-gray-800">
            </div>
            <div class="ml-3 text-sm">
                <label for="terms" class="text-gray-600 dark:text-gray-400">
                    {{ __('I agree to the') }} <a href="#" class="text-[#4473be] dark:text-[#7da3e0] hover:underline">{{ __('Terms of Service') }}</a> {{ __('and') }} <a href="#" class="text-[#4473be] dark:text-[#7da3e0] hover:underline">{{ __('Privacy Policy') }}</a>
                </label>
            </div>
        </div>

        <div>
            <button type="submit" class="auth-button w-full py-3 px-4 rounded-lg text-white font-medium focus:outline-none focus:ring-4 focus:ring-[#4473be] focus:ring-opacity-25">
                <span class="flex items-center justify-center">
                    <i class="fas fa-user-plus mr-2"></i>
                    {{ __('Create Account') }}
                </span>
            </button>
        </div>

        <div class="relative my-6">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-300 dark:border-gray-700"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-2 bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400">
                    {{ __('Already have an account?') }}
                </span>
            </div>
        </div>

        <div class="text-center">
            <a href="{{ route('login', [], false) }}" class="inline-block w-full py-3 px-4 rounded-lg border border-[#4473be] text-[#4473be] dark:text-[#7da3e0] dark:border-[#7da3e0] font-medium hover:bg-[#4473be] hover:text-white dark:hover:bg-[#7da3e0] dark:hover:text-gray-900 transition-colors text-center">
                {{ __('Sign in instead') }}
            </a>
        </div>
    </form>
</x-tenant-guest-layout>
