<x-admin-layout :title="'Foydalanuvchi: ' . $user->name">

    <div x-data="{ editMode: false }">

        <!-- Header Card -->
        <div class="bg-white rounded-2xl border border-slate-200/70 p-6 mb-5">
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-rose-500 to-rose-600 flex items-center justify-center text-2xl font-bold text-white shadow-lg shadow-rose-600/30">
                        {{ mb_substr($user->name, 0, 1) }}
                    </div>
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <h2 class="text-xl font-bold text-slate-900">{{ $user->name }}</h2>
                            @if($user->role === 'super_admin')
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200/60">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 1.944A11.954 11.954 0 012.166 5C2.056 5.649 2 6.319 2 7c0 5.225 3.34 9.67 8 11.317C14.66 16.67 18 12.225 18 7z" clip-rule="evenodd"/></svg>
                                    Super Admin
                                </span>
                            @endif
                            @if($user->is_active)
                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>Faol
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-600">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>Nofaol
                                </span>
                            @endif
                        </div>
                        <p class="text-sm text-slate-600">{{ $user->email }}</p>
                        @if($user->phone)
                            <p class="text-sm text-slate-500 mt-0.5">{{ $user->phone }}</p>
                        @endif
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    @if($user->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.users.impersonate', $user) }}" onsubmit="return confirm('Ushbu foydalanuvchi sifatida kirishni xohlaysizmi?');">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-rose-600 text-white text-sm font-semibold rounded-lg hover:bg-rose-700 shadow-md shadow-rose-600/30 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                                Impersonate
                            </button>
                        </form>
                    @endif
                    <button @click="editMode = !editMode" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 text-slate-700 text-sm font-medium rounded-lg hover:bg-slate-50 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                        <span x-text="editMode ? 'Yopish' : 'Tahrirlash'"></span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Stats Grid (5 cards) -->
        <div class="grid grid-cols-5 gap-3 mb-5">
            <div class="bg-white rounded-xl border border-slate-200/70 p-4">
                <p class="text-[11px] font-medium text-slate-500 uppercase tracking-wide">Qurilmalar</p>
                <p class="text-2xl font-bold text-slate-900 mt-1">{{ $user->devices_count ?? 0 }}</p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200/70 p-4">
                <p class="text-[11px] font-medium text-slate-500 uppercase tracking-wide">Kontaktlar</p>
                <p class="text-2xl font-bold text-slate-900 mt-1">{{ $user->contacts_count ?? 0 }}</p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200/70 p-4">
                <p class="text-[11px] font-medium text-slate-500 uppercase tracking-wide">Shablonlar</p>
                <p class="text-2xl font-bold text-slate-900 mt-1">{{ $user->templates_count ?? 0 }}</p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200/70 p-4">
                <p class="text-[11px] font-medium text-slate-500 uppercase tracking-wide">Kampaniyalar</p>
                <p class="text-2xl font-bold text-slate-900 mt-1">{{ $user->campaigns_count ?? 0 }}</p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200/70 p-4">
                <p class="text-[11px] font-medium text-slate-500 uppercase tracking-wide">Xabarlar</p>
                <p class="text-2xl font-bold text-slate-900 mt-1">{{ $user->messages_count ?? 0 }}</p>
            </div>
        </div>

        <!-- SMS stats row -->
        <div class="grid grid-cols-3 gap-3 mb-5">
            <div class="bg-white rounded-xl border border-emerald-200/70 p-4 flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-emerald-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                </div>
                <div>
                    <p class="text-[11px] font-medium text-slate-500 uppercase tracking-wide">Yetkazilgan</p>
                    <p class="text-xl font-bold text-emerald-700">{{ number_format($stats['delivered'] ?? 0) }}</p>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-red-200/70 p-4 flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-red-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </div>
                <div>
                    <p class="text-[11px] font-medium text-slate-500 uppercase tracking-wide">Xato</p>
                    <p class="text-xl font-bold text-red-700">{{ number_format($stats['failed'] ?? 0) }}</p>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-amber-200/70 p-4 flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-[11px] font-medium text-slate-500 uppercase tracking-wide">Kutilmoqda</p>
                    <p class="text-xl font-bold text-amber-700">{{ number_format($stats['pending'] ?? 0) }}</p>
                </div>
            </div>
        </div>

        <!-- Two columns -->
        <div class="grid grid-cols-3 gap-5 mb-5">

            <!-- Left: Edit Form / View details -->
            <div class="col-span-2">

                <!-- Edit Mode -->
                <div x-show="editMode" x-cloak class="bg-white rounded-2xl border border-rose-200/70 overflow-hidden">
                    <div class="px-5 py-3.5 border-b border-slate-100 bg-rose-50/40">
                        <h3 class="text-sm font-semibold text-slate-900">Foydalanuvchini tahrirlash</h3>
                    </div>
                    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="p-5 space-y-4">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Ism</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Email</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Telefon</label>
                                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">SMS limit</label>
                                <input type="number" name="sms_limit" value="{{ old('sms_limit', $user->sms_limit) }}" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Role</label>
                                <select name="role" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500">
                                    <option value="user" @selected($user->role === 'user')>Foydalanuvchi</option>
                                    <option value="super_admin" @selected($user->role === 'super_admin')>Super Admin</option>
                                </select>
                            </div>
                            <div class="flex items-end">
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="hidden" name="is_active" value="0">
                                    <input type="checkbox" name="is_active" value="1" {{ $user->is_active ? 'checked' : '' }} class="w-4 h-4 rounded border-slate-300 text-rose-600 focus:ring-rose-500/20">
                                    <span class="text-sm font-medium text-slate-700">Faol foydalanuvchi</span>
                                </label>
                            </div>
                        </div>
                        <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                            <button type="button" @click="editMode = false" class="px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100 rounded-lg transition-colors">Bekor qilish</button>
                            <button type="submit" class="px-4 py-2 text-sm font-semibold bg-rose-600 text-white rounded-lg hover:bg-rose-700 shadow-sm shadow-rose-600/20 transition-colors">Saqlash</button>
                        </div>
                    </form>
                </div>

                <!-- View mode -->
                <div x-show="!editMode" class="bg-white rounded-2xl border border-slate-200/70 overflow-hidden">
                    <div class="px-5 py-3.5 border-b border-slate-100">
                        <h3 class="text-sm font-semibold text-slate-900">Tafsilotlar</h3>
                    </div>
                    <dl class="p-5 grid grid-cols-2 gap-x-6 gap-y-4">
                        <div>
                            <dt class="text-[11px] font-medium text-slate-500 uppercase tracking-wide">Ism</dt>
                            <dd class="text-sm text-slate-900 mt-0.5">{{ $user->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-[11px] font-medium text-slate-500 uppercase tracking-wide">Email</dt>
                            <dd class="text-sm text-slate-900 mt-0.5">{{ $user->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-[11px] font-medium text-slate-500 uppercase tracking-wide">Telefon</dt>
                            <dd class="text-sm text-slate-900 mt-0.5">{{ $user->phone ?: '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-[11px] font-medium text-slate-500 uppercase tracking-wide">SMS limit</dt>
                            <dd class="text-sm text-slate-900 mt-0.5 tabular-nums">{{ number_format($user->sms_used) }} / {{ number_format($user->sms_limit) }}</dd>
                        </div>
                        <div class="col-span-2">
                            <dt class="text-[11px] font-medium text-slate-500 uppercase tracking-wide">API Key</dt>
                            <dd class="mt-1">
                                <code class="block px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-700 font-mono break-all">{{ $user->api_key ?? '—' }}</code>
                            </dd>
                        </div>
                        <div class="col-span-2">
                            <dt class="text-[11px] font-medium text-slate-500 uppercase tracking-wide">Webhook URL</dt>
                            <dd class="mt-1">
                                <code class="block px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-700 font-mono break-all">{{ $user->webhook_url ?: '—' }}</code>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-[11px] font-medium text-slate-500 uppercase tracking-wide">Ro'yxatdan o'tgan</dt>
                            <dd class="text-sm text-slate-900 mt-0.5">{{ $user->created_at->format('d.m.Y H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-[11px] font-medium text-slate-500 uppercase tracking-wide">So'nggi faollik</dt>
                            <dd class="text-sm text-slate-900 mt-0.5">{{ $user->last_seen_at ? \Carbon\Carbon::parse($user->last_seen_at)->diffForHumans() : '—' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Right: Devices -->
            <div class="bg-white rounded-2xl border border-slate-200/70 overflow-hidden h-fit">
                <div class="px-5 py-3.5 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-slate-900">Qurilmalar</h3>
                    <span class="text-xs text-slate-500">{{ count($devices ?? []) }} ta</span>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse($devices ?? [] as $device)
                        <div class="px-5 py-3">
                            <div class="flex items-center justify-between mb-1">
                                <p class="text-sm font-medium text-slate-900 truncate">{{ $device->name ?? $device->model ?? 'Qurilma' }}</p>
                                <x-status-badge :status="$device->status ?? 'offline'"/>
                            </div>
                            <p class="text-xs text-slate-500">{{ $device->phone_number ?? $device->model ?? '—' }}</p>
                        </div>
                    @empty
                        <div class="px-5 py-8 text-center text-sm text-slate-400">Qurilmalar yo'q</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Messages -->
        <div class="bg-white rounded-2xl border border-slate-200/70 overflow-hidden mb-5">
            <div class="px-5 py-3.5 border-b border-slate-100">
                <h3 class="text-sm font-semibold text-slate-900">So'nggi xabarlar</h3>
            </div>
            @if(count($recentMessages ?? []) > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50 border-b border-slate-200/70">
                            <tr class="text-left text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                                <th class="px-5 py-2.5">Kimdan → Kimga</th>
                                <th class="px-5 py-2.5">Xabar</th>
                                <th class="px-5 py-2.5">Status</th>
                                <th class="px-5 py-2.5">Vaqt</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($recentMessages as $msg)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-5 py-3 text-xs text-slate-700 tabular-nums">
                                        <span>{{ $msg->phone_from ?? '—' }}</span>
                                        <span class="text-slate-400 mx-1">→</span>
                                        <span>{{ $msg->phone_to ?? '—' }}</span>
                                    </td>
                                    <td class="px-5 py-3 text-xs text-slate-600 max-w-xs truncate">{{ $msg->message ?? $msg->body ?? '' }}</td>
                                    <td class="px-5 py-3"><x-status-badge :status="$msg->status ?? 'pending'"/></td>
                                    <td class="px-5 py-3 text-xs text-slate-500 whitespace-nowrap">{{ $msg->created_at->diffForHumans() }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="py-10 text-center text-sm text-slate-400">Xabarlar yo'q</div>
            @endif
        </div>

        <!-- Danger Zone -->
        <div class="bg-white rounded-2xl border border-red-200/70 overflow-hidden">
            <div class="px-5 py-3.5 border-b border-red-100 bg-red-50/40 flex items-center gap-2">
                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                <h3 class="text-sm font-semibold text-red-900">Xavfli zona</h3>
            </div>
            <div class="p-5 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-900">Foydalanuvchini o'chirish</p>
                    <p class="text-xs text-slate-500 mt-0.5">Bu amalni qaytarib bo'lmaydi. Barcha ma'lumotlar o'chiriladi.</p>
                </div>
                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Foydalanuvchini bir umrga o\'chirishni xohlaysizmi? Bu amalni qaytarib bo\'lmaydi!');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white text-sm font-semibold rounded-lg hover:bg-red-700 shadow-sm shadow-red-600/20 transition-colors">
                        O'chirish
                    </button>
                </form>
            </div>
        </div>

    </div>

</x-admin-layout>
