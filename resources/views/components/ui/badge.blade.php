@props(['type' => 'gray'])

@php
$types = [
    'green' => 'bg-green-100 text-green-700',
    'red' => 'bg-red-100 text-red-700',
    'gray' => 'bg-gray-100 text-gray-700',
];
@endphp

<span class="px-2 py-1 text-xs rounded {{ $types[$type] }}">
    {{ $slot }}
</span>
