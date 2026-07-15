<x-guest-layout>
    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm sm:p-8">
        <div class="mb-8"><p class="text-sm font-medium text-emerald-700">Super Admin access</p><h1 class="mt-2 text-2xl font-semibold tracking-tight text-gray-900">Welcome back</h1><p class="mt-2 text-sm leading-6 text-gray-500">Sign in to manage your platform and academy network.</p></div>
        @if(session('status'))<div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('status') }}</div>@endif
        <form method="POST" action="{{ route('login', [], false) }}" class="space-y-5">@csrf
            <div><label for="email" class="app-label">Email address</label><input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="name@example.com" class="app-input @error('email') border-red-500 @enderror">@error('email')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror</div>
            <div x-data="{ visible: false }"><div class="flex items-center justify-between"><label for="password" class="app-label">Password</label>@routeCheck('password.request')<a href="{{ route('password.request', [], false) }}" class="mb-1.5 text-sm font-medium text-emerald-700 hover:text-emerald-800">Forgot password?</a>@endrouteCheck</div><div class="relative"><input id="password" name="password" :type="visible ? 'text' : 'password'" required autocomplete="current-password" placeholder="Enter your password" class="app-input pr-11 @error('password') border-red-500 @enderror"><button type="button" @click="visible = !visible" class="absolute inset-y-0 right-0 px-3 text-gray-400 hover:text-emerald-700" aria-label="Show or hide password"><i class="fas" :class="visible ? 'fa-eye-slash' : 'fa-eye'"></i></button></div>@error('password')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror</div>
            <label class="flex items-center gap-2 text-sm text-gray-600"><input type="checkbox" name="remember" class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-600">Remember this device</label>
            <button type="submit" class="app-btn-primary w-full"><i class="fas fa-arrow-right-to-bracket"></i>Sign in to Central</button>
        </form>
    </div>
</x-guest-layout>
 