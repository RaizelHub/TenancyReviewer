<x-guest-layout>
    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Welcome Back!</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-2">Sign in to your account to continue</p>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="mb-6 p-4 bg-purple-50 dark:bg-purple-900/30 border-l-4 border-purple-500 text-purple-700 dark:text-purple-300 rounded">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login', [], false) }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                <span class="flex items-center">
                    <i class="far fa-envelope text-purple-500 mr-2"></i>
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
                    autofocus
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
                    <i class="far fa-lock text-purple-500 mr-2"></i>
                    {{ __('Password') }}
                </span>
            </label>
            <div class="relative" x-data="{ showPassword: false }">
                <input id="password"
                    class="auth-input block w-full px-4 py-3 rounded-lg focus:outline-none @error('password') border-red-500 dark:border-red-500 @enderror"
                    :type="showPassword ? 'text' : 'password'"
                    name="password"
                    required
                    autocomplete="current-password"
                    placeholder="••••••••" />
                <button type="button"
                    @click="showPassword = !showPassword"
                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 focus:outline-none">
                    <i class="far" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                </button>
                @error('password')
                    <div class="absolute inset-y-0 right-8 flex items-center pr-3 pointer-events-none">
                        <i class="fas fa-exclamation-circle text-red-500"></i>
                    </div>
                @enderror
            </div>
            @error('password')
                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me"
                    type="checkbox"
                    class="rounded-sm border-gray-300 dark:border-gray-700 text-purple-600 shadow-sm focus:ring-purple-500 dark:focus:ring-purple-600 dark:focus:ring-offset-gray-800"
                    name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>

            @routeCheck('password.request')
                <a class="text-sm text-purple-600 dark:text-purple-400 hover:text-purple-800 dark:hover:text-purple-300 transition-colors" href="{{ route('password.request', [], false) }}">
                    {{ __('Forgot password?') }}
                </a>
            @endrouteCheck
        </div>

        <div>
            <button type="submit" class="auth-button w-full py-3 px-4 rounded-lg text-white font-medium focus:outline-none focus:ring-4 focus:ring-purple-500 focus:ring-opacity-25">
                <span class="flex items-center justify-center">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    {{ __('Sign in') }}
                </span>
            </button>
        </div>

        <div class="relative my-6">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-300 dark:border-gray-700"></div>
            </div>
        </div>
    </form>
</x-guest-layout>
