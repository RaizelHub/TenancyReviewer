@props(['message' => '', 'type' => 'success', 'show' => false])

<div 
    x-data="{ show: {{ $show ? 'true' : 'false' }} }" 
    x-show="show" 
    x-init="setTimeout(() => { show = false }, 5000)"
    @click="show = false"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform translate-y-2"
    x-transition:enter-end="opacity-100 transform translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform translate-y-0"
    x-transition:leave-end="opacity-0 transform translate-y-2"
    class="fixed bottom-4 right-4 z-50 flex items-center p-4 mb-4 rounded-lg shadow-lg cursor-pointer {{ $type === 'success' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 border-l-4 border-green-500' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 border-l-4 border-red-500' }}"
    role="alert"
>
    <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 {{ $type === 'success' ? 'text-green-500 bg-green-100/50 dark:bg-green-800/30 dark:text-green-200' : 'text-red-500 bg-red-100/50 dark:bg-red-800/30 dark:text-red-200' }} rounded-lg">
        @if($type === 'success')
            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
            </svg>
        @else
            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.5 11.5a1 1 0 0 1-2 0v-4a1 1 0 0 1 2 0v4Zm-3.5-2a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3Z"/>
            </svg>
        @endif
        <span class="sr-only">{{ $type === 'success' ? 'Success' : 'Error' }} icon</span>
    </div>
    <div class="ml-3 text-sm font-normal">{{ $message }}</div>
</div>
