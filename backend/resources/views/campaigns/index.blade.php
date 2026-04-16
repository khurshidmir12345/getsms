<x-dashboard-layout title="Kampaniyalar">
    <x-slot name="actions">
        <a href="{{ route('campaigns.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Kampaniya yaratish
        </a>
    </x-slot>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-slate-100">
            <thead>
                <tr class="bg-slate-50">
                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Nomi</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Shablon</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Guruh</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Holat</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Progress</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Amallar</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($campaigns as $campaign)
                <tr class="hover:bg-slate-50/60 transition-colors">
                    {{-- Nomi --}}
                    <td class="px-6 py-4">
                        <span class="text-sm font-semibold text-slate-900">{{ $campaign->name }}</span>
                    </td>

                    {{-- Shablon --}}
                    <td class="px-6 py-4">
                        @if($campaign->template)
                            <span class="text-sm text-slate-700">{{ $campaign->template->name }}</span>
                        @else
                            <span class="text-slate-400">—</span>
                        @endif
                    </td>

                    {{-- Guruh --}}
                    <td class="px-6 py-4">
                        @if($campaign->contactGroup)
                            <span class="text-sm text-slate-700">{{ $campaign->contactGroup->name }}</span>
                        @else
                            <span class="text-slate-400">—</span>
                        @endif
                    </td>

                    {{-- Holat --}}
                    <td class="px-6 py-4">
                        <x-status-badge :status="$campaign->status"/>
                    </td>

                    {{-- Progress --}}
                    <td class="px-6 py-4">
                        @php
                            $pct = $campaign->total_messages > 0
                                ? round(($campaign->sent_count / $campaign->total_messages) * 100)
                                : 0;
                        @endphp
                        <div class="flex items-center gap-2.5 min-w-[140px]">
                            <div class="flex-1 bg-slate-100 rounded-full h-2 overflow-hidden">
                                <div class="bg-indigo-600 h-2 rounded-full transition-all duration-500"
                                     style="width: {{ $pct }}%"></div>
                            </div>
                            <span class="text-xs text-slate-500 whitespace-nowrap tabular-nums">
                                {{ $campaign->sent_count }}/{{ $campaign->total_messages }}
                                <span class="text-slate-400">({{ $pct }}%)</span>
                            </span>
                        </div>
                    </td>

                    {{-- Amallar --}}
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-1">
                            @if(in_array($campaign->status, ['draft', 'paused']))
                                <form method="POST" action="{{ route('campaigns.start', $campaign) }}">
                                    @csrf
                                    <button type="submit"
                                            class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium text-emerald-700 bg-emerald-50 hover:bg-emerald-100 rounded-lg transition-colors">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-6.518-3.76A1 1 0 007 8.22v7.56a1 1 0 001.234.97l6.518-1.76a1 1 0 000-1.944z"/>
                                        </svg>
                                        Boshlash
                                    </button>
                                </form>
                            @endif

                            @if($campaign->status === 'running')
                                <form method="POST" action="{{ route('campaigns.pause', $campaign) }}">
                                    @csrf
                                    <button type="submit"
                                            class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium text-amber-700 bg-amber-50 hover:bg-amber-100 rounded-lg transition-colors">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6"/>
                                        </svg>
                                        Pauza
                                    </button>
                                </form>
                            @endif

                            @if(!in_array($campaign->status, ['completed', 'cancelled']))
                                <form method="POST" action="{{ route('campaigns.cancel', $campaign) }}"
                                      onsubmit="return confirm('Kampaniyani bekor qilishga ishonchingiz komilmi?')">
                                    @csrf
                                    <button type="submit"
                                            class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium text-red-700 bg-red-50 hover:bg-red-100 rounded-lg transition-colors">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        Bekor
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center">
                                <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-slate-700">Hali kampaniyalar yo'q</p>
                                <p class="text-xs text-slate-400 mt-0.5">Birinchi kampaniyangizni yaratib boshlang</p>
                            </div>
                            <a href="{{ route('campaigns.create') }}"
                               class="mt-1 inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium rounded-lg transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Kampaniya yaratish
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($campaigns->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
            {{ $campaigns->links() }}
        </div>
        @endif
    </div>
</x-dashboard-layout>
