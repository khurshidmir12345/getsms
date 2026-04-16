<x-dashboard-layout title="Kontaktlar">
    <x-slot name="actions">
        <button onclick="document.getElementById('addContactModal').classList.remove('hidden')" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Kontakt qo'shish
        </button>
        <button onclick="document.getElementById('importModal').classList.remove('hidden')" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200">
            Import CSV
        </button>
    </x-slot>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
        <form method="GET" class="flex flex-wrap gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Ism yoki raqam..." class="rounded-lg border-gray-300 text-sm flex-1 min-w-[200px]">
            <select name="group_id" class="rounded-lg border-gray-300 text-sm">
                <option value="">Barcha guruhlar</option>
                @foreach($groups as $group)
                    <option value="{{ $group->id }}" {{ request('group_id') == $group->id ? 'selected' : '' }}>{{ $group->name }} ({{ $group->contacts_count }})</option>
                @endforeach
            </select>
            <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm hover:bg-gray-200">Filter</button>
        </form>
    </div>

    <!-- Contacts Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ism</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Telefon</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Guruh</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amallar</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($contacts as $contact)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $contact->name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $contact->phone }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $contact->email ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $contact->group?->name ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm">
                        <form method="POST" action="{{ route('contacts.destroy', $contact) }}" class="inline" onsubmit="return confirm('O\'chirishga ishonchingiz komilmi?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800">O'chirish</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">Kontaktlar topilmadi</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t">
            {{ $contacts->withQueryString()->links() }}
        </div>
    </div>

    <!-- Add Contact Modal -->
    <div id="addContactModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6">
            <h3 class="text-lg font-semibold mb-4">Kontakt qo'shish</h3>
            <form method="POST" action="{{ route('contacts.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ism</label>
                    <input type="text" name="name" required class="w-full rounded-lg border-gray-300 text-sm">
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Telefon</label>
                    <input type="text" name="phone" required placeholder="+998901234567" class="w-full rounded-lg border-gray-300 text-sm">
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" class="w-full rounded-lg border-gray-300 text-sm">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Guruh</label>
                    <select name="contact_group_id" class="w-full rounded-lg border-gray-300 text-sm">
                        <option value="">Guruhsiz</option>
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">Saqlash</button>
                    <button type="button" onclick="document.getElementById('addContactModal').classList.add('hidden')" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm rounded-lg hover:bg-gray-200">Bekor</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Import Modal -->
    <div id="importModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6">
            <h3 class="text-lg font-semibold mb-4">CSV Import</h3>
            <p class="text-sm text-gray-500 mb-4">CSV faylda "name" va "phone" ustunlari bo'lishi kerak.</p>
            <form method="POST" action="{{ route('contacts.import') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <input type="file" name="file" accept=".csv,.txt" required class="text-sm">
                </div>
                <div class="mb-4">
                    <select name="contact_group_id" class="w-full rounded-lg border-gray-300 text-sm">
                        <option value="">Guruhsiz</option>
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">Import</button>
                    <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm rounded-lg hover:bg-gray-200">Bekor</button>
                </div>
            </form>
        </div>
    </div>
</x-dashboard-layout>
