@props(['label' => '','for' => ''])


<label for="{{$for}}" class="block mb-2 text-sm font-medium text-gray-900">{{$label}}</label>
<select id="{{$for}}" name="{{$for}}" value="{{old($for)}}"
    {{$attributes->merge([
    "class"=>"bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5",

    ])}}
>
    {{$slot}}
</select>

