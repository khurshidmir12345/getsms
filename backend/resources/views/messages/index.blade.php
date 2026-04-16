<x-dashboard-layout title="Xabarlar">
    <x-slot name="actions">
        <a href="{{ route('messages.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-150">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            SMS Yuborish
        </a>
    </x-slot>

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
                    placeholder="Qidirish..."
                    class="w-full pl-9 pr-3 py-2 text-sm border border-slate-200 rounded-lg bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                >
            </div>

            <select name="status"
                class="py-2 pl-3 pr-8 text-sm border border-slate-200 rounded-lg bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition appearance-none">
                <option value="">Barcha holatlar</option>
                @foreach(['pending','queued','sending','sent','delivered','failed'] as $s)
                    <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst($s) }}</option>
                @endforeach
            </select>

            <select name="direction"
                class="py-2 pl-3 pr-8 text-sm border border-slate-200 rounded-lg bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition appearance-none">
                <option value="">Barcha yo'nalishlar</option>
                <option value="outgoing" @selected(request('direction') === 'outgoing')>Chiquvchi</option>
                <option value="incoming" @selected(request('direction') === 'incoming')>Kiruvchi</option>
            </select>

            <button type="submit"
                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors duration-150">
                Filter
            </button>

            @if(request()->hasAny(['search','status','direction']))
                <a href="{{ route('messages.index') }}"
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
                    <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider w-16">#</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Yo'nalish</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Raqam</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Xabar</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Holat</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Qurilma</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Vaqt</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($messages as $message)
                    <tr class="hover:bg-slate-50 transition-colors duration-100">
                        <td class="px-5 py-3.5 text-sm text-slate-400 font-mono">#{{ $message->id }}</td>

                        <td class="px-5 py-3.5">
                            @if($message->direction === 'outgoing')
                                <span class="inline-flex items-center gap-1.5 text-sm font-medium text-blue-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                                    </svg>
                                    Chiquvchi
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 text-sm font-medium text-emerald-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                                    </svg>
                                    Kiruvchi
                                </span>
                            @endif
                        </td>

                        <td class="px-5 py-3.5 text-sm font-medium text-slate-800 whitespace-nowrap">
                            {{ $message->direction === 'outgoing' ? $message->phone_to : $message->phone_from }}
                        </td>

                        <td class="px-5 py-3.5 text-sm text-slate-500 max-w-xs truncate">
                            {{ $message->body }}
                        </td>

                        <td class="px-5 py-3.5">
                            <x-status-badge :status="$message->status"/>
                        </td>

                        <td class="px-5 py-3.5 text-sm text-slate-500">
                            {{ $message->device?->name ?? '—' }}
                        </td>

                        <td class="px-5 py-3.5 text-sm text-slate-400 whitespace-nowrap">
                            {{ $message->created_at->diffForHumans() }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-16 text-center">
                            <div class="flex flex-col items-center gap-3 text-slate-400">
                                <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M8 10h.01M12 10h.01M16 10h.01M21 16c0 1.1-.9 2-2 2H7l-4 4V6c0-1.1.9-2 2-2h14c1.1 0 2 .9 2 2v10z"/>
                                </svg>
                                <p class="text-sm font-medium text-slate-500">Xabarlar topilmadi</p>
                                <a href="{{ route('messages.create') }}"
                                   class="text-sm text-indigo-600 hover:text-indigo-700 font-medium hover:underline">
                                    Birinchi SMS yuboring &rarr;
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($messages->hasPages())
            <div class="px-5 py-4 border-t border-slate-100 bg-slate-50/50">
                {{ $messages->withQueryString()->links() }}
            </div>
        @endif
    </div>
</x-dashboard-layout>
