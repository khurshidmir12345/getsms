<x-dashboard-layout title="Kampaniya yaratish">
    <div class="max-w-2xl">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <form method="POST" action="{{ route('campaigns.store') }}">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kampaniya nomi</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full rounded-lg border-gray-300 text-sm">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Shablon</label>
                    <select name="template_id" required class="w-full rounded-lg border-gray-300 text-sm">
                        <option value="">Tanlang...</option>
                        @foreach($templates as $template)
                            <option value="{{ $template->id }}">{{ $template->name }}</option>
                        @endforeach
                    </select>
                    @error('template_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kontakt guruhi</label>
                    <select name="contact_group_id" required class="w-full rounded-lg border-gray-300 text-sm">
                        <option value="">Tanlang...</option>
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}">{{ $group->name }} ({{ $group->contacts_count }} kontakt)</option>
                        @endforeach
                    </select>
                    @error('contact_group_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Qurilma</label>
                    <select name="device_id" class="w-full rounded-lg border-gray-300 text-sm">
                        <option value="">Avtomatik tanlash</option>
                        @foreach($devices as $device)
                            <option value="{{ $device->id }}">{{ $device->name }} ({{ $device->status }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tezlik (SMS/daqiqa)</label>
                    <input type="number" name="rate_limit" value="{{ old('rate_limit', 20) }}" min="1" max="60" class="w-full rounded-lg border-gray-300 text-sm">
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">Yaratish</button>
                    <a href="{{ route('campaigns.index') }}" class="px-6 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200">Bekor</a>
                </div>
            </form>
        </div>
    </div>
</x-dashboard-layout>
