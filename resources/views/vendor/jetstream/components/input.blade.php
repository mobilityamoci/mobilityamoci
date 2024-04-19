@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'bg-gray-50 text-sm border border-gray-300 text-gray-900 rounded-md focus:ring-green-500 focus:border-green-500 block  p-2.5']) !!}>
