@props(['color' => 'blue'])

@php
$colors = [
    'blue' => 'bg-blue-600 hover:bg-blue-700',
    'red' => 'bg-red-600 hover:bg-red-700',
    'gray' => 'bg-gray-200 hover:bg-gray-300 text-black',
    'yellow' => 'bg-yellow-600 hover:bg-yellow-700',
];
@endphp

<button {{ $attributes->merge([
    'class' => $colors[$color] . ' text-white px-4 py-2 rounded-lg shadow'
]) }}>
    {{ $slot }}
</button>
