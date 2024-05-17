<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Mobilityamoci') }}</title>
    @include('layouts.body-styles')

</head>
<body class="font-sans antialiased">
<x-jet-banner/>

<div class="min-h-screen bg-gray-100"
     style="background-size: cover; background-image:   url({{asset('mappa-piacenza.png')}})">
    @livewire('student-navigation-menu')

    <div class="flex ">
        <!-- Card Column -->
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
                    {{ $slot }}
                </main>
        </div>

@include('layouts.body-scripts')
</body>
</html>
