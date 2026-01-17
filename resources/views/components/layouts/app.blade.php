<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles

        <title>{{ $title ?? 'Loan Management' }}</title>
    </head>
    <body>
        <div class="min-h-screen bg-gray-50">
            <x-topnav />

            <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                {{ $slot }}
            </main>
        </div>

        @livewireScripts
    </body>
</html>
