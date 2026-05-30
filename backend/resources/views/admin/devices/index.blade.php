<x-admin-layout title="Barcha qurilmalar">

    <!-- Filter bar -->
    <div class="bg-white rounded-xl border border-slate-200/70 p-4 mb-5">
        <form method="GET" action="{{ route('admin.devices.index') }}" class="flex flex-wrap items-center gap-3">
            <div class="flex-1 min-w-[240px] relative">
                <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Qurilma, foydalanuvchi, telefon..." class="w-full pl-9 pr-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500">
            </div>
            <select name="status" class="px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500">
                <option value="">Barcha status</option>
                <option value="online" @selected(request('status') === 'online')>Online</option>
                <option value="offline" @selected(request('status') === 'offline')>Offline</option>
            </select>
            <button type="submit" class="px-4 py-2 text-sm font-medium bg-rose-600 text-white rounded-lg hover:bg-rose-700 shadow-sm shadow-rose-600/20 transition-colors">
                Filterlash
            </button>
            @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('admin.devices.index') }}" class="px-3 py-2 text-sm font-medium text-slate-600 hover:text-slate-900">Tozalash</a>
            @endif
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-slate-200/70 overflow-hidden">
        @if($devices->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 border-b border-slate-200/70">
                        <tr class="text-left text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                            <th class="px-5 py-3">Qurilma</th>
                            <th class="px-5 py-3">Foydalanuvchi</th>
                            <th class="px-5 py-3">Telefon</th>
                            <th class="px-5 py-3">Operator</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3">Batareya</th>
                            <th class="px-5 py-3">So'nggi faollik</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($devices as $device)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-lg bg-slate-100 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5"/></svg>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-medium text-slate-900 truncate">{{ $device->name ?? 'Qurilma' }}</p>
                                            <p class="text-xs text-slate-500 truncate">{{ $device->model ?? '—' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5">
                                    @if($device->user)
                                        <a href="{{ route('admin.users.show', $device->user) }}" class="group">
                                            <p class="text-sm font-medium text-slate-900 group-hover:text-rose-600 transition-colors">{{ $device->user->name }}</p>
                                            <p class="text-xs text-slate-500">{{ $device->user->email }}</p>
                                        </a>
                                    @else
                                        <span class="text-xs text-slate-400">—</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-slate-700 tabular-nums">{{ $device->phone_number ?? '—' }}</td>
                                <td class="px-5 py-3.5 text-slate-600">{{ $device->operator ?? '—' }}</td>
                                <td class="px-5 py-3.5">
                                    <x-status-badge :status="$device->status ?? 'offline'"/>
                                </td>
                                <td class="px-5 py-3.5">
                                    @php $battery = $device->battery_level ?? null; @endphp
                                    @if(!is_null($battery))
                                        <div class="flex items-center gap-2 min-w-[100px]">
                                            <div class="flex-1 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                                @php
                                                    $color = $battery > 50 ? 'bg-emerald-500' : ($battery > 20 ? 'bg-amber-500' : 'bg-red-500');
                                                @endphp
                                                <div class="h-full {{ $color }} rounded-full" style="width: {{ $battery }}%"></div>
                                            </div>
                                            <span class="text-xs text-slate-600 tabular-nums w-9 text-right">{{ $battery }}%</span>
                                        </div>
                                    @else
                                        <span class="text-xs text-slate-400">—</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-xs text-slate-500 whitespace-nowrap">
                                    {{ $device->last_seen_at ? \Carbon\Carbon::parse($device->last_seen_at)->diffForHumans() : '—' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-5 py-3 border-t border-slate-100">
                {{ $devices->withQueryString()->links() }}
            </div>
        @else
            <div class="py-16 text-center">
                <div class="w-14 h-14 mx-auto rounded-2xl bg-slate-100 flex items-center justify-center mb-3">
                    <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5"/></svg>
                </div>
                <p class="text-sm font-medium text-slate-900">Qurilmalar topilmadi</p>
                <p class="text-xs text-slate-500 mt-1">Filterni o'zgartiring yoki tozalang</p>
            </div>
        @endif
    </div>

</x-admin-layout>
