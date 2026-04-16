<x-dashboard-layout title="Sozlamalar">
    <div class="max-w-2xl space-y-6">
        <!-- API Key -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">API Key</h3>
            <p class="text-sm text-gray-500 mb-4">Shu kalitni pDaftar yoki boshqa tizimga kiriting. SMS yuborish va qurilma ulash uchun ishlatiladi.</p>
            <div class="space-y-4">
                <div>
                    <div class="flex items-center gap-2">
                        <code class="bg-gray-100 px-4 py-2 rounded-lg text-sm font-mono flex-1 overflow-x-auto">{{ $user->api_key }}</code>
                        <button onclick="navigator.clipboard.writeText('{{ $user->api_key }}')" class="px-3 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">Nusxa</button>
                    </div>
                </div>
                <form method="POST" action="{{ route('settings.regenerateApiKey') }}" onsubmit="return confirm('Yangi kalit yaratilsa eskisi ishlamaydi. pDaftar va ilovalarni qayta sozlashingiz kerak. Davom etasizmi?')">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-red-50 text-red-600 text-sm rounded-lg hover:bg-red-100">Kalitni yangilash</button>
                </form>
            </div>
        </div>

        <!-- Webhook -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Webhook URL</h3>
            <p class="text-sm text-gray-500 mb-4">SMS holati o'zgarganda (yuborildi, yetkazildi, xato) shu URL ga POST so'rov yuboriladi. pDaftar integratsiya uchun kerak.</p>
            <form method="POST" action="{{ route('settings.updateWebhook') }}">
                @csrf
                <div class="mb-3">
                    <input type="url" name="webhook_url" value="{{ old('webhook_url', $user->webhook_url) }}" placeholder="https://pdaftar.uz/webhook/sms"
                        class="w-full rounded-lg border-gray-300 text-sm @error('webhook_url') border-red-300 @enderror">
                    @error('webhook_url') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">Saqlash</button>
            </form>
            @if($user->webhook_url)
                <div class="mt-3 p-3 bg-green-50 rounded-lg">
                    <p class="text-sm text-green-700">Webhook faol: {{ $user->webhook_url }}</p>
                </div>
            @endif
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
            <h3 class="text-lg font-semibold text-gray-900 mb-4">API Hujjat (pDaftar integratsiya)</h3>
            <div class="space-y-4 text-sm">
                <div class="p-3 bg-blue-50 rounded-lg text-blue-800 text-sm">
                    Barcha so'rovlarda <strong>X-API-Key</strong> header yoki <strong>Authorization: Bearer sk_...</strong> yuborilishi kerak.
                </div>

                <div>
                    <p class="font-medium text-gray-700 mb-1">1. SMS yuborish</p>
                    <code class="block bg-gray-900 text-green-400 p-3 rounded-lg text-xs whitespace-pre">POST {{ config('app.url') }}/api/v1/sms/send
X-API-Key: {{ $user->api_key }}
Content-Type: application/json

{"to": "+998901234567", "body": "Salom!"}</code>
                </div>

                <div>
                    <p class="font-medium text-gray-700 mb-1">2. Ko'plab SMS yuborish (bulk)</p>
                    <code class="block bg-gray-900 text-green-400 p-3 rounded-lg text-xs whitespace-pre">POST {{ config('app.url') }}/api/v1/sms/send-bulk
X-API-Key: {{ $user->api_key }}
Content-Type: application/json

{"messages": [
  {"to": "+998901111111", "body": "Xabar 1"},
  {"to": "+998902222222", "body": "Xabar 2"}
]}</code>
                </div>

                <div>
                    <p class="font-medium text-gray-700 mb-1">3. SMS holati</p>
                    <code class="block bg-gray-900 text-green-400 p-3 rounded-lg text-xs whitespace-pre">GET {{ config('app.url') }}/api/v1/sms/{id}/status
X-API-Key: {{ $user->api_key }}</code>
                </div>

                <div>
                    <p class="font-medium text-gray-700 mb-1">4. Hisob ma'lumotlari</p>
                    <code class="block bg-gray-900 text-green-400 p-3 rounded-lg text-xs whitespace-pre">GET {{ config('app.url') }}/api/v1/account/info
X-API-Key: {{ $user->api_key }}</code>
                </div>

                <div>
                    <p class="font-medium text-gray-700 mb-1">5. Webhook sozlash (API orqali)</p>
                    <code class="block bg-gray-900 text-green-400 p-3 rounded-lg text-xs whitespace-pre">POST {{ config('app.url') }}/api/v1/webhook/configure
X-API-Key: {{ $user->api_key }}
Content-Type: application/json

{"url": "https://pdaftar.uz/webhook/sms"}</code>
                </div>

                <div class="border-t pt-4 mt-4">
                    <p class="font-medium text-gray-700 mb-2">Webhook payload (SMS holati o'zgarganda sizga keladi):</p>
                    <code class="block bg-gray-900 text-green-400 p-3 rounded-lg text-xs whitespace-pre">{
  "event": "sms.status_changed",
  "message_id": 123,
  "status": "delivered",
  "phone_to": "+998901234567",
  "body": "Salom!",
  "sent_at": "2025-01-01T12:00:00+05:00",
  "delivered_at": "2025-01-01T12:00:05+05:00",
  "error_message": null,
  "timestamp": "2025-01-01T12:00:05+05:00"
}</code>
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>
