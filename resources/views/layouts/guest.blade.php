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

        <!-- Background styling aligned with authenticated app layout -->
        <style>
            :root { --user-theme-color: {{ optional(auth()->user())->theme_color ?? '#f3f4f6' }}; }
            .app-bg { position:relative; min-height:100vh; background: radial-gradient(circle at 30% 20%, var(--user-theme-color) 0%, #ffffff 70%); transition: background .5s ease; }
            #theme-overlay { position:absolute; inset:0; background: var(--user-theme-color); opacity:.55; mix-blend-mode:multiply; pointer-events:none; transition: background .4s ease, opacity .4s ease; }
            @media (prefers-color-scheme: dark) {
                .app-bg { background: radial-gradient(circle at 30% 20%, var(--user-theme-color) 0%, #1f2937 70%); }
                body { background:#0f172a; }
            }
        </style>
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="app-bg">
            <div id="theme-overlay"></div>
            <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
                <div>
                    <a href="/">
                        <x-application-logo class="w-20 h-20 rounded-lg shadow fill-current text-gray-500" />
                    </a>
                </div>
                <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white/90 backdrop-blur-sm shadow-md overflow-hidden sm:rounded-xl border border-white/40">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
