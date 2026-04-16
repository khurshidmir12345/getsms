<x-dashboard-layout title="SMS Yuborish">
    <div class="max-w-2xl">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <form method="POST" action="{{ route('messages.store') }}">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Telefon raqam</label>
                    <input type="text" name="phone_to" value="{{ old('phone_to') }}" placeholder="+998901234567"
                        class="w-full rounded-lg border-gray-300 text-sm @error('phone_to') border-red-300 @enderror">
                    @error('phone_to') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Qurilma</label>
                    <select name="device_id" class="w-full rounded-lg border-gray-300 text-sm">
                        <option value="">Avtomatik tanlash</option>
                        @foreach($devices as $device)
                            <option value="{{ $device->id }}" {{ $device->status === 'online' ? '' : 'disabled' }}>
                                {{ $device->name }} ({{ $device->phone_number }}) — {{ $device->status }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Shablon</label>
                    <select id="template_select" class="w-full rounded-lg border-gray-300 text-sm">
                        <option value="">Shablonsiz</option>
                        @foreach($templates as $template)
                            <option value="{{ $template->id }}" data-body="{{ $template->body }}">{{ $template->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Xabar matni</label>
                    <textarea name="body" id="message_body" rows="4" placeholder="SMS matnini yozing..."
                        class="w-full rounded-lg border-gray-300 text-sm @error('body') border-red-300 @enderror">{{ old('body') }}</textarea>
                    <p class="text-xs text-gray-400 mt-1"><span id="char_count">0</span> belgi | <span id="sms_count">0</span> SMS</p>
                    @error('body') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">
                        Yuborish
                    </button>
                    <a href="{{ route('messages.index') }}" class="px-6 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200">
                        Bekor qilish
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('template_select').addEventListener('change', function() {
            const option = this.options[this.selectedIndex];
            if (option.dataset.body) {
                document.getElementById('message_body').value = option.dataset.body;
            }
        });

        document.getElementById('message_body').addEventListener('input', function() {
            const len = this.value.length;
            document.getElementById('char_count').textContent = len;
            const hasUnicode = /[^\x00-\x7F]/.test(this.value);
            const smsLen = hasUnicode ? 70 : 160;
            const multiLen = hasUnicode ? 67 : 153;
            const count = len <= smsLen ? 1 : Math.ceil(len / multiLen);
            document.getElementById('sms_count').textContent = len === 0 ? 0 : count;
        });
    </script>
</x-dashboard-layout>
