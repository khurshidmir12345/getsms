<x-dashboard-layout title="Kontaktlar">

    <x-slot name="actions">
        <div class="flex items-center gap-2" x-data>
            <button
                @click="$dispatch('open-slide', 'import')"
                class="inline-flex items-center gap-2 px-4 py-2 border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-sm font-medium rounded-lg transition-colors duration-150 shadow-sm"
            >
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3"/>
                </svg>
                Import CSV
            </button>
            <button
                @click="$dispatch('open-slide', 'add-contact')"
                class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors duration-150 shadow-sm"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
                Kontakt qo'shish
            </button>
        </div>
    </x-slot>

    {{-- Slide-over: Add Contact --}}
    <div
        x-data="{ open: false }"
        @open-slide.window="if ($event.detail === 'add-contact') open = true"
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
                    <h2 class="text-base font-semibold text-slate-800">Kontakt qo'shish</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Yangi kontakt ma'lumotlarini kiriting</p>
                </div>
                <button @click="open = false"
                        class="p-2 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto px-6 py-5">
                <form method="POST" action="{{ route('contacts.store') }}" id="addContactForm">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Ism <span class="text-red-400">*</span></label>
                        <input type="text" name="name" required value="{{ old('name') }}"
                               placeholder="To'liq ismi"
                               class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Telefon <span class="text-red-400">*</span></label>
                        <input type="text" name="phone" required value="{{ old('phone') }}"
                               placeholder="+998 90 123 45 67"
                               class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               placeholder="email@example.com"
                               class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Guruh</label>
                        <select name="contact_group_id"
                                class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition appearance-none">
                            <option value="">— Guruhsiz —</option>
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}" {{ old('contact_group_id') == $group->id ? 'selected' : '' }}>
                                    {{ $group->name }}
                                    @if(isset($group->contacts_count)) ({{ $group->contacts_count }}) @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>

            <div class="flex items-center gap-3 px-6 py-4 border-t border-slate-100 bg-slate-50/60">
                <button type="submit" form="addContactForm"
                        class="flex-1 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors duration-150">
                    Saqlash
                </button>
                <button @click="open = false"
                        type="button"
                        class="px-5 py-2.5 text-sm font-medium text-slate-600 hover:text-slate-800 transition-colors">
                    Bekor
                </button>
            </div>
        </div>
    </div>

    {{-- Slide-over: Import CSV --}}
    <div
        x-data="{ open: false }"
        @open-slide.window="if ($event.detail === 'import') open = true"
        x-show="open"
        x-cloak
        class="relative z-50"
    >
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
                    <h2 class="text-base font-semibold text-slate-800">CSV Import</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Kontaktlarni fayl orqali yuklang</p>
                </div>
                <button @click="open = false"
                        class="p-2 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto px-6 py-5">
                <form method="POST" action="{{ route('contacts.import') }}" enctype="multipart/form-data" id="importForm">
                    @csrf

                    {{-- Info box --}}
                    <div class="mb-5 flex gap-3 p-3.5 bg-indigo-50 border border-indigo-100 rounded-xl">
                        <svg class="w-5 h-5 text-indigo-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-xs text-indigo-700 leading-relaxed">
                            CSV da <strong>'name'</strong> va <strong>'phone'</strong> ustunlari kerak.<br>
                            Fayl formati: <code class="bg-indigo-100 px-1 rounded">.csv</code> yoki <code class="bg-indigo-100 px-1 rounded">.txt</code>
                        </p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Fayl <span class="text-red-400">*</span></label>
                        <label class="flex flex-col items-center justify-center w-full h-28 border-2 border-dashed border-slate-200 rounded-xl cursor-pointer bg-slate-50 hover:bg-slate-100 transition-colors">
                            <svg class="w-7 h-7 text-slate-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span class="text-xs text-slate-400">CSV yoki TXT faylni tanlang</span>
                            <input type="file" name="file" accept=".csv,.txt" required class="hidden">
                        </label>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Guruh</label>
                        <select name="contact_group_id"
                                class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition appearance-none">
                            <option value="">— Guruhsiz —</option>
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>

            <div class="flex items-center gap-3 px-6 py-4 border-t border-slate-100 bg-slate-50/60">
                <button type="submit" form="importForm"
                        class="flex-1 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors duration-150">
                    Import qilish
                </button>
                <button @click="open = false" type="button"
                        class="px-5 py-2.5 text-sm font-medium text-slate-600 hover:text-slate-800 transition-colors">
                    Bekor
                </button>
            </div>
        </div>
    </div>

    {{-- Filter bar --}}
    <div class="bg-white border border-slate-200 rounded-2xl p-4 mb-5 shadow-sm">
        <form method="GET" class="flex flex-wrap items-center gap-3">
            <div class="relative flex-1 min-w-[200px]">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                </svg>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Ism yoki raqam..."
                    class="w-full pl-9 pr-3 py-2 text-sm border border-slate-200 rounded-lg bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                >
            </div>

            <select name="group_id"
                class="py-2 pl-3 pr-8 text-sm border border-slate-200 rounded-lg bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition appearance-none">
                <option value="">Barcha guruhlar</option>
                @foreach($groups as $group)
                    <option value="{{ $group->id }}" @selected(request('group_id') == $group->id)>
                        {{ $group->name }}
                        @if(isset($group->contacts_count)) ({{ $group->contacts_count }}) @endif
                    </option>
                @endforeach
            </select>

            <button type="submit"
                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors duration-150">
                Filter
            </button>

            @if(request()->hasAny(['search','group_id']))
                <a href="{{ route('contacts.index') }}"
                   class="px-3 py-2 text-sm text-slate-500 hover:text-slate-700 transition-colors">
                    Tozalash
                </a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-slate-100">
            <thead>
                <tr class="bg-slate-50">
                    <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Ism</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Telefon</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Email</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Guruh</th>
                    <th class="px-5 py-3 text-right text-xs font-semibold text-slate-400 uppercase tracking-wider">Amallar</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($contacts as $contact)
                    <tr class="hover:bg-slate-50 transition-colors duration-100">
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center shrink-0">
                                    <span class="text-xs font-semibold text-indigo-600">
                                        {{ mb_strtoupper(mb_substr($contact->name, 0, 1)) }}
                                    </span>
                                </div>
                                <span class="text-sm font-medium text-slate-800">{{ $contact->name }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-3.5 text-sm text-slate-600 font-mono">{{ $contact->phone }}</td>
                        <td class="px-5 py-3.5 text-sm text-slate-500">{{ $contact->email ?? '—' }}</td>
                        <td class="px-5 py-3.5 text-sm">
                            @if($contact->group)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-slate-100 text-slate-600 text-xs font-medium">
                                    {{ $contact->group->name }}
                                </span>
                            @else
                                <span class="text-slate-400">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <form method="POST" action="{{ route('contacts.destroy', $contact) }}"
                                  class="inline"
                                  onsubmit="return confirm('Kontaktni o\'chirishga ishonchingiz komilmi?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="text-xs font-medium text-red-500 hover:text-red-700 transition-colors px-2 py-1 rounded hover:bg-red-50">
                                    O'chirish
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-16 text-center">
                            <div class="flex flex-col items-center gap-3 text-slate-400">
                                <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                </svg>
                                <p class="text-sm font-medium text-slate-500">Kontaktlar topilmadi</p>
                                <button type="button"
                                        @click="$dispatch('open-slide', 'add-contact')"
                                        class="text-sm text-indigo-600 hover:text-indigo-700 font-medium hover:underline">
                                    Birinchi kontaktni qo'shing &rarr;
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($contacts->hasPages())
            <div class="px-5 py-4 border-t border-slate-100 bg-slate-50/50">
                {{ $contacts->withQueryString()->links() }}
            </div>
        @endif
    </div>

</x-dashboard-layout>
