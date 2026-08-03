<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'CareQueue') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-slate-50 text-slate-900 min-h-screen flex items-center justify-center py-10">
        <div class="w-full max-w-4xl mx-auto px-6">
            <div class="bg-white shadow-xl rounded-3xl overflow-hidden border border-slate-200">
                <div class="grid lg:grid-cols-2 gap-0 lg:gap-8">
                    <div class="p-10 lg:p-16 bg-gradient-to-br from-sky-600 via-cyan-500 to-slate-900 text-white">
                        <h1 class="text-4xl font-bold tracking-tight mb-4">CareQueue</h1>
                        <p class="text-lg leading-8 mb-8">A fast, easy queue management system for hospitals. Patients join queues, doctors serve visits, and admins manage departments from one clean dashboard.</p>
                        {{--
                        <div class="space-y-4">
                            <div class="rounded-3xl bg-white/10 p-5">
                                <p class="text-sm uppercase tracking-[0.2em] text-slate-200">Doctor login</p>
                                <p class="mt-2 text-lg font-semibold">doctor@example.com</p>
                                <p class="text-sm text-slate-200">password</p>
                            </div>
                            <div class="rounded-3xl bg-white/10 p-5">
                                <p class="text-sm uppercase tracking-[0.2em] text-slate-200">Patient login</p>
                                <p class="mt-2 text-lg font-semibold">test@example.com</p>
                                <p class="text-sm text-slate-200">password</p>
                            </div>
                            <div class="rounded-3xl bg-white/10 p-5">
                                <p class="text-sm uppercase tracking-[0.2em] text-slate-200">Admin login</p>
                                <p class="mt-2 text-lg font-semibold">admin@example.com</p>
                                <p class="text-sm text-slate-200">password</p>
                            </div>
                        </div>
                        --}}
                    </div>
                    <div class="p-10 lg:p-16 flex flex-col justify-center">
                        <div class="mb-10">
                            <p class="text-sm uppercase tracking-[0.25em] text-slate-500">Welcome to</p>
                            <h2 class="mt-4 text-3xl font-semibold text-slate-900">CareQueue</h2>
                            <p class="mt-3 text-slate-600">Login to start managing patient queues, doctor assignments, and department workflows.</p>
                        </div>
                        <div class="grid gap-4">
                            <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-3xl bg-slate-900 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-200/10 hover:bg-slate-800 transition">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-3xl border border-slate-300 bg-white px-6 py-3 text-sm font-semibold text-slate-900 hover:bg-slate-50 transition">Register</a>
                            @endif
                        </div>
                        {{-- <div class="mt-10 text-sm text-slate-500">
                            <p>Use the seeded credentials shown to access the app quickly.</p>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
