<x-dashboard-layout title="Dashboard">

    {{-- ── Stat Cards ───────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">

        {{-- Bugungi SMS --}}
        <div class="bg-white border border-slate-200/60 rounded-2xl p-5 flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-500">Bugungi SMS</p>
                <p class="text-2xl font-bold text-slate-800 mt-1">{{ $stats['sent_today'] }}</p>
                <p class="text-xs text-slate-400 mt-1">Bugun yuborilgan</p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
        </div>

        {{-- Yetkazilgan --}}
        <div class="bg-white border border-slate-200/60 rounded-2xl p-5 flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-500">Yetkazilgan</p>
                <p class="text-2xl font-bold text-slate-800 mt-1">{{ $stats['delivered'] }}</p>
                <p class="text-xs text-emerald-500 mt-1">Muvaffaqiyatli</p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-emerald-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>

        {{-- Qurilmalar --}}
        <div class="bg-white border border-slate-200/60 rounded-2xl p-5 flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-500">Qurilmalar</p>
                <p class="text-2xl font-bold text-slate-800 mt-1">
                    <span class="text-emerald-500">{{ $stats['devices_online'] }}</span><span class="text-slate-400 text-lg font-medium"> / {{ $stats['devices_total'] }}</span>
                </p>
                <p class="text-xs text-slate-400 mt-1">Online / Jami</p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-violet-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
            </div>
        </div>

        {{-- SMS Limit --}}
        <div class="bg-white border border-slate-200/60 rounded-2xl p-5">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <p class="text-sm text-slate-500">SMS Limit</p>
                    <p class="text-2xl font-bold text-slate-800 mt-1">
                        {{ $stats['sms_used'] }}<span class="text-lg font-medium text-slate-400">/{{ $stats['sms_limit'] }}</span>
                    </p>
                </div>
                <div class="w-11 h-11 rounded-xl bg-amber-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
            </div>
            @php
                $pct = $stats['sms_limit'] > 0 ? min(($stats['sms_used'] / $stats['sms_limit']) * 100, 100) : 0;
                $barColor = $pct >= 90 ? 'bg-red-500' : ($pct >= 70 ? 'bg-amber-500' : 'bg-indigo-600');
            @endphp
            <div class="w-full bg-slate-100 rounded-full h-1.5">
                <div class="{{ $barColor }} h-1.5 rounded-full transition-all" style="width: {{ $pct }}%"></div>
            </div>
            <p class="text-xs text-slate-400 mt-1.5">{{ number_format($pct, 0) }}% ishlatilgan</p>
        </div>

    </div>

    {{-- ── Quick Actions ─────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">

        <a href="{{ route('messages.create') }}"
           class="group bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-2xl p-5 flex items-center gap-4 hover:shadow-lg transition-all duration-200">
            <div class="w-11 h-11 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
            </div>
            <div>
                <p class="text-white font-semibold">SMS Yuborish</p>
                <p class="text-indigo-200 text-xs mt-0.5">Yangi xabar yarating</p>
            </div>
        </a>

        <a href="{{ route('contacts.index') }}"
           class="group bg-gradient-to-br from-emerald-500 to-emerald-700 rounded-2xl p-5 flex items-center gap-4 hover:shadow-lg transition-all duration-200">
            <div class="w-11 h-11 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
            </div>
            <div>
                <p class="text-white font-semibold">Kontakt qo'shish</p>
                <p class="text-emerald-200 text-xs mt-0.5">Raqamlarni boshqaring</p>
            </div>
        </a>

        <a href="{{ route('devices.index') }}"
           class="group bg-gradient-to-br from-violet-500 to-violet-700 rounded-2xl p-5 flex items-center gap-4 hover:shadow-lg transition-all duration-200">
            <div class="w-11 h-11 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <p class="text-white font-semibold">Qurilma ulash</p>
                <p class="text-violet-200 text-xs mt-0.5">Android telefonni bog'lang</p>
            </div>
        </a>

    </div>

    {{-- ── Recent Messages ───────────────────────────────────────── --}}
    <div class="bg-white border border-slate-200/60 rounded-2xl overflow-hidden">

        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-base font-semibold text-slate-800">So'nggi xabarlar</h3>
            <a href="{{ route('messages.index') }}"
               class="text-sm text-indigo-600 hover:text-indigo-800 font-medium transition-colors">
                Hammasini ko'rish →
            </a>
        </div>

        @if ($recentMessages->isEmpty())
            <div class="py-16 flex flex-col items-center gap-3 text-center">
                <div class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <p class="text-slate-500 font-medium">Hali xabarlar yo'q</p>
                <p class="text-slate-400 text-sm">Birinchi SMS yuborib ko'ring</p>
                <a href="{{ route('messages.create') }}"
                   class="mt-2 inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-xl hover:bg-indigo-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    SMS yuborish
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b border-slate-100">
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wide">Raqam</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wide">Xabar</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wide">Holat</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wide">Vaqt</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach ($recentMessages->take(5) as $message)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-6 py-3.5 whitespace-nowrap text-sm font-medium text-slate-700">
                                {{ $message->direction === 'outgoing' ? $message->phone_to : $message->phone_from }}
                            </td>
                            <td class="px-6 py-3.5 text-sm text-slate-500 max-w-xs">
                                <span class="block truncate max-w-[240px]">{{ $message->body }}</span>
                            </td>
                            <td class="px-6 py-3.5 whitespace-nowrap">
                                <x-status-badge :status="$message->status"/>
                            </td>
                            <td class="px-6 py-3.5 whitespace-nowrap text-sm text-slate-400">
                                {{ $message->created_at->diffForHumans() }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

    </div>

</x-dashboard-layout>
