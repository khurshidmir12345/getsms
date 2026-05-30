@php
    $maxChart = collect($chartData ?? [])->max('count') ?: 1;
@endphp
<x-admin-layout title="Admin Dashboard">

    <!-- 4 Big Stat Cards -->
    <div class="grid grid-cols-4 gap-4 mb-6">
        <!-- Foydalanuvchilar -->
        <div class="bg-white rounded-2xl border border-slate-200/70 p-5">
            <div class="flex items-start justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-rose-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                </div>
                @if(($stats['users_new_today'] ?? 0) > 0)
                    <span class="text-[10px] font-semibold bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded-full">+{{ $stats['users_new_today'] }} bugun</span>
                @endif
            </div>
            <p class="text-xs font-medium text-slate-500 uppercase tracking-wide mb-1">Foydalanuvchilar</p>
            <p class="text-3xl font-bold text-slate-900">{{ number_format($stats['users_total'] ?? 0) }}</p>
            <p class="text-xs text-slate-500 mt-1">{{ $stats['users_active'] ?? 0 }} faol</p>
        </div>

        <!-- Qurilmalar -->
        <div class="bg-white rounded-2xl border border-slate-200/70 p-5">
            <div class="flex items-start justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-rose-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3"/></svg>
                </div>
            </div>
            <p class="text-xs font-medium text-slate-500 uppercase tracking-wide mb-1">Qurilmalar</p>
            <p class="text-3xl font-bold text-slate-900">{{ number_format($stats['devices_total'] ?? 0) }}</p>
            <p class="text-xs text-emerald-600 mt-1 flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                {{ $stats['devices_online'] ?? 0 }} online
            </p>
        </div>

        <!-- SMS jami -->
        <div class="bg-white rounded-2xl border border-slate-200/70 p-5">
            <div class="flex items-start justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-rose-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                </div>
            </div>
            <p class="text-xs font-medium text-slate-500 uppercase tracking-wide mb-1">SMS jami</p>
            <p class="text-3xl font-bold text-slate-900">{{ number_format($stats['messages_total'] ?? 0) }}</p>
            <p class="text-xs text-slate-500 mt-1">Barcha vaqt</p>
        </div>

        <!-- SMS bugun -->
        <div class="bg-white rounded-2xl border border-slate-200/70 p-5">
            <div class="flex items-start justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-rose-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-xs font-medium text-slate-500 uppercase tracking-wide mb-1">SMS bugun</p>
            <p class="text-3xl font-bold text-slate-900">{{ number_format($stats['messages_today'] ?? 0) }}</p>
            <p class="text-xs text-slate-500 mt-1">So'nggi 24 soat</p>
        </div>
    </div>

    <!-- 3 small status cards -->
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-emerald-200/70 p-4 flex items-center gap-4">
            <div class="w-11 h-11 rounded-lg bg-emerald-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Yetkazilgan</p>
                <p class="text-2xl font-bold text-emerald-700">{{ number_format($stats['messages_delivered'] ?? 0) }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-red-200/70 p-4 flex items-center gap-4">
            <div class="w-11 h-11 rounded-lg bg-red-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Xato</p>
                <p class="text-2xl font-bold text-red-700">{{ number_format($stats['messages_failed'] ?? 0) }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-amber-200/70 p-4 flex items-center gap-4">
            <div class="w-11 h-11 rounded-lg bg-amber-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Kutilmoqda</p>
                <p class="text-2xl font-bold text-amber-700">{{ number_format($stats['messages_pending'] ?? 0) }}</p>
            </div>
        </div>
    </div>

    <!-- Two columns: Top users & Recent users -->
    <div class="grid grid-cols-2 gap-4 mb-6">
        <!-- Top Users -->
        <div class="bg-white rounded-2xl border border-slate-200/70 overflow-hidden">
            <div class="px-5 py-3.5 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-slate-900">Eng faol userlar</h3>
                <a href="{{ route('admin.users.index') }}" class="text-xs font-medium text-rose-600 hover:text-rose-700">Barchasi →</a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($topUsers ?? [] as $u)
                    <a href="{{ route('admin.users.show', $u) }}" class="flex items-center gap-3 px-5 py-3 hover:bg-slate-50 transition-colors">
                        <div class="w-8 h-8 rounded-lg bg-rose-100 flex items-center justify-center text-xs font-semibold text-rose-700">
                            {{ mb_substr($u->name, 0, 1) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-slate-900 truncate">{{ $u->name }}</p>
                            <p class="text-xs text-slate-500 truncate">{{ $u->email }}</p>
                        </div>
                        <span class="text-sm font-semibold text-slate-700">{{ number_format($u->messages_count) }}</span>
                    </a>
                @empty
                    <div class="px-5 py-8 text-center text-sm text-slate-400">Ma'lumot yo'q</div>
                @endforelse
            </div>
        </div>

        <!-- Recent Users -->
        <div class="bg-white rounded-2xl border border-slate-200/70 overflow-hidden">
            <div class="px-5 py-3.5 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-slate-900">Yangi userlar</h3>
                <a href="{{ route('admin.users.index') }}" class="text-xs font-medium text-rose-600 hover:text-rose-700">Barchasi →</a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($recentUsers ?? [] as $u)
                    <a href="{{ route('admin.users.show', $u) }}" class="flex items-center gap-3 px-5 py-3 hover:bg-slate-50 transition-colors">
                        <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-xs font-semibold text-slate-700">
                            {{ mb_substr($u->name, 0, 1) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-slate-900 truncate">{{ $u->name }}</p>
                            <p class="text-xs text-slate-500 truncate">{{ $u->email }}</p>
                        </div>
                        <span class="text-xs text-slate-400">{{ $u->created_at->diffForHumans() }}</span>
                    </a>
                @empty
                    <div class="px-5 py-8 text-center text-sm text-slate-400">Ma'lumot yo'q</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Chart: Last 7 days -->
    <div class="bg-white rounded-2xl border border-slate-200/70 overflow-hidden">
        <div class="px-5 py-3.5 border-b border-slate-100">
            <h3 class="text-sm font-semibold text-slate-900">So'nggi 7 kun</h3>
            <p class="text-xs text-slate-500 mt-0.5">Kunlik xabarlar statistikasi</p>
        </div>
        <div class="p-5 space-y-2.5">
            @forelse($chartData ?? [] as $row)
                @php
                    $pct = $maxChart > 0 ? round(($row['count'] / $maxChart) * 100) : 0;
                @endphp
                <div class="flex items-center gap-3">
                    <span class="w-20 text-xs font-medium text-slate-500 flex-shrink-0">{{ $row['date'] }}</span>
                    <div class="flex-1 h-7 bg-slate-100 rounded-md overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-rose-500 to-rose-600 rounded-md transition-all" style="width: {{ $pct }}%"></div>
                    </div>
                    <span class="w-16 text-right text-sm font-semibold text-slate-900 tabular-nums">{{ number_format($row['count']) }}</span>
                </div>
            @empty
                <p class="text-center text-sm text-slate-400 py-6">Ma'lumot yo'q</p>
            @endforelse
        </div>
    </div>

</x-admin-layout>
