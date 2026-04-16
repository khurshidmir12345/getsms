<x-dashboard-layout title="Qurilmalar">

    {{-- ── Qurilma ulash: 3-step inline card ── --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 mb-6">
        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-4">Qurilma ulash</p>

        <div class="flex items-center gap-0">

            {{-- Step 1 --}}
            <div class="flex items-center gap-3 flex-1 min-w-0">
                <div class="flex-shrink-0 w-9 h-9 rounded-xl bg-indigo-50 flex items-center justify-center">
                    <svg class="w-4.5 h-4.5 text-indigo-600" style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                              d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-slate-800">1. Ilovani yuklab oling</p>
                    <p class="text-xs text-slate-400 truncate">Android telefoningizga o'rnating</p>
                </div>
            </div>

            {{-- Dashed connector --}}
            <div class="flex-shrink-0 mx-3">
                <svg class="w-10 h-px" viewBox="0 0 40 1" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <line x1="0" y1="0.5" x2="40" y2="0.5" stroke="#CBD5E1" stroke-dasharray="4 3"/>
                </svg>
            </div>

            {{-- Step 2 --}}
            <div class="flex items-center gap-3 flex-1 min-w-0">
                <div class="flex-shrink-0 w-9 h-9 rounded-xl bg-indigo-50 flex items-center justify-center">
                    <svg class="text-indigo-600" style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                              d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-slate-800">2. API kalitni kiriting</p>
                    <div class="flex items-center gap-1.5 mt-1" x-data="{ copied: false }">
                        <code class="text-xs font-mono bg-slate-100 text-slate-700 px-2 py-0.5 rounded-lg truncate max-w-[160px] select-all">{{ auth()->user()->api_key }}</code>
                        <button type="button"
                                @click="navigator.clipboard.writeText('{{ auth()->user()->api_key }}'); copied = true; setTimeout(() => copied = false, 2000)"
                                class="flex-shrink-0 px-2 py-0.5 text-xs font-medium rounded-lg transition-colors"
                                :class="copied ? 'bg-emerald-100 text-emerald-700' : 'bg-indigo-50 text-indigo-600 hover:bg-indigo-100'">
                            <span x-show="!copied">Nusxa</span>
                            <span x-show="copied" x-cloak>Nusxalandi!</span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Dashed connector --}}
            <div class="flex-shrink-0 mx-3">
                <svg class="w-10 h-px" viewBox="0 0 40 1" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <line x1="0" y1="0.5" x2="40" y2="0.5" stroke="#CBD5E1" stroke-dasharray="4 3"/>
                </svg>
            </div>

            {{-- Step 3 --}}
            <div class="flex items-center gap-3 flex-shrink-0">
                <div class="flex-shrink-0 w-9 h-9 rounded-xl bg-emerald-50 flex items-center justify-center">
                    <svg class="text-emerald-600" style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-800">3. Tayyor!</p>
                    <p class="text-xs text-slate-400">Qurilma quyida ko'rinadi</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Device grid ── --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse ($devices as $device)
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 flex flex-col gap-4 hover:shadow-md transition-shadow">

            {{-- Card top: name + badge --}}
            <div class="flex items-start justify-between gap-2">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                  d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-slate-900 truncate">{{ $device->name }}</p>
                        <p class="text-xs text-slate-400 truncate">{{ $device->model ?? 'Noma\'lum model' }}</p>
                    </div>
                </div>
                <x-status-badge :status="$device->isOnline() ? 'online' : 'offline'"/>
            </div>

            {{-- Info rows --}}
            <div class="space-y-2.5 text-xs">
                <div class="flex justify-between items-center">
                    <span class="text-slate-500">Telefon</span>
                    <span class="text-slate-800 font-medium">{{ $device->phone_number ?? '—' }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-slate-500">Operator</span>
                    <span class="text-slate-800 font-medium">{{ $device->operator ?? '—' }}</span>
                </div>

                {{-- Battery with colored bar --}}
                <div class="flex justify-between items-center gap-3">
                    <span class="text-slate-500 flex-shrink-0">Batareya</span>
                    @if($device->battery_level !== null)
                        @php
                            $bat = $device->battery_level;
                            $batColor = $bat > 50 ? 'bg-emerald-500' : ($bat > 20 ? 'bg-amber-400' : 'bg-red-500');
                        @endphp
                        <div class="flex items-center gap-1.5 flex-1 justify-end">
                            <div class="w-16 bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                <div class="{{ $batColor }} h-1.5 rounded-full transition-all" style="width: {{ $bat }}%"></div>
                            </div>
                            <span class="text-slate-800 font-medium tabular-nums">{{ $bat }}%</span>
                        </div>
                    @else
                        <span class="text-slate-400">—</span>
                    @endif
                </div>

                <div class="flex justify-between items-center">
                    <span class="text-slate-500">Signal</span>
                    <span class="text-slate-800 font-medium">
                        {{ $device->signal_strength !== null ? $device->signal_strength . '%' : '—' }}
                    </span>
                </div>
            </div>

            {{-- Last seen --}}
            <div class="text-xs text-slate-400 flex items-center gap-1">
                <svg class="w-3.5 h-3.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                So'nggi faollik:
                <span class="text-slate-600 font-medium">
                    {{ $device->last_seen_at?->diffForHumans() ?? 'Hech qachon' }}
                </span>
            </div>

            {{-- Footer actions --}}
            <div class="flex items-center gap-2 pt-3 border-t border-slate-100">
                <form method="POST" action="{{ route('devices.toggle', $device) }}" class="flex-1">
                    @csrf
                    <button type="submit"
                            class="w-full py-1.5 rounded-lg text-xs font-medium transition-colors
                                   {{ $device->is_active
                                       ? 'bg-amber-50 text-amber-700 hover:bg-amber-100'
                                       : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}">
                        {{ $device->is_active ? 'O\'chirish' : 'Yoqish' }}
                    </button>
                </form>
                <form method="POST" action="{{ route('devices.destroy', $device) }}"
                      onsubmit="return confirm('Qurilmani o\'chirishga ishonchingiz komilmi?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="px-3 py-1.5 rounded-lg text-xs font-medium bg-red-50 text-red-600 hover:bg-red-100 transition-colors">
                        O'chirish
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="col-span-full flex flex-col items-center justify-center py-16 gap-4">
            <div class="w-14 h-14 rounded-2xl bg-slate-100 flex items-center justify-center">
                <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
            </div>
            <div class="text-center">
                <p class="text-sm font-semibold text-slate-700">Hali qurilmalar ulanmagan</p>
                <p class="text-xs text-slate-400 mt-1 max-w-xs">
                    Android telefoningizga SMS Gateway ilovasini o'rnating va API kalitni kiritib ulang.
                </p>
            </div>
        </div>
        @endforelse
    </div>
</x-dashboard-layout>
