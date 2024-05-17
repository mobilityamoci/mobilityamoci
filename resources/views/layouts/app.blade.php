<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Mobilityamoci') }}</title>

    @include('layouts.body-styles')

</head>
<body class="font-sans antialiased" style="height: 100%; overflow: hidden">
<x-jet-banner/>

<div class="min-h-screen bg-white" id="background-map"
     style="background-size: cover; background-image:   url({{asset('mappa-piacenza.png')}})"
    {{--     style="background-size: cover; background-image: url('data:image/png;base64,')"--}}
>


    <div class="flex ">
        <!-- Card Column -->
        <div class="md:w-10/12 flex align-middle">
            <div style="overflow:scroll; max-height: 85vh; overflow-x: hidden"
                 class="p-3 bg-white h-fit w-full m-12 border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 bg-opacity-75">
                <!-- Page Heading -->
                @if (isset($header))
                    <header class="bg-white shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <!-- Page Content -->
                <main>
                    @if (isset($slot))
                        {{ $slot }}
                    @endif
                    @yield('content')
                </main>
            </div>
        </div>
        <div class=" md:w-2/12 justify-center flex items-center h-screen bg-transparent"
             style="position: sticky !important; top: 0 !important;">
            @livewire('navigation-menu')
        </div>
    </div>
</div>

@include('layouts.body-scripts')

</body>

</html>
