@props(['position' => 'top'])

<span class="relative inline-flex items-center group" x-data="{ open: false }">
    <button @click="open = !open" @click.outside="open = false" type="button" class="inline-flex items-center justify-center w-4 h-4 rounded-full bg-slate-200 hover:bg-indigo-100 text-slate-500 hover:text-indigo-600 transition-colors cursor-pointer ml-1">
        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z" clip-rule="evenodd"/></svg>
    </button>
    <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
        class="absolute z-50 w-64 px-3 py-2 text-xs text-slate-700 bg-white border border-slate-200 rounded-lg shadow-lg {{ $position === 'top' ? 'bottom-full mb-2' : ($position === 'bottom' ? 'top-full mt-2' : ($position === 'left' ? 'right-full mr-2' : 'left-full ml-2')) }}"
        style="display: none;">
        {{ $slot }}
    </div>
</span>
