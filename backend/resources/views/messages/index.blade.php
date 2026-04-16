<x-dashboard-layout title="Xabarlar">
    <x-slot name="actions">
        <a href="{{ route('messages.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            SMS Yuborish
        </a>
    </x-slot>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
        <form method="GET" class="flex flex-wrap gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Qidirish..." class="rounded-lg border-gray-300 text-sm flex-1 min-w-[200px]">
            <select name="status" class="rounded-lg border-gray-300 text-sm">
                <option value="">Barcha holatlar</option>
                @foreach(['pending','queued','sending','sent','delivered','failed'] as $s)
                    <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            <select name="direction" class="rounded-lg border-gray-300 text-sm">
                <option value="">Barcha yo'nalishlar</option>
                <option value="outgoing" {{ request('direction') === 'outgoing' ? 'selected' : '' }}>Chiquvchi</option>
                <option value="incoming" {{ request('direction') === 'incoming' ? 'selected' : '' }}>Kiruvchi</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm hover:bg-gray-200">Filter</button>
        </form>
    </div>

    <!-- Messages Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Yo'nalish</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Raqam</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Xabar</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Holat</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Qurilma</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vaqt</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($messages as $message)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-500">#{{ $message->id }}</td>
                    <td class="px-6 py-4">
                        @if($message->direction === 'outgoing')
                            <span class="text-blue-600 text-sm">Chiquvchi</span>
                        @else
                            <span class="text-green-600 text-sm">Kiruvchi</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">
                        {{ $message->direction === 'outgoing' ? $message->phone_to : $message->phone_from }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">{{ $message->body }}</td>
                    <td class="px-6 py-4">
                        @php
                            $colors = ['pending' => 'yellow', 'queued' => 'blue', 'sending' => 'blue', 'sent' => 'indigo', 'delivered' => 'green', 'failed' => 'red'];
                            $color = $colors[$message->status] ?? 'gray';
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $color }}-100 text-{{ $color }}-800">
                            {{ $message->status }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $message->device?->name ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $message->created_at->format('d.m.Y H:i') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">Xabarlar topilmadi</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t">
            {{ $messages->withQueryString()->links() }}
        </div>
    </div>
</x-dashboard-layout>
