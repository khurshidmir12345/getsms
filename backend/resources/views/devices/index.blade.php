<x-dashboard-layout title="Qurilmalar">

    {{-- ══════════════════════════════════════════════════════════
         SECTION 1: Ilovani olish — Download + Telegram
    ══════════════════════════════════════════════════════════ --}}
    <div class="mb-8">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="flex flex-col sm:flex-row">
                {{-- Left: App preview --}}
                <div class="sm:w-56 bg-gradient-to-br from-slate-900 to-slate-800 flex items-center justify-center p-8">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-indigo-600 rounded-2xl flex items-center justify-center mx-auto shadow-lg shadow-indigo-600/30">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                        </div>
                        <p class="text-white font-semibold text-sm mt-3">SMS Gateway</p>
                        <p class="text-slate-400 text-[10px] mt-0.5">v1.0 • Android</p>
                    </div>
                </div>

                {{-- Right: Info + Actions --}}
                <div class="flex-1 p-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="text-base font-semibold text-slate-900">SMS Gateway ilovasi</h3>
                            <p class="text-sm text-slate-500 mt-1">Telefoningizni SMS serverga aylantiradi</p>
                        </div>
                        <span class="text-xs text-slate-400 bg-slate-50 px-2 py-1 rounded-lg">47 MB</span>
                    </div>

                    {{-- Features --}}
                    <div class="flex flex-wrap gap-2 mt-4">
                        <span class="inline-flex items-center gap-1 text-xs text-slate-600 bg-slate-50 px-2.5 py-1 rounded-lg">
                            <svg class="w-3 h-3 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Background ishlaydi
                        </span>
                        <span class="inline-flex items-center gap-1 text-xs text-slate-600 bg-slate-50 px-2.5 py-1 rounded-lg">
                            <svg class="w-3 h-3 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Android 5.0+
                        </span>
                        <span class="inline-flex items-center gap-1 text-xs text-slate-600 bg-slate-50 px-2.5 py-1 rounded-lg">
                            <svg class="w-3 h-3 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Delivery report
                        </span>
                    </div>

                    {{-- Buttons --}}
                    <div class="flex items-center gap-3 mt-5">
                        <a href="{{ asset('downloads/sms-gateway.apk') }}" download
                           class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3"/></svg>
                            Yuklab olish
                        </a>
                        <a href="{{ asset('downloads/sms-gateway.apk') }}" download
                           onclick="event.preventDefault(); shareToTelegram()"
                           class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#2AABEE] hover:bg-[#229ED9] text-white text-sm font-semibold rounded-xl transition-colors shadow-sm">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/></svg>
                            Telegramga yuborish
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════
         SECTION 2: Sozlash qadamlari
    ══════════════════════════════════════════════════════════ --}}
    <div class="mb-8">
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">Sozlash</p>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            {{-- Step 1 --}}
            <div class="bg-white rounded-2xl border border-slate-200 p-5 relative">
                <div class="absolute -top-2.5 left-5 px-2 py-0.5 bg-indigo-600 text-white text-[10px] font-bold rounded-md">1</div>
                <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center mb-3">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3"/></svg>
                </div>
                <p class="text-sm font-semibold text-slate-800">Ilovani o'rnating</p>
                <p class="text-xs text-slate-400 mt-1">Yuqoridagi tugma orqali yuklab oling va telefoningizga o'rnating</p>
            </div>

            {{-- Step 2 --}}
            <div class="bg-white rounded-2xl border border-slate-200 p-5 relative">
                <div class="absolute -top-2.5 left-5 px-2 py-0.5 bg-indigo-600 text-white text-[10px] font-bold rounded-md">2</div>
                <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center mb-3">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                </div>
                <p class="text-sm font-semibold text-slate-800">API kalit kiriting</p>
                <div class="mt-2" x-data="{ copied: false }">
                    <div class="flex items-center gap-1.5">
                        <code class="flex-1 text-[11px] font-mono bg-slate-50 text-slate-700 px-2.5 py-1.5 rounded-lg truncate select-all border border-slate-100">{{ auth()->user()->api_key }}</code>
                        <button type="button"
                                @click="navigator.clipboard.writeText('{{ auth()->user()->api_key }}'); copied = true; setTimeout(() => copied = false, 2000)"
                                :class="copied ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : 'bg-slate-50 text-slate-500 border-slate-200 hover:bg-indigo-50 hover:text-indigo-600'"
                                class="flex-shrink-0 px-2 py-1.5 text-[11px] font-medium rounded-lg border transition-all">
                            <span x-show="!copied">Nusxa</span>
                            <span x-show="copied" x-cloak>Tayyor!</span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Step 3 --}}
            <div class="bg-white rounded-2xl border border-emerald-200 bg-emerald-50/30 p-5 relative">
                <div class="absolute -top-2.5 left-5 px-2 py-0.5 bg-emerald-600 text-white text-[10px] font-bold rounded-md">3</div>
                <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center mb-3">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <p class="text-sm font-semibold text-slate-800">Tayyor!</p>
                <p class="text-xs text-slate-400 mt-1">Ilova serverga ulanadi va qurilma quyida ko'rinadi</p>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════
         SECTION 3: Ulangan qurilmalar
    ══════════════════════════════════════════════════════════ --}}
    <div>
        <div class="flex items-center justify-between mb-4">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Ulangan qurilmalar</p>
            @if($devices->count())
                <span class="text-xs text-slate-400">{{ $devices->count() }} ta qurilma</span>
            @endif
        </div>

        @if($devices->isEmpty())
            {{-- Empty state --}}
            <div class="bg-white rounded-2xl border border-dashed border-slate-300 py-16 text-center">
                <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                </div>
                <p class="text-sm font-medium text-slate-600">Hali qurilmalar ulanmagan</p>
                <p class="text-xs text-slate-400 mt-1">Yuqoridagi qadamlarni bajaring</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($devices as $device)
                <div class="bg-white rounded-2xl border {{ $device->isOnline() ? 'border-emerald-200' : 'border-slate-200' }} shadow-sm overflow-hidden hover:shadow-md transition-shadow">

                    {{-- Header strip --}}
                    <div class="px-5 py-3.5 flex items-center justify-between {{ $device->isOnline() ? 'bg-emerald-50/50' : 'bg-slate-50/50' }} border-b {{ $device->isOnline() ? 'border-emerald-100' : 'border-slate-100' }}">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="relative flex-shrink-0">
                                <div class="w-9 h-9 rounded-xl {{ $device->isOnline() ? 'bg-emerald-100' : 'bg-slate-100' }} flex items-center justify-center">
                                    <svg class="w-4.5 h-4.5 {{ $device->isOnline() ? 'text-emerald-600' : 'text-slate-400' }}" style="width:18px;height:18px" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                @if($device->isOnline())
                                    <span class="absolute -top-0.5 -right-0.5 w-3 h-3 bg-emerald-500 border-2 border-white rounded-full"></span>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-slate-900 truncate">{{ $device->name }}</p>
                                <p class="text-[11px] text-slate-400 truncate">{{ $device->model ?? 'Android' }}</p>
                            </div>
                        </div>
                        <x-status-badge :status="$device->isOnline() ? 'online' : 'offline'"/>
                    </div>

                    {{-- Body --}}
                    <div class="px-5 py-4 space-y-3">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <p class="text-[10px] text-slate-400 uppercase tracking-wider">Telefon</p>
                                <p class="text-xs font-medium text-slate-800 mt-0.5">{{ $device->phone_number ?? '—' }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-slate-400 uppercase tracking-wider">Operator</p>
                                <p class="text-xs font-medium text-slate-800 mt-0.5">{{ $device->operator ?? '—' }}</p>
                            </div>
                        </div>

                        {{-- Battery --}}
                        @if($device->battery_level !== null)
                            @php
                                $bat = $device->battery_level;
                                $batColor = $bat > 50 ? 'bg-emerald-500' : ($bat > 20 ? 'bg-amber-400' : 'bg-red-500');
                                $batIcon = $bat > 50 ? 'text-emerald-500' : ($bat > 20 ? 'text-amber-500' : 'text-red-500');
                            @endphp
                            <div class="flex items-center gap-3 bg-slate-50 rounded-xl px-3 py-2">
                                <svg class="w-4 h-4 {{ $batIcon }} flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M3 5a2 2 0 012-2h7a2 2 0 012 2v1h1a2 2 0 012 2v4a2 2 0 01-2 2h-1v1a2 2 0 01-2 2H5a2 2 0 01-2-2V5z"/>
                                </svg>
                                <div class="flex-1">
                                    <div class="w-full bg-slate-200 rounded-full h-1.5">
                                        <div class="{{ $batColor }} h-1.5 rounded-full transition-all" style="width: {{ $bat }}%"></div>
                                    </div>
                                </div>
                                <span class="text-xs font-semibold text-slate-700 tabular-nums">{{ $bat }}%</span>
                            </div>
                        @endif

                        {{-- Last seen --}}
                        <p class="text-[11px] text-slate-400">
                            So'nggi: <span class="text-slate-600 font-medium">{{ $device->last_seen_at?->diffForHumans() ?? '—' }}</span>
                        </p>
                    </div>

                    {{-- Actions --}}
                    <div class="px-5 py-3 bg-slate-50/50 border-t border-slate-100 flex items-center gap-2">
                        <form method="POST" action="{{ route('devices.toggle', $device) }}" class="flex-1">
                            @csrf
                            <button type="submit" class="w-full py-1.5 rounded-lg text-xs font-medium transition-colors {{ $device->is_active ? 'text-amber-700 hover:bg-amber-100' : 'text-emerald-700 hover:bg-emerald-100' }}">
                                {{ $device->is_active ? 'O\'chirish' : 'Yoqish' }}
                            </button>
                        </form>
                        <form method="POST" action="{{ route('devices.destroy', $device) }}" onsubmit="return confirm('Qurilmani o\'chirishga ishonchingiz komilmi?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-medium text-red-600 hover:bg-red-100 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Telegram share: APK faylni to'g'ridan-to'g'ri yuborish --}}
    <script>
        function shareToTelegram() {
            // APK faylni fetch qilib blob sifatida olish
            const apkUrl = '{{ asset("downloads/sms-gateway.apk") }}';

            // Web Share API bilan fayl yuborish (Android Chrome)
            if (navigator.share && navigator.canShare) {
                fetch(apkUrl)
                    .then(res => res.blob())
                    .then(blob => {
                        const file = new File([blob], 'sms-gateway.apk', { type: 'application/vnd.android.package-archive' });
                        if (navigator.canShare({ files: [file] })) {
                            navigator.share({
                                title: 'SMS Gateway',
                                text: 'SMS Gateway ilovasini yuklab oling',
                                files: [file]
                            });
                        } else {
                            // Fallback: to'g'ridan-to'g'ri yuklab olish
                            window.location.href = apkUrl;
                        }
                    })
                    .catch(() => {
                        window.location.href = apkUrl;
                    });
            } else {
                // Desktop: faylni yuklab olish
                window.location.href = apkUrl;
            }
        }
    </script>
</x-dashboard-layout>
