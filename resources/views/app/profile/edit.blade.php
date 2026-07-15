<x-tenant-app-layout>
    @php($isStudent = auth()->guard('student')->check())
    <x-slot name="header">
        <div>
            <p class="text-sm font-medium text-emerald-700">{{ $isStudent ? 'Student account' : 'Teacher account' }}</p>
            <h2 class="mt-1 text-2xl font-semibold tracking-tight text-gray-900">Profile settings</h2>
            <p class="mt-1 text-sm text-gray-500">Keep your account information and profile photo up to date.</p>
        </div>
    </x-slot>

    <div class="mx-auto max-w-5xl">
        <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
            <div class="flex flex-col gap-5 border-b border-gray-200 bg-gray-50 px-6 py-6 sm:flex-row sm:items-center">
                @if($user->profile_photo)<img src="{{ $user->profile_photo }}" alt="{{ $user->name }}" class="h-16 w-16 rounded-2xl object-cover">@else<span class="flex h-16 w-16 items-center justify-center rounded-2xl bg-emerald-100 text-xl font-bold text-emerald-700">{{ strtoupper(substr($user->name, 0, 1)) }}</span>@endif
                <div class="min-w-0"><h3 class="truncate text-xl font-semibold text-gray-900">{{ $user->name }}</h3><p class="mt-1 truncate text-sm text-gray-500">{{ $user->email }}</p><span class="mt-3 inline-flex rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">{{ $isStudent ? 'Student' : 'Instructor' }}</span></div>
            </div>
            <div class="grid grid-cols-1 gap-8 p-6 lg:grid-cols-[13rem_minmax(0,1fr)]">
                <aside class="border-b border-gray-200 pb-5 lg:border-b-0 lg:border-r lg:pb-0 lg:pr-6"><p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Settings</p><div class="mt-4 space-y-2"><a href="#profile" class="flex items-center gap-3 rounded-xl bg-emerald-50 px-3 py-2.5 text-sm font-semibold text-emerald-700"><i class="fas fa-user w-4"></i>Profile</a><a href="#security" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50"><i class="fas fa-lock w-4"></i>Security</a></div></aside>
                <div class="space-y-8"><section id="profile" class="scroll-mt-24"><div class="mb-6"><h3 class="text-lg font-semibold text-gray-900">Profile information</h3><p class="mt-1 text-sm text-gray-500">Your name and email are managed by your account administrator. You can update your profile photo here.</p></div>@include('app.profile.partials.update-profile-information-form')</section><section id="security" class="border-t border-gray-200 pt-8 scroll-mt-24"><div class="mb-6"><h3 class="text-lg font-semibold text-gray-900">Password & security</h3><p class="mt-1 text-sm text-gray-500">Maintain a secure password for your account.</p></div>@include('app.profile.partials.update-password-form')</section></div>
            </div>
        </section>
    </div>
</x-tenant-app-layout>
 