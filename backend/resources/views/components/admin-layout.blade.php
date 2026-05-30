@props(['title' => 'Admin'])
<x-admin.layouts.admin :title="$title">
    @isset($actions)
        <x-slot name="actions">{{ $actions }}</x-slot>
    @endisset
    {{ $slot }}
</x-admin.layouts.admin>
