<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700">
                <i class="fas fa-cogs text-lg"></i>
            </span>
            <div>
                <p class="text-sm font-medium text-emerald-700">System parameters</p>
                <h2 class="text-2xl font-semibold tracking-tight text-gray-900">Platform Settings</h2>
            </div>
        </div>
    </x-slot>

    <div class="mx-auto max-w-4xl">
        @if(session('success'))
            <div class="mb-6 rounded-xl border border-green-200 bg-green-50 p-4 text-green-700 flex items-center gap-3">
                <i class="fas fa-check-circle text-lg"></i>
                <p class="font-medium">{{ session('success') }}</p>
            </div>
        @endif

        <form action="{{ route('settings.update') }}" method="POST" class="app-surface p-6 sm:p-8 space-y-8">
            @csrf
            
            <!-- General Settings -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-100 pb-3 flex items-center gap-2">
                    <i class="fas fa-sliders text-emerald-600"></i> General Configuration
                </h3>
                <div class="mt-4 grid grid-cols-1 gap-6">
                    <div>
                        <label for="app_name" class="block text-sm font-medium text-gray-700">Platform Name</label>
                        <input type="text" name="app_name" id="app_name" value="{{ $settings['app_name'] }}" required
                            class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end pt-4 border-t border-gray-100">
                <button type="submit" class="app-btn-primary px-6 py-3 text-base">
                    <i class="fas fa-save mr-2"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
