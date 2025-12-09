@props(['hideNavigation' => false])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- Standaard titel --}}
        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <style>
            @php($theme = auth()->user()->theme_color ?? null)
            @if($theme)
                /* Theme actief: donker getinte theme-kleur als tekst + witte stroke */
                :root { 
                    --user-theme-color: {{ $theme }}; 
                    /* Donkerdere variant voor leesbaarheid */
                    --question-text-color: color-mix(in srgb, {{ $theme }} 55%, #000000); 
                    --user-theme-stroke:#ffffff; 
                }
            @else
                /* Geen theme: normale donkere tekst, geen witte stroke (stroke transparant) */
                :root { 
                    --user-theme-color: #f3f4f6; 
                    --question-text-color:#0f172a; 
                    --user-theme-stroke: transparent; 
                }
            @endif
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
    <body class="font-sans antialiased">
        <div class="app-bg">
            <div id="theme-overlay"></div>

        @unless($hideNavigation)
            @include('layouts.navigation')
        @endunless

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto text-center py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
