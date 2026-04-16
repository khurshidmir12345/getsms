@props(['title' => 'Dashboard'])

<x-layouts.dashboard :title="$title">
    <x-slot name="actions">
        @if(isset($actions))
            {{ $actions }}
        @endif
    </x-slot>
    {{ $slot }}
</x-layouts.dashboard>
