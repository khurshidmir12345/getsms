<x-dashboard-layout title="Sozlamalar">
    <div class="max-w-2xl"
         x-data="{ tab: 'api' }">

        {{-- ── Tab nav ── --}}
        <div class="flex items-center gap-1 bg-slate-100 rounded-xl p-1 mb-6 w-fit">
            <button type="button"
                    @click="tab = 'api'"
                    :class="tab === 'api'
                        ? 'bg-white text-indigo-700 shadow-sm font-semibold'
                        : 'text-slate-500 hover:text-slate-800'"
                    class="px-4 py-1.5 rounded-lg text-sm transition-all">
                API
            </button>
            <button type="button"
                    @click="tab = 'webhook'"
                    :class="tab === 'webhook'
                        ? 'bg-white text-indigo-700 shadow-sm font-semibold'
                        : 'text-slate-500 hover:text-slate-800'"
                    class="px-4 py-1.5 rounded-lg text-sm transition-all">
                Webhook
            </button>
            <button type="button"
                    @click="tab = 'account'"
                    :class="tab === 'account'
                        ? 'bg-white text-indigo-700 shadow-sm font-semibold'
                        : 'text-slate-500 hover:text-slate-800'"
                    class="px-4 py-1.5 rounded-lg text-sm transition-all">
                Hisob
            </button>
        </div>

        {{-- ════════════════════════════════
             TAB: API
        ════════════════════════════════ --}}
        <div x-show="tab === 'api'" x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
             class="space-y-5">

            {{-- API Key card --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <div class="flex items-center justify-between mb-1">
                    <h3 class="text-sm font-semibold text-slate-900">API Kalit</h3>
                </div>
                <p class="text-xs text-slate-500 mb-4">
                    Shu kalitni pDaftar yoki boshqa tizimga kiriting. SMS yuborish va qurilma ulash uchun ishlatiladi.
                </p>

                {{-- Key display + copy --}}
                <div class="flex items-center gap-2 mb-4" x-data="{ copied: false }">
                    <code class="flex-1 px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono text-slate-800 overflow-x-auto select-all">{{ $user->api_key }}</code>
                    <button type="button"
                            @click="navigator.clipboard.writeText('{{ $user->api_key }}'); copied = true; setTimeout(() => copied = false, 2000)"
                            :class="copied ? 'bg-emerald-600' : 'bg-indigo-600 hover:bg-indigo-700'"
                            class="flex-shrink-0 px-3.5 py-2.5 text-white text-xs font-medium rounded-xl transition-colors">
                        <span x-show="!copied">Nusxa</span>
                        <span x-show="copied" x-cloak>&#10003; Nusxalandi</span>
                    </button>
                </div>

                {{-- Regenerate --}}
                <form method="POST" action="{{ route('settings.regenerateApiKey') }}"
                      onsubmit="return confirm('Yangi kalit yaratilsa eskisi ishlamaydi. pDaftar va ilovalarni qayta sozlashingiz kerak. Davom etasizmi?')">
                    @csrf
                    <button type="submit"
                            class="text-xs font-medium text-red-600 hover:text-red-800 transition-colors underline underline-offset-2">
                        Kalitni yangilash
                    </button>
                </form>
            </div>

            {{-- API Docs: 3 collapsible sections --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm divide-y divide-slate-100 overflow-hidden">

                <div class="px-1 py-0.5 bg-slate-50 border-b border-slate-100 px-5 py-2.5">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">API Hujjatlari</p>
                </div>

                {{-- Section 1: SMS yuborish --}}
                <div x-data="{ open: false }">
                    <button type="button"
                            @click="open = !open"
                            class="w-full flex items-center justify-between px-5 py-3.5 text-left hover:bg-slate-50 transition-colors">
                        <div class="flex items-center gap-2.5">
                            <div class="w-5 h-5 rounded bg-indigo-100 flex items-center justify-center flex-shrink-0">
                                <span class="text-[10px] font-bold text-indigo-700">1</span>
                            </div>
                            <span class="text-sm font-medium text-slate-800">SMS yuborish</span>
                            <span class="text-xs px-1.5 py-0.5 bg-blue-50 text-blue-700 rounded font-mono">POST</span>
                        </div>
                        <svg class="w-4 h-4 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-transition x-data="{ codeCopied: false }" class="px-5 pb-4">
                        <div class="relative">
                            <pre class="bg-slate-900 text-emerald-400 rounded-xl p-4 text-xs font-mono whitespace-pre overflow-x-auto">POST {{ config('app.url') }}/api/v1/sms/send
X-API-Key: {{ $user->api_key }}
Content-Type: application/json

{
  "to": "+998901234567",
  "body": "Salom!"
}</pre>
                            <button type="button"
                                    @click="navigator.clipboard.writeText(`POST {{ config('app.url') }}/api/v1/sms/send\nX-API-Key: {{ $user->api_key }}\nContent-Type: application/json\n\n{\"to\": \"+998901234567\", \"body\": \"Salom!\"}`); codeCopied = true; setTimeout(() => codeCopied = false, 2000)"
                                    class="absolute top-3 right-3 px-2 py-1 text-xs rounded-lg transition-colors"
                                    :class="codeCopied ? 'bg-emerald-700 text-white' : 'bg-slate-700 text-slate-300 hover:bg-slate-600'">
                                <span x-show="!codeCopied">Nusxa</span>
                                <span x-show="codeCopied" x-cloak>&#10003;</span>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Section 2: Bulk SMS --}}
                <div x-data="{ open: false }">
                    <button type="button"
                            @click="open = !open"
                            class="w-full flex items-center justify-between px-5 py-3.5 text-left hover:bg-slate-50 transition-colors">
                        <div class="flex items-center gap-2.5">
                            <div class="w-5 h-5 rounded bg-indigo-100 flex items-center justify-center flex-shrink-0">
                                <span class="text-[10px] font-bold text-indigo-700">2</span>
                            </div>
                            <span class="text-sm font-medium text-slate-800">Ko'plab SMS (bulk)</span>
                            <span class="text-xs px-1.5 py-0.5 bg-blue-50 text-blue-700 rounded font-mono">POST</span>
                        </div>
                        <svg class="w-4 h-4 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-transition x-data="{ codeCopied: false }" class="px-5 pb-4">
                        <div class="relative">
                            <pre class="bg-slate-900 text-emerald-400 rounded-xl p-4 text-xs font-mono whitespace-pre overflow-x-auto">POST {{ config('app.url') }}/api/v1/sms/send-bulk
X-API-Key: {{ $user->api_key }}
Content-Type: application/json

{
  "messages": [
    {"to": "+998901111111", "body": "Xabar 1"},
    {"to": "+998902222222", "body": "Xabar 2"}
  ]
}</pre>
                            <button type="button"
                                    @click="codeCopied = true; setTimeout(() => codeCopied = false, 2000)"
                                    class="absolute top-3 right-3 px-2 py-1 text-xs rounded-lg transition-colors"
                                    :class="codeCopied ? 'bg-emerald-700 text-white' : 'bg-slate-700 text-slate-300 hover:bg-slate-600'">
                                <span x-show="!codeCopied">Nusxa</span>
                                <span x-show="codeCopied" x-cloak>&#10003;</span>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Section 3: SMS holati --}}
                <div x-data="{ open: false }">
                    <button type="button"
                            @click="open = !open"
                            class="w-full flex items-center justify-between px-5 py-3.5 text-left hover:bg-slate-50 transition-colors">
                        <div class="flex items-center gap-2.5">
                            <div class="w-5 h-5 rounded bg-indigo-100 flex items-center justify-center flex-shrink-0">
                                <span class="text-[10px] font-bold text-indigo-700">3</span>
                            </div>
                            <span class="text-sm font-medium text-slate-800">SMS holati</span>
                            <span class="text-xs px-1.5 py-0.5 bg-emerald-50 text-emerald-700 rounded font-mono">GET</span>
                        </div>
                        <svg class="w-4 h-4 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-transition x-data="{ codeCopied: false }" class="px-5 pb-4">
                        <div class="relative">
                            <pre class="bg-slate-900 text-emerald-400 rounded-xl p-4 text-xs font-mono whitespace-pre overflow-x-auto">GET {{ config('app.url') }}/api/v1/sms/{id}/status
X-API-Key: {{ $user->api_key }}</pre>
                            <button type="button"
                                    @click="codeCopied = true; setTimeout(() => codeCopied = false, 2000)"
                                    class="absolute top-3 right-3 px-2 py-1 text-xs rounded-lg transition-colors"
                                    :class="codeCopied ? 'bg-emerald-700 text-white' : 'bg-slate-700 text-slate-300 hover:bg-slate-600'">
                                <span x-show="!codeCopied">Nusxa</span>
                                <span x-show="codeCopied" x-cloak>&#10003;</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ════════════════════════════════
             TAB: WEBHOOK
        ════════════════════════════════ --}}
        <div x-show="tab === 'webhook'" x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
             class="space-y-5">

            {{-- Webhook URL form --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <div class="flex items-center gap-2 mb-1">
                    <h3 class="text-sm font-semibold text-slate-900">Webhook URL</h3>
                    <x-info-tooltip>SMS holati o'zgarganda (yuborildi, yetkazildi, xato) shu URL ga POST so'rov yuboriladi.</x-info-tooltip>
                    @if($user->webhook_url)
                        <x-status-badge status="delivered"/>
                    @endif
                </div>
                <p class="text-xs text-slate-500 mb-4">
                    Integratsiya tizimingizdan URL manzilini kiriting.
                    @if($user->webhook_url)
                        <span class="font-medium text-emerald-600">Hozir faol.</span>
                    @endif
                </p>

                <form method="POST" action="{{ route('settings.updateWebhook') }}">
                    @csrf
                    <div class="flex gap-2">
                        <input type="url"
                               name="webhook_url"
                               value="{{ old('webhook_url', $user->webhook_url) }}"
                               placeholder="https://yourapp.com/webhook/sms"
                               class="flex-1 px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-900 placeholder-slate-400
                                      focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition
                                      @error('webhook_url') border-red-400 bg-red-50 @enderror">
                        <button type="submit"
                                class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl transition-colors shadow-sm flex-shrink-0">
                            Saqlash
                        </button>
                    </div>
                    @error('webhook_url')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </form>

                @if(session('success'))
                    <div class="mt-3 px-3.5 py-2.5 bg-emerald-50 border border-emerald-100 rounded-xl">
                        <p class="text-xs text-emerald-700 font-medium">{{ session('success') }}</p>
                    </div>
                @endif
            </div>

            {{-- Webhook payload example --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden"
                 x-data="{ open: false }">
                <button type="button"
                        @click="open = !open"
                        class="w-full flex items-center justify-between px-5 py-4 text-left hover:bg-slate-50 transition-colors">
                    <div class="flex items-center gap-2.5">
                        <div class="w-7 h-7 rounded-lg bg-slate-100 flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-slate-800">Webhook payload namunasi</span>
                    </div>
                    <svg class="w-4 h-4 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open" x-transition class="px-5 pb-5">
                    <p class="text-xs text-slate-500 mb-2">SMS holati o'zgarganda sizning URL-ga quyidagi JSON yuboriladi:</p>
                    <pre class="bg-slate-900 text-emerald-400 rounded-xl p-4 text-xs font-mono whitespace-pre overflow-x-auto">{
  "event": "sms.status_changed",
  "message_id": 123,
  "status": "delivered",
  "phone_to": "+998901234567",
  "body": "Salom!",
  "sent_at": "2025-01-01T12:00:00+05:00",
  "delivered_at": "2025-01-01T12:00:05+05:00",
  "error_message": null,
  "timestamp": "2025-01-01T12:00:05+05:00"
}</pre>
                </div>
            </div>
        </div>

        {{-- ════════════════════════════════
             TAB: HISOB
        ════════════════════════════════ --}}
        <div x-show="tab === 'account'" x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">

            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <h3 class="text-sm font-semibold text-slate-900 mb-5">Hisob ma'lumotlari</h3>

                <dl class="space-y-0 divide-y divide-slate-100">
                    <div class="flex items-center justify-between py-3">
                        <dt class="text-xs text-slate-500">Ism</dt>
                        <dd class="text-sm font-medium text-slate-900">{{ $user->name }}</dd>
                    </div>
                    <div class="flex items-center justify-between py-3">
                        <dt class="text-xs text-slate-500">Email</dt>
                        <dd class="text-sm font-medium text-slate-900">{{ $user->email }}</dd>
                    </div>
                    <div class="flex items-center justify-between py-3">
                        <dt class="text-xs text-slate-500">SMS ishlatilgan</dt>
                        <dd class="flex items-center gap-2">
                            <span class="text-sm font-semibold text-slate-900 tabular-nums">
                                {{ number_format($user->sms_used) }}
                                <span class="text-slate-400 font-normal">/ {{ number_format($user->sms_limit) }}</span>
                            </span>
                            @php
                                $usagePct = $user->sms_limit > 0
                                    ? round(($user->sms_used / $user->sms_limit) * 100)
                                    : 0;
                                $usageColor = $usagePct >= 90 ? 'bg-red-500' : ($usagePct >= 70 ? 'bg-amber-400' : 'bg-indigo-600');
                            @endphp
                            <div class="w-20 bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                <div class="{{ $usageColor }} h-1.5 rounded-full transition-all" style="width: {{ $usagePct }}%"></div>
                            </div>
                        </dd>
                    </div>
                    <div class="flex items-center justify-between py-3">
                        <dt class="text-xs text-slate-500">Tarif rejasi</dt>
                        <dd>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700">
                                {{ $user->plan?->name ?? 'Bepul' }}
                            </span>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

    </div>{{-- /x-data --}}
</x-dashboard-layout>
