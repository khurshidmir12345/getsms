<x-dashboard-layout title="Shablonlar">

    <x-slot name="actions">
        <button
            @click="$dispatch('open-slide', 'add-template')"
            class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors duration-150 shadow-sm"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Shablon yaratish
        </button>
    </x-slot>

    {{-- Slide-over: Create Template --}}
    <div
        x-data="{ open: false }"
        @open-slide.window="if ($event.detail === 'add-template') open = true"
        x-show="open"
        x-cloak
        class="relative z-50"
    >
        {{-- Overlay --}}
        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="open = false"
            class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm"
        ></div>

        {{-- Panel --}}
        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-250"
            x-transition:enter-start="translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full"
            class="fixed inset-y-0 right-0 w-full max-w-md bg-white shadow-2xl flex flex-col"
        >
            <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100">
                <div>
                    <h2 class="text-base font-semibold text-slate-800">Shablon yaratish</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Qayta ishlatiladigan SMS shabloni</p>
                </div>
                <button @click="open = false"
                        class="p-2 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto px-6 py-5">
                <form method="POST" action="{{ route('templates.store') }}" id="addTemplateForm">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">
                            Nomi <span class="text-red-400">*</span>
                        </label>
                        <input
                            type="text"
                            name="name"
                            required
                            value="{{ old('name') }}"
                            placeholder="Shablon nomi"
                            class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                        >
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Kategoriya</label>
                        <input
                            type="text"
                            name="category"
                            value="{{ old('category') }}"
                            placeholder="masalan: marketing, bildirishnoma"
                            class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                        >
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">
                            Matn <span class="text-red-400">*</span>
                        </label>
                        <textarea
                            name="body"
                            rows="6"
                            required
                            placeholder="Salom {name}, sizning buyurtmangiz tayyor!"
                            class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-lg resize-none focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                        >{{ old('body') }}</textarea>
                        <div class="mt-2 flex items-start gap-2 p-2.5 bg-slate-50 rounded-lg border border-slate-100">
                            <svg class="w-4 h-4 text-slate-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-xs text-slate-500">
                                O'zgaruvchilar ishlatishingiz mumkin:
                                <code class="bg-slate-200 px-1 rounded text-slate-700">{name}</code>,
                                <code class="bg-slate-200 px-1 rounded text-slate-700">{phone}</code>
                            </p>
                        </div>
                    </div>
                </form>
            </div>

            <div class="flex items-center gap-3 px-6 py-4 border-t border-slate-100 bg-slate-50/60">
                <button type="submit" form="addTemplateForm"
                        class="flex-1 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors duration-150">
                    Saqlash
                </button>
                <button @click="open = false" type="button"
                        class="px-5 py-2.5 text-sm font-medium text-slate-600 hover:text-slate-800 transition-colors">
                    Bekor
                </button>
            </div>
        </div>
    </div>

    {{-- Template grid --}}
    @if($templates->isEmpty())
        {{-- Empty state --}}
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm px-6 py-20 text-center">
            <div class="flex flex-col items-center gap-3 text-slate-400">
                <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-sm font-medium text-slate-500">Shablonlar topilmadi</p>
                <button type="button"
                        @click="$dispatch('open-slide', 'add-template')"
                        class="text-sm text-indigo-600 hover:text-indigo-700 font-medium hover:underline">
                    Birinchi shablonni yarating &rarr;
                </button>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach ($templates as $template)
                <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm hover:shadow-md hover:border-slate-300 transition-all duration-150 flex flex-col">

                    {{-- Header --}}
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <h3 class="text-sm font-semibold text-slate-800 leading-snug">{{ $template->name }}</h3>
                        @if($template->category)
                            <span class="shrink-0 inline-flex items-center px-2 py-0.5 rounded-md bg-indigo-50 text-indigo-600 text-xs font-medium border border-indigo-100">
                                {{ $template->category }}
                            </span>
                        @else
                            <span class="shrink-0 inline-flex items-center px-2 py-0.5 rounded-md bg-slate-100 text-slate-500 text-xs font-medium">
                                Umumiy
                            </span>
                        @endif
                    </div>

                    {{-- Body preview --}}
                    <p class="text-sm text-slate-500 leading-relaxed flex-1"
                       style="-webkit-line-clamp:3; display:-webkit-box; -webkit-box-orient:vertical; overflow:hidden;">
                        {{ $template->body }}
                    </p>

                    {{-- Footer --}}
                    <div class="flex items-center justify-between mt-4 pt-3.5 border-t border-slate-100">
                        <span class="text-xs text-slate-400">
                            {{ mb_strlen($template->body) }} belgi
                        </span>
                        <form method="POST" action="{{ route('templates.destroy', $template) }}"
                              onsubmit="return confirm('Shablonni o\'chirishga ishonchingiz komilmi?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="text-xs font-medium text-red-500 hover:text-red-700 transition-colors px-2 py-1 rounded hover:bg-red-50">
                                O'chirish
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        @if($templates->hasPages())
            <div class="mt-6">
                {{ $templates->links() }}
            </div>
        @endif
    @endif

</x-dashboard-layout>
