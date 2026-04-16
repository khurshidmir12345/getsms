<x-dashboard-layout title="Qo'llanma">
    <div x-data="{ tab: 'simple' }">

        {{-- Tab controls --}}
        <div class="flex items-center gap-1 bg-slate-100 rounded-2xl p-1 w-fit mb-8">
            <button
                @click="tab = 'simple'"
                :class="tab === 'simple'
                    ? 'bg-indigo-600 text-white shadow-sm'
                    : 'text-slate-500 hover:text-slate-700'"
                class="px-5 py-2 rounded-xl text-sm font-medium transition-all duration-200 focus:outline-none"
            >
                Oddiy foydalanish
            </button>
            <button
                @click="tab = 'pdaftar'"
                :class="tab === 'pdaftar'
                    ? 'bg-indigo-600 text-white shadow-sm'
                    : 'text-slate-500 hover:text-slate-700'"
                class="px-5 py-2 rounded-xl text-sm font-medium transition-all duration-200 focus:outline-none"
            >
                pDaftar integratsiya
            </button>
        </div>

        {{-- Tab 1: Oddiy foydalanish --}}
        <div x-show="tab === 'simple'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="max-w-2xl">
                @php
                $simpleSteps = [
                    [
                        'num'   => 1,
                        'title' => "Ro'yxatdan o'ting",
                        'desc'  => 'Email va parol bilan hisob yarating',
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>',
                    ],
                    [
                        'num'   => 2,
                        'title' => 'Ilovani yuklab oling',
                        'desc'  => 'Android telefoningizga SMS Gateway ilovasini o\'rnatring',
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 14v-4m0 0l-2 2m2-2l2 2"/>',
                    ],
                    [
                        'num'   => 3,
                        'title' => 'Telefonni ulang',
                        'desc'  => 'Ilovada API kalitni kiriting va telefon ulanadi',
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>',
                    ],
                    [
                        'num'   => 4,
                        'title' => 'SMS yuboring',
                        'desc'  => 'Web paneldan yoki API orqali SMS yuborishni boshlang',
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>',
                    ],
                ];
                @endphp

                @foreach ($simpleSteps as $i => $step)
                    <div class="flex gap-4">
                        {{-- Left: number + connector --}}
                        <div class="flex flex-col items-center">
                            <div class="w-9 h-9 rounded-full bg-indigo-600 text-white flex items-center justify-center text-sm font-bold flex-shrink-0">
                                {{ $step['num'] }}
                            </div>
                            @if (!$loop->last)
                                <div class="w-px flex-1 bg-slate-200 my-2"></div>
                            @endif
                        </div>

                        {{-- Right: card --}}
                        <div class="bg-white border border-slate-200/60 rounded-2xl p-5 flex items-start gap-4 {{ $loop->last ? 'mb-0' : 'mb-2' }} flex-1">
                            <div class="w-11 h-11 rounded-xl bg-indigo-50 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    {!! $step['icon'] !!}
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-slate-800">{{ $step['title'] }}</p>
                                <p class="text-sm text-slate-500 mt-0.5">{{ $step['desc'] }}</p>
                            </div>
                        </div>
                    </div>
                    @if (!$loop->last)
                        {{-- spacer so the connector line aligns correctly --}}
                        <div class="h-0"></div>
                    @endif
                @endforeach
            </div>
        </div>

        {{-- Tab 2: pDaftar integratsiya --}}
        <div x-show="tab === 'pdaftar'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="max-w-2xl">
                @php
                $pdaftarSteps = [
                    [
                        'num'   => 1,
                        'title' => "Ro'yxatdan o'ting",
                        'desc'  => 'Email va parol bilan hisob yarating',
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>',
                    ],
                    [
                        'num'   => 2,
                        'title' => 'API kalitni oling',
                        'desc'  => 'Sozlamalar sahifasidan API kalitni nusxa oling',
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>',
                    ],
                    [
                        'num'   => 3,
                        'title' => "pDaftar ga kiriting",
                        'desc'  => "pDaftar sozlamalarida SMS provayderga API kalitni kiriting",
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"/>',
                    ],
                    [
                        'num'   => 4,
                        'title' => 'Telefonni ulang',
                        'desc'  => "Android telefoningizga ilova o'rnatib API kalit kiriting",
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>',
                    ],
                    [
                        'num'   => 5,
                        'title' => 'Tayyor!',
                        'desc'  => 'pDaftar avtomatik SMS yuboradi, telefon orqali yetkaziladi',
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                    ],
                ];
                @endphp

                @foreach ($pdaftarSteps as $i => $step)
                    <div class="flex gap-4">
                        {{-- Left: number + connector --}}
                        <div class="flex flex-col items-center">
                            <div class="w-9 h-9 rounded-full {{ $loop->last ? 'bg-emerald-500' : 'bg-indigo-600' }} text-white flex items-center justify-center text-sm font-bold flex-shrink-0">
                                @if ($loop->last)
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                    </svg>
                                @else
                                    {{ $step['num'] }}
                                @endif
                            </div>
                            @if (!$loop->last)
                                <div class="w-px flex-1 bg-slate-200 my-2"></div>
                            @endif
                        </div>

                        {{-- Right: card --}}
                        <div class="bg-white border {{ $loop->last ? 'border-emerald-200/60' : 'border-slate-200/60' }} rounded-2xl p-5 flex items-start gap-4 {{ $loop->last ? 'mb-0' : 'mb-2' }} flex-1">
                            <div class="w-11 h-11 rounded-xl {{ $loop->last ? 'bg-emerald-50' : 'bg-indigo-50' }} flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 {{ $loop->last ? 'text-emerald-500' : 'text-indigo-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    {!! $step['icon'] !!}
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold {{ $loop->last ? 'text-emerald-700' : 'text-slate-800' }}">{{ $step['title'] }}</p>
                                <p class="text-sm text-slate-500 mt-0.5">{{ $step['desc'] }}</p>
                            </div>
                        </div>
                    </div>
                    @if (!$loop->last)
                        <div class="h-0"></div>
                    @endif
                @endforeach
            </div>
        </div>

    </div>
</x-dashboard-layout>
