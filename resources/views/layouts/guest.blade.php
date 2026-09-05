<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'CareQueue') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-900 antialiased">
        <div class="min-h-screen bg-slate-50 px-4 py-10 sm:px-6">
            <div class="mx-auto grid min-h-[calc(100vh-5rem)] max-w-4xl overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-xl lg:grid-cols-2 lg:gap-8">
                <aside class="relative overflow-hidden bg-gradient-to-br from-sky-600 via-cyan-500 to-slate-900 px-7 py-10 text-white sm:px-10 lg:px-16 lg:py-16">
                    <div class="absolute -right-24 -top-24 h-64 w-64 rounded-full border-[32px] border-white/10"></div>
                    <div class="absolute -bottom-28 -left-20 h-64 w-64 rounded-full border-[24px] border-white/10"></div>
                    <div class="relative flex h-full flex-col">
                        <a href="/" class="inline-flex items-center gap-3 text-lg font-bold tracking-tight">
                            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-white text-sm font-bold text-sky-700">CQ</span>
                            CareQueue
                        </a>
                        <div class="mt-auto max-w-sm pb-4 pt-20 lg:pb-8">
                            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-sky-100">Calm care, clear queues</p>
                            <h1 class="mt-5 text-4xl font-semibold leading-[1.08] tracking-tight sm:text-5xl">Less waiting. More care.</h1>
                            <p class="mt-6 max-w-xs text-base leading-7 text-sky-50/80">A simple place for patients, doctors, and hospital teams to keep every visit moving.</p>
                            <div class="mt-10 flex items-center gap-3 text-sm text-sky-50/75">
                                <span class="h-px w-10 bg-white/70"></span>
                                <span>Built for better visits</span>
                            </div>
                        </div>
                    </div>
                </aside>

                <main class="flex items-center px-6 py-10 sm:px-12 lg:px-16 lg:py-14">
                    <div class="w-full max-w-lg">
                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>
    </body>
</html>
