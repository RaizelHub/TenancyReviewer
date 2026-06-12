<x-tenant-guest-layout>
    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Reset Your Password</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-2">We'll send you a link to reset your password</p>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/30 border-l-4 border-green-500 text-green-700 dark:text-green-300 rounded">
            {{ session('status') }}
        </div>
    @endif

    <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg text-sm text-gray-600 dark:text-gray-400">
        {{ __('Enter your email address below and we will send you a password reset link.') }}
    </div>

    <form method="POST" action="{{ route('password.email', [], false) }}" class="space-y-6">
        @csrf

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
                    autofocus
                    autocomplete="email"
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

        <div>
            <button type="submit" class="auth-button w-full py-3 px-4 rounded-lg text-white font-medium focus:outline-none focus:ring-4 focus:ring-[#4473be] focus:ring-opacity-25">
                <span class="flex items-center justify-center">
                    <i class="fas fa-paper-plane mr-2"></i>
                    {{ __('Send Reset Link') }}
                </span>
            </button>
        </div>

        <div class="relative my-6">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-300 dark:border-gray-700"></div>
            </div>
        </div>

        <div class="text-center">
            <a href="{{ route('login', [], false) }}" class="inline-flex items-center justify-center text-[#4473be] dark:text-[#7da3e0] hover:text-[#3a5fa0] dark:hover:text-[#6b91ce] transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                {{ __('Back to login') }}
            </a>
        </div>
    </form>
</x-tenant-guest-layout>
