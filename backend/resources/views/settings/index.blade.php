<x-dashboard-layout title="Sozlamalar">
    <div class="max-w-2xl space-y-6">
        <!-- API Keys -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">API Kalitlar</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">API Key</label>
                    <div class="flex items-center gap-2">
                        <code class="bg-gray-100 px-4 py-2 rounded-lg text-sm font-mono flex-1 overflow-x-auto">{{ $user->api_key }}</code>
                        <button onclick="navigator.clipboard.writeText('{{ $user->api_key }}')" class="px-3 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm hover:bg-gray-200">Nusxa</button>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">API Secret</label>
                    <div class="flex items-center gap-2">
                        <code class="bg-gray-100 px-4 py-2 rounded-lg text-sm font-mono flex-1">{{ str_repeat('*', 20) }}</code>
                    </div>
                </div>
                <form method="POST" action="{{ route('settings.regenerateApiKey') }}" onsubmit="return confirm('Yangi kalit yaratilsa eskisi ishlamaydi. Davom etasizmi?')">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-red-50 text-red-600 text-sm rounded-lg hover:bg-red-100">API kalitni yangilash</button>
                </form>
            </div>
        </div>

        <!-- Account Info -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Hisob ma'lumotlari</h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Ism:</span>
                    <span class="text-gray-900">{{ $user->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Email:</span>
                    <span class="text-gray-900">{{ $user->email }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">SMS ishlatilgan:</span>
                    <span class="text-gray-900">{{ $user->sms_used }} / {{ $user->sms_limit }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Tarif:</span>
                    <span class="text-gray-900">{{ $user->plan?->name ?? 'Bepul' }}</span>
                </div>
            </div>
        </div>

        <!-- API Documentation -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">API Hujjat</h3>
            <div class="space-y-4 text-sm">
                <div>
                    <p class="font-medium text-gray-700 mb-1">SMS yuborish</p>
                    <code class="block bg-gray-900 text-green-400 p-3 rounded-lg text-xs">
POST /api/v1/sms/send<br>
Authorization: Bearer {api_token}<br>
Content-Type: application/json<br><br>
{"to": "+998901234567", "body": "Salom!"}
                    </code>
                </div>
                <div>
                    <p class="font-medium text-gray-700 mb-1">SMS holati</p>
                    <code class="block bg-gray-900 text-green-400 p-3 rounded-lg text-xs">
GET /api/v1/sms/{id}/status<br>
Authorization: Bearer {api_token}
                    </code>
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>
