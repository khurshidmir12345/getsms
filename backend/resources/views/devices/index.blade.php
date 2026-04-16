<x-dashboard-layout title="Qurilmalar">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <h3 class="text-sm font-medium text-gray-700 mb-2">Qurilma ulash</h3>
        <p class="text-sm text-gray-500 mb-3">Android telefoningizga SMS Gateway ilovasini o'rnating va quyidagi API kalitni kiriting:</p>
        <div class="flex items-center gap-2">
            <code class="bg-gray-100 px-4 py-2 rounded-lg text-sm font-mono flex-1">{{ auth()->user()->api_key }}</code>
            <button onclick="navigator.clipboard.writeText('{{ auth()->user()->api_key }}')" class="px-3 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">Nusxa</button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($devices as $device)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">{{ $device->name }}</h3>
                        <p class="text-xs text-gray-500">{{ $device->model ?? 'Noma\'lum' }}</p>
                    </div>
                </div>
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $device->isOnline() ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                    {{ $device->isOnline() ? 'Online' : 'Offline' }}
                </span>
            </div>

            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Telefon:</span>
                    <span class="text-gray-900">{{ $device->phone_number ?? '-' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Operator:</span>
                    <span class="text-gray-900">{{ $device->operator ?? '-' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Batareya:</span>
                    <span class="text-gray-900">{{ $device->battery_level !== null ? $device->battery_level . '%' : '-' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Signal:</span>
                    <span class="text-gray-900">{{ $device->signal_strength !== null ? $device->signal_strength . '%' : '-' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">So'nggi faollik:</span>
                    <span class="text-gray-900">{{ $device->last_seen_at?->diffForHumans() ?? 'Hech qachon' }}</span>
                </div>
            </div>

            <div class="flex gap-2 mt-4 pt-4 border-t">
                <form method="POST" action="{{ route('devices.toggle', $device) }}">
                    @csrf
                    <button class="text-sm {{ $device->is_active ? 'text-yellow-600' : 'text-green-600' }} hover:underline">
                        {{ $device->is_active ? 'O\'chirish' : 'Yoqish' }}
                    </button>
                </form>
                <form method="POST" action="{{ route('devices.destroy', $device) }}" onsubmit="return confirm('O\'chirishga ishonchingiz komilmi?')">
                    @csrf @method('DELETE')
                    <button class="text-sm text-red-600 hover:underline">O'chirish</button>
                </form>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12 text-gray-500">
            <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
            <p>Hali qurilmalar ulanmagan</p>
            <p class="text-sm mt-1">Android telefoningizga ilovani o'rnating</p>
        </div>
        @endforelse
    </div>
</x-dashboard-layout>
