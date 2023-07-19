{{--@props(['active'])--}}

{{--@php--}}
{{--$classes = ($active ?? false)--}}
{{--            ? 'bg-white-200 inline-flex items-center px-1 pt-1 border-b-2 border-indigo-400 text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-indigo-700 transition'--}}
{{--            : 'bg-white-200 inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition';--}}
{{--@endphp--}}

{{--<a {{ $attributes->merge(['class' => $classes]) }}>--}}
{{--    {{ $slot }}--}}
{{--</a>--}}
@props(['active', 'href' => ''])

@php
    $classes = ($active ?? false)
                ? 'flex p-2 text-green rounded-md shadow-lg w-fit bg-green-500 bg-opacity-75 transition'
                : 'flex p-2 text-green rounded-md shadow-lg w-fit bg-white bg-opacity-75 transition';
@endphp

<a style="z-index: 0" {{ $attributes->merge(['class' => $classes, 'href' => $href]) }}>
    {{ $slot }}
</a>
