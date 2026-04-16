<x-dashboard-layout title="SMS Yuborish">

    <div class="max-w-xl mx-auto">
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6"
             x-data="{
                body: '{{ old('body') }}',
                charCount: 0,
                smsCount: 0,
                updateCounter() {
                    this.charCount = this.body.length;
                    if (this.charCount === 0) { this.smsCount = 0; return; }
                    const unicode = /[^\x00-\x7F]/.test(this.body);
                    const single = unicode ? 70 : 160;
                    const multi  = unicode ? 67 : 153;
                    this.smsCount = this.charCount <= single ? 1 : Math.ceil(this.charCount / multi);
                },
                loadTemplate(select) {
                    const opt = select.options[select.selectedIndex];
                    if (opt.dataset.body) {
                        this.body = opt.dataset.body;
                        this.updateCounter();
                    }
                }
             }"
             x-init="updateCounter()">

            <form method="POST" action="{{ route('messages.store') }}">
                @csrf

                {{-- Phone --}}
                <div class="mb-5">
                    <label for="phone_to" class="block text-sm font-medium text-slate-700 mb-1.5">
                        Telefon raqam
                    </label>
                    <input
                        type="text"
                        id="phone_to"
                        name="phone_to"
                        value="{{ old('phone_to') }}"
                        placeholder="+998 90 123 45 67"
                        class="w-full px-3.5 py-2.5 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition
                               @error('phone_to') border-red-400 bg-red-50 @else border-slate-200 bg-white @enderror"
                    >
                    @error('phone_to')
                        <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Device --}}
                <div class="mb-5">
                    <label for="device_id" class="block text-sm font-medium text-slate-700 mb-1.5">
                        Qurilma
                    </label>
                    <select
                        id="device_id"
                        name="device_id"
                        class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition appearance-none"
                    >
                        <option value="">— Avtomatik tanlash —</option>
                        @foreach($devices as $device)
                            <option
                                value="{{ $device->id }}"
                                {{ old('device_id') == $device->id ? 'selected' : '' }}
                                @if($device->status !== 'online') disabled @endif
                            >
                                {{ $device->name }}
                                @if($device->phone_number) ({{ $device->phone_number }}) @endif
                                — {{ $device->status }}
                            </option>
                        @endforeach
                    </select>
                    @if($devices->where('status','online')->isEmpty())
                        <p class="mt-1.5 text-xs text-amber-600 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                            </svg>
                            Hozirda online qurilmalar yo'q
                        </p>
                    @endif
                </div>

                {{-- Template --}}
                <div class="mb-5">
                    <label for="template_select" class="block text-sm font-medium text-slate-700 mb-1.5">
                        Shablon <span class="text-slate-400 font-normal">(ixtiyoriy)</span>
                    </label>
                    <select
                        id="template_select"
                        class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition appearance-none"
                        @change="loadTemplate($el)"
                    >
                        <option value="" data-body="">— Shablonsiz —</option>
                        @foreach($templates as $template)
                            <option value="{{ $template->id }}" data-body="{{ $template->body }}">
                                {{ $template->name }}
                                @if($template->category) — {{ $template->category }} @endif
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Body --}}
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="message_body" class="block text-sm font-medium text-slate-700">
                            Xabar matni
                        </label>
                        <span class="text-xs text-slate-400 tabular-nums"
                              x-text="charCount + ' belgi | ' + smsCount + ' SMS'">
                            0 belgi | 0 SMS
                        </span>
                    </div>
                    <textarea
                        id="message_body"
                        name="body"
                        rows="5"
                        placeholder="SMS matnini yozing..."
                        x-model="body"
                        @input="updateCounter()"
                        class="w-full px-3.5 py-2.5 text-sm border rounded-lg resize-none focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition
                               @error('body') border-red-400 bg-red-50 @else border-slate-200 bg-white @enderror"
                    ></textarea>
                    {{-- Progress bar --}}
                    <div class="mt-1.5 h-1 w-full bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-indigo-500 rounded-full transition-all duration-150"
                             :style="'width:' + Math.min(100, (charCount / 160) * 100) + '%'"
                             :class="charCount > 160 ? 'bg-amber-500' : 'bg-indigo-500'">
                        </div>
                    </div>
                    @error('body')
                        <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-3 pt-2 border-t border-slate-100">
                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors duration-150 shadow-sm"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        Yuborish
                    </button>
                    <a href="{{ route('messages.index') }}"
                       class="px-4 py-2.5 text-sm font-medium text-slate-500 hover:text-slate-700 transition-colors">
                        Bekor qilish
                    </a>
                </div>

            </form>
        </div>
    </div>

</x-dashboard-layout>
