<x-admin-layout title="Barcha SMS lar">

    <!-- Filter bar -->
    <div class="bg-white rounded-xl border border-slate-200/70 p-4 mb-5">
        <form method="GET" action="{{ route('admin.messages.index') }}" class="flex flex-wrap items-center gap-3">
            <div class="flex-1 min-w-[240px] relative">
                <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Telefon, matn, foydalanuvchi..." class="w-full pl-9 pr-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500">
            </div>
            <select name="status" class="px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500">
                <option value="">Barcha status</option>
                <option value="pending" @selected(request('status') === 'pending')>Kutilmoqda</option>
                <option value="queued" @selected(request('status') === 'queued')>Navbatda</option>
                <option value="sent" @selected(request('status') === 'sent')>Yuborildi</option>
                <option value="delivered" @selected(request('status') === 'delivered')>Yetkazildi</option>
                <option value="failed" @selected(request('status') === 'failed')>Xato</option>
            </select>
            <select name="direction" class="px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500">
                <option value="">Barcha yo'nalish</option>
                <option value="outgoing" @selected(request('direction') === 'outgoing')>Chiquvchi</option>
                <option value="incoming" @selected(request('direction') === 'incoming')>Kiruvchi</option>
            </select>
            <button type="submit" class="px-4 py-2 text-sm font-medium bg-rose-600 text-white rounded-lg hover:bg-rose-700 shadow-sm shadow-rose-600/20 transition-colors">
                Filterlash
            </button>
            @if(request()->hasAny(['search', 'status', 'direction']))
                <a href="{{ route('admin.messages.index') }}" class="px-3 py-2 text-sm font-medium text-slate-600 hover:text-slate-900">Tozalash</a>
            @endif
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-slate-200/70 overflow-hidden">
        @if($messages->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 border-b border-slate-200/70">
                        <tr class="text-left text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                            <th class="px-5 py-3">#ID</th>
                            <th class="px-5 py-3">Foydalanuvchi</th>
                            <th class="px-5 py-3">Kimdan → Kimga</th>
                            <th class="px-5 py-3">Xabar</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3">Yo'nalish</th>
                            <th class="px-5 py-3">Sana</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($messages as $msg)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-5 py-3.5 text-xs text-slate-500 font-mono tabular-nums">#{{ $msg->id }}</td>
                                <td class="px-5 py-3.5">
                                    @if($msg->user)
                                        <a href="{{ route('admin.users.show', $msg->user) }}" class="group">
                                            <p class="text-sm font-medium text-slate-900 group-hover:text-rose-600 transition-colors truncate">{{ $msg->user->name }}</p>
                                            <p class="text-xs text-slate-500 truncate">{{ $msg->user->email }}</p>
                                        </a>
                                    @else
                                        <span class="text-xs text-slate-400">—</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-xs text-slate-700 tabular-nums whitespace-nowrap">
                                    <span>{{ $msg->phone_from ?? '—' }}</span>
                                    <span class="text-slate-400 mx-1">→</span>
                                    <span>{{ $msg->phone_to ?? '—' }}</span>
                                </td>
                                <td class="px-5 py-3.5">
                                    <p class="text-xs text-slate-700 max-w-xs truncate">{{ $msg->message ?? $msg->body ?? '' }}</p>
                                </td>
                                <td class="px-5 py-3.5">
                                    <x-status-badge :status="$msg->status ?? 'pending'"/>
                                </td>
                                <td class="px-5 py-3.5">
                                    @if(($msg->direction ?? 'outgoing') === 'incoming')
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-xs font-medium bg-blue-50 text-blue-700">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                                            Kiruvchi
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-xs font-medium bg-rose-50 text-rose-700">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                                            Chiquvchi
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-xs text-slate-500 whitespace-nowrap">{{ $msg->created_at->format('d.m.Y H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-5 py-3 border-t border-slate-100">
                {{ $messages->withQueryString()->links() }}
            </div>
        @else
            <div class="py-16 text-center">
                <div class="w-14 h-14 mx-auto rounded-2xl bg-slate-100 flex items-center justify-center mb-3">
                    <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                </div>
                <p class="text-sm font-medium text-slate-900">Xabarlar topilmadi</p>
                <p class="text-xs text-slate-500 mt-1">Filterni o'zgartiring yoki tozalang</p>
            </div>
        @endif
    </div>

</x-admin-layout>
