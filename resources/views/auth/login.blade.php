<x-guest-layout>
    <div class="mb-8">
        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-sky-600">Welcome back</p>
        <h2 class="mt-3 text-3xl font-semibold tracking-tight text-slate-900">Sign in to CareQueue</h2>
        <p class="mt-3 text-sm leading-6 text-slate-500">Pick up where you left off and keep care moving.</p>
    </div>

    <x-auth-session-status class="mb-5" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <x-input-label for="email" class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-600" :value="__('Email address')" />
            <x-text-input id="email" class="mt-2 block w-full border-slate-300 bg-slate-50 px-4 py-3 text-sm focus:border-sky-500 focus:ring-sky-500" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-6">
            <div class="flex items-center justify-between">
                <x-input-label for="password" class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-600" :value="__('Password')" />
                @if (Route::has('password.request'))
                    <a class="text-xs font-semibold text-sky-600 hover:text-slate-900" href="{{ route('password.request') }}">{{ __('Forgot password?') }}</a>
                @endif
            </div>
            <x-text-input id="password" class="mt-2 block w-full border-slate-300 bg-slate-50 px-4 py-3 text-sm focus:border-sky-500 focus:ring-sky-500"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-5">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-sky-600 shadow-sm focus:ring-sky-500" name="remember">
                <span class="ms-2 text-sm text-slate-500">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="mt-8">
            <x-primary-button class="w-full justify-center rounded-3xl bg-slate-900 px-5 py-3 text-xs tracking-[0.16em] hover:bg-slate-800 focus:bg-slate-800 focus:ring-sky-500">
                {{ __('Sign in') }}
            </x-primary-button>
        </div>
    </form>

    <p class="mt-8 border-t border-slate-200 pt-6 text-center text-sm text-slate-500">
        New to CareQueue?
        <a class="font-semibold text-sky-600 hover:text-slate-900" href="{{ route('register') }}">Create an account</a>
    </p>
</x-guest-layout>
