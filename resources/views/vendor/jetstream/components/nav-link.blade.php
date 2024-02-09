
@props(['active', 'href' => ''])

@php
    $classes = ($active ?? false)
                ? 'flex p-2 text-green rounded-md shadow-lg w-fit bg-green-500 bg-opacity-75 transition'
                : 'flex p-2 text-green rounded-md shadow-lg w-fit bg-white bg-opacity-75 transition';
@endphp

<a style="z-index: 0" {{ $attributes->merge(['class' => $classes, 'href' => $href]) }}>
    {{ $slot }}
</a>
