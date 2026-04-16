@props(['status'])

@php
$config = match($status) {
    'pending' => ['dot' => 'bg-amber-400', 'text' => 'text-amber-700', 'bg' => 'bg-amber-50', 'label' => 'Kutilmoqda'],
    'queued' => ['dot' => 'bg-blue-400', 'text' => 'text-blue-700', 'bg' => 'bg-blue-50', 'label' => 'Navbatda'],
    'sending' => ['dot' => 'bg-blue-400 animate-pulse', 'text' => 'text-blue-700', 'bg' => 'bg-blue-50', 'label' => 'Yuborilmoqda'],
    'sent' => ['dot' => 'bg-indigo-400', 'text' => 'text-indigo-700', 'bg' => 'bg-indigo-50', 'label' => 'Yuborildi'],
    'delivered' => ['dot' => 'bg-emerald-400', 'text' => 'text-emerald-700', 'bg' => 'bg-emerald-50', 'label' => 'Yetkazildi'],
    'failed' => ['dot' => 'bg-red-400', 'text' => 'text-red-700', 'bg' => 'bg-red-50', 'label' => 'Xato'],
    'draft' => ['dot' => 'bg-slate-400', 'text' => 'text-slate-700', 'bg' => 'bg-slate-50', 'label' => 'Qoralama'],
    'running' => ['dot' => 'bg-blue-400 animate-pulse', 'text' => 'text-blue-700', 'bg' => 'bg-blue-50', 'label' => 'Ishlayapti'],
    'paused' => ['dot' => 'bg-amber-400', 'text' => 'text-amber-700', 'bg' => 'bg-amber-50', 'label' => 'To\'xtatilgan'],
    'completed' => ['dot' => 'bg-emerald-400', 'text' => 'text-emerald-700', 'bg' => 'bg-emerald-50', 'label' => 'Tugallangan'],
    'cancelled' => ['dot' => 'bg-red-400', 'text' => 'text-red-700', 'bg' => 'bg-red-50', 'label' => 'Bekor qilingan'],
    'online' => ['dot' => 'bg-emerald-400', 'text' => 'text-emerald-700', 'bg' => 'bg-emerald-50', 'label' => 'Online'],
    'offline' => ['dot' => 'bg-slate-400', 'text' => 'text-slate-700', 'bg' => 'bg-slate-50', 'label' => 'Offline'],
    default => ['dot' => 'bg-slate-400', 'text' => 'text-slate-700', 'bg' => 'bg-slate-50', 'label' => $status],
};
@endphp

<span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-xs font-medium {{ $config['bg'] }} {{ $config['text'] }}">
    <span class="w-1.5 h-1.5 rounded-full {{ $config['dot'] }}"></span>
    {{ $config['label'] }}
</span>
