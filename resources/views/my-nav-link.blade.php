@props(['active', 'href'])

@php
    $classes = ($active ?? false)
                ? 'flex p-2 rounded-md  shadow-lg w-fit bg-green-700 transition'
                : 'flex p-2 rounded-md  shadow-lg w-fit bg-white transition';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }} href="{{$href}}">
    {{ $slot }}
</a>
