<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://fonts.bunny.net/css?family=outfit:300,400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-ink">
        <div class="flex min-h-screen flex-col items-center justify-center px-6 py-10">
            <a href="/" class="font-display text-[17px] font-semibold tracking-[-0.03em] text-ink">
                {{ config('app.name', 'Laravel') }}
            </a>

            <div class="mt-8 w-full sm:max-w-md">
                <div class="card card-pad rise">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
