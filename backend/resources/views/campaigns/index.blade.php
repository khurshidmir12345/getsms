<x-dashboard-layout title="Kampaniyalar">
    <x-slot name="actions">
        <a href="{{ route('campaigns.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Kampaniya yaratish
        </a>
    </x-slot>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nomi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Shablon</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Guruh</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Holat</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Progress</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amallar</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($campaigns as $campaign)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $campaign->name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $campaign->template?->name ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $campaign->contactGroup?->name ?? '-' }}</td>
                    <td class="px-6 py-4">
                        @php
                            $colors = ['draft' => 'gray', 'running' => 'blue', 'paused' => 'yellow', 'completed' => 'green', 'cancelled' => 'red'];
                            $color = $colors[$campaign->status] ?? 'gray';
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $color }}-100 text-{{ $color }}-800">
                            {{ $campaign->status }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <div class="flex-1 bg-gray-200 rounded-full h-2 max-w-[100px]">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $campaign->progress() }}%"></div>
                            </div>
                            <span class="text-xs text-gray-500">{{ $campaign->sent_count }}/{{ $campaign->total_messages }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <div class="flex gap-2">
                            @if($campaign->status === 'draft' || $campaign->status === 'paused')
                                <form method="POST" action="{{ route('campaigns.start', $campaign) }}">
                                    @csrf
                                    <button class="text-green-600 hover:text-green-800 text-sm">Boshlash</button>
                                </form>
                            @endif
                            @if($campaign->status === 'running')
                                <form method="POST" action="{{ route('campaigns.pause', $campaign) }}">
                                    @csrf
                                    <button class="text-yellow-600 hover:text-yellow-800 text-sm">Pauza</button>
                                </form>
                            @endif
                            @if($campaign->status !== 'completed' && $campaign->status !== 'cancelled')
                                <form method="POST" action="{{ route('campaigns.cancel', $campaign) }}" onsubmit="return confirm('Bekor qilishga ishonchingiz komilmi?')">
                                    @csrf
                                    <button class="text-red-600 hover:text-red-800 text-sm">Bekor</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">Kampaniyalar topilmadi</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t">
            {{ $campaigns->links() }}
        </div>
    </div>
</x-dashboard-layout>
