<x-dashboard-layout title="Shablonlar">
    <x-slot name="actions">
        <button onclick="document.getElementById('addTemplateModal').classList.remove('hidden')" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Shablon yaratish
        </button>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($templates as $template)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-start justify-between mb-3">
                <h3 class="font-semibold text-gray-900">{{ $template->name }}</h3>
                <span class="text-xs text-gray-400">{{ $template->category ?? 'Umumiy' }}</span>
            </div>
            <p class="text-sm text-gray-600 mb-4 whitespace-pre-wrap">{{ $template->body }}</p>
            <div class="flex gap-2 pt-3 border-t">
                <form method="POST" action="{{ route('templates.destroy', $template) }}" onsubmit="return confirm('O\'chirishga ishonchingiz komilmi?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-sm text-red-600 hover:text-red-800">O'chirish</button>
                </form>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12 text-gray-500">
            Shablonlar topilmadi. Birinchi shablonni yarating!
        </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $templates->links() }}
    </div>

    <!-- Add Template Modal -->
    <div id="addTemplateModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-lg p-6">
            <h3 class="text-lg font-semibold mb-4">Shablon yaratish</h3>
            <form method="POST" action="{{ route('templates.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomi</label>
                    <input type="text" name="name" required class="w-full rounded-lg border-gray-300 text-sm">
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategoriya</label>
                    <input type="text" name="category" placeholder="masalan: marketing, xabar" class="w-full rounded-lg border-gray-300 text-sm">
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Matn</label>
                    <textarea name="body" rows="4" required placeholder="Salom {name}, sizning buyurtmangiz tayyor!" class="w-full rounded-lg border-gray-300 text-sm"></textarea>
                    <p class="text-xs text-gray-400 mt-1">Placeholder'lar: {name}, {phone}</p>
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">Saqlash</button>
                    <button type="button" onclick="document.getElementById('addTemplateModal').classList.add('hidden')" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm rounded-lg hover:bg-gray-200">Bekor</button>
                </div>
            </form>
        </div>
    </div>
</x-dashboard-layout>
