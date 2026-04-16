<x-dashboard-layout title="Kampaniya yaratish">
    <div class="max-w-xl mx-auto">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">

            {{-- Card header --}}
            <div class="mb-6">
                <h2 class="text-base font-semibold text-slate-900">Yangi kampaniya</h2>
                <p class="text-xs text-slate-500 mt-0.5">Shablon va guruh tanlang, so'ng kampaniyani ishga tushiring.</p>
            </div>

            <form method="POST" action="{{ route('campaigns.store') }}" class="space-y-5">
                @csrf

                {{-- Kampaniya nomi --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Kampaniya nomi</label>
                    <input type="text"
                           name="name"
                           value="{{ old('name') }}"
                           required
                           placeholder="Masalan: Yangi yil tabriki"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-900 placeholder-slate-400
                                  focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition
                                  @error('name') border-red-400 bg-red-50 @enderror">
                    @error('name')
                        <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                            <svg class="w-3 h-3 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Shablon --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Shablon <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <select name="template_id"
                                required
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-900 appearance-none
                                       focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition pr-9
                                       @error('template_id') border-red-400 bg-red-50 @enderror">
                            <option value="">— Shablon tanlang —</option>
                            @foreach($templates as $template)
                                <option value="{{ $template->id }}" {{ old('template_id') == $template->id ? 'selected' : '' }}>
                                    {{ $template->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>
                    @error('template_id')
                        <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                            <svg class="w-3 h-3 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Kontakt guruhi --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Kontakt guruhi <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <select name="contact_group_id"
                                required
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-900 appearance-none
                                       focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition pr-9
                                       @error('contact_group_id') border-red-400 bg-red-50 @enderror">
                            <option value="">— Guruh tanlang —</option>
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}" {{ old('contact_group_id') == $group->id ? 'selected' : '' }}>
                                    {{ $group->name }}
                                    @if(isset($group->contacts_count))
                                        ({{ $group->contacts_count }} kontakt)
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>
                    @error('contact_group_id')
                        <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                            <svg class="w-3 h-3 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Qurilma --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Qurilma</label>
                    <div class="relative">
                        <select name="device_id"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-900 appearance-none
                                       focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition pr-9">
                            <option value="">Avtomatik tanlash</option>
                            @foreach($devices as $device)
                                <option value="{{ $device->id }}" {{ old('device_id') == $device->id ? 'selected' : '' }}>
                                    {{ $device->name }}
                                    @if(isset($device->model)) ({{ $device->model }}) @endif
                                    — {{ $device->status }}
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>
                    <p class="mt-1 text-xs text-slate-400">Tanlanmasa, tizim aktiv qurilmani avtomatik tanlaydi.</p>
                </div>

                {{-- Tezlik --}}
                <div>
                    <label class="flex items-center gap-0.5 text-xs font-semibold text-slate-700 mb-1.5">
                        Tezlik (SMS/daqiqa)
                        <x-info-tooltip>Daqiqasiga nechta SMS yuboriladi. Juda yuqori qiymat operatorning blokloviga olib kelishi mumkin.</x-info-tooltip>
                    </label>
                    <input type="number"
                           name="rate_limit"
                           value="{{ old('rate_limit', 20) }}"
                           min="1"
                           max="60"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-900
                                  focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition
                                  @error('rate_limit') border-red-400 bg-red-50 @enderror">
                    @error('rate_limit')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Divider --}}
                <div class="border-t border-slate-100 pt-5 flex items-center gap-3">
                    <button type="submit"
                            class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl transition-colors shadow-sm">
                        Yaratish
                    </button>
                    <a href="{{ route('campaigns.index') }}"
                       class="px-5 py-2.5 text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors">
                        Bekor
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-dashboard-layout>
