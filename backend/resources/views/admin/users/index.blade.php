<x-admin-layout title="Foydalanuvchilar">

    <!-- Filter bar -->
    <div class="bg-white rounded-xl border border-slate-200/70 p-4 mb-5">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap items-center gap-3">
            <div class="flex-1 min-w-[240px] relative">
                <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Ism, email yoki telefon..." class="w-full pl-9 pr-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500">
            </div>
            <select name="status" class="px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500">
                <option value="">Barcha status</option>
                <option value="active" @selected(request('status') === 'active')>Faol</option>
                <option value="inactive" @selected(request('status') === 'inactive')>Nofaol</option>
            </select>
            <select name="role" class="px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500">
                <option value="">Barcha rollar</option>
                <option value="user" @selected(request('role') === 'user')>Foydalanuvchi</option>
                <option value="super_admin" @selected(request('role') === 'super_admin')>Super Admin</option>
            </select>
            <button type="submit" class="px-4 py-2 text-sm font-medium bg-rose-600 text-white rounded-lg hover:bg-rose-700 shadow-sm shadow-rose-600/20 transition-colors">
                Filterlash
            </button>
            @if(request()->hasAny(['search', 'status', 'role']))
                <a href="{{ route('admin.users.index') }}" class="px-3 py-2 text-sm font-medium text-slate-600 hover:text-slate-900">Tozalash</a>
            @endif
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-slate-200/70 overflow-hidden">
        @if($users->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 border-b border-slate-200/70">
                        <tr class="text-left text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                            <th class="px-5 py-3">Foydalanuvchi</th>
                            <th class="px-5 py-3">Telefon</th>
                            <th class="px-5 py-3">SMS</th>
                            <th class="px-5 py-3">Qurilmalar</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3">Role</th>
                            <th class="px-5 py-3">Sana</th>
                            <th class="px-5 py-3 text-right">Amallar</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($users as $user)
                            @php
                                $pct = ($user->sms_limit ?? 0) > 0 ? min(($user->sms_used / $user->sms_limit) * 100, 100) : 0;
                            @endphp
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-5 py-3.5">
                                    <a href="{{ route('admin.users.show', $user) }}" class="flex items-center gap-3 group">
                                        <div class="w-9 h-9 rounded-lg bg-rose-100 flex items-center justify-center text-sm font-semibold text-rose-700 flex-shrink-0">
                                            {{ mb_substr($user->name, 0, 1) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-medium text-slate-900 group-hover:text-rose-600 transition-colors truncate">{{ $user->name }}</p>
                                            <p class="text-xs text-slate-500 truncate">{{ $user->email }}</p>
                                        </div>
                                    </a>
                                </td>
                                <td class="px-5 py-3.5 text-slate-700 tabular-nums">{{ $user->phone ?: '—' }}</td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-2 min-w-[140px]">
                                        <div class="flex-1 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                            <div class="h-full bg-rose-500 rounded-full" style="width: {{ $pct }}%"></div>
                                        </div>
                                        <span class="text-xs text-slate-600 tabular-nums whitespace-nowrap">{{ number_format($user->sms_used) }}/{{ number_format($user->sms_limit) }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5 text-slate-700 tabular-nums">{{ $user->devices_count ?? 0 }}</td>
                                <td class="px-5 py-3.5">
                                    @if($user->is_active)
                                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>Faol
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-600">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>Nofaol
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5">
                                    @if($user->role === 'super_admin')
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200/60">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 1.944A11.954 11.954 0 012.166 5C2.056 5.649 2 6.319 2 7c0 5.225 3.34 9.67 8 11.317C14.66 16.67 18 12.225 18 7c0-.682-.057-1.35-.166-2.001A11.954 11.954 0 0110 1.944z" clip-rule="evenodd"/></svg>
                                            Admin
                                        </span>
                                    @else
                                        <span class="text-xs text-slate-600">Foydalanuvchi</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-xs text-slate-500 whitespace-nowrap">{{ $user->created_at->format('d.m.Y') }}</td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-2 justify-end">
                                        <a href="{{ route('admin.users.show', $user) }}" class="text-xs font-medium text-rose-600 hover:text-rose-700">Ko'rish</a>
                                        @if($user->id !== auth()->id())
                                            <form method="POST" action="{{ route('admin.users.impersonate', $user) }}" onsubmit="return confirm('Ushbu foydalanuvchi sifatida kirishni xohlaysizmi?');">
                                                @csrf
                                                <button type="submit" class="text-xs font-medium text-slate-600 hover:text-rose-600 px-2 py-1 rounded border border-slate-200 hover:border-rose-300 hover:bg-rose-50 transition-colors">
                                                    Impersonate
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-5 py-3 border-t border-slate-100">
                {{ $users->withQueryString()->links() }}
            </div>
        @else
            <div class="py-16 text-center">
                <div class="w-14 h-14 mx-auto rounded-2xl bg-slate-100 flex items-center justify-center mb-3">
                    <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0z"/></svg>
                </div>
                <p class="text-sm font-medium text-slate-900">Foydalanuvchilar topilmadi</p>
                <p class="text-xs text-slate-500 mt-1">Filterni o'zgartiring yoki tozalang</p>
            </div>
        @endif
    </div>

</x-admin-layout>
