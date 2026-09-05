<x-guest-layout>
    <div class="mb-8">
        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-sky-600">Join CareQueue</p>
        <h2 class="mt-3 text-3xl font-semibold tracking-tight text-slate-900">Create your account</h2>
        <p class="mt-3 text-sm leading-6 text-slate-500">Set up your patient profile to join and track hospital queues.</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

<div class="grid gap-5 sm:grid-cols-2">
<div>
    <x-input-label for="first_name" class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-600" :value="__('First name')" />
    <x-text-input id="first_name"
        class="mt-2 block w-full border-slate-300 bg-slate-50 px-4 py-3 text-sm focus:border-sky-500 focus:ring-sky-500"
        type="text"
        name="first_name"
        :value="old('first_name')"
        required
        autofocus />

    <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
</div>

<!-- Last Name -->
<div>
    <x-input-label for="last_name" class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-600" :value="__('Last name')" />
    <x-text-input id="last_name"
        class="mt-2 block w-full border-slate-300 bg-slate-50 px-4 py-3 text-sm focus:border-sky-500 focus:ring-sky-500"
        type="text"
        name="last_name"
        :value="old('last_name')"
        required />

    <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
</div>
</div>

<div class="mt-5">
    <x-input-label for="phone" class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-600" :value="__('Phone number')" />
    <x-text-input id="phone"
        class="mt-2 block w-full border-slate-300 bg-slate-50 px-4 py-3 text-sm focus:border-sky-500 focus:ring-sky-500"
        type="text"
        name="phone"
        :value="old('phone')" />

    <x-input-error :messages="$errors->get('phone')" class="mt-2" />
</div>

        <!-- Email Address -->
        <div class="mt-5">
            <x-input-label for="email" class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-600" :value="__('Email address')" />
            <x-text-input id="email" class="mt-2 block w-full border-slate-300 bg-slate-50 px-4 py-3 text-sm focus:border-sky-500 focus:ring-sky-500" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-5">
            <x-input-label for="password" class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-600" :value="__('Password')" />

            <x-text-input id="password" class="mt-2 block w-full border-slate-300 bg-slate-50 px-4 py-3 text-sm focus:border-sky-500 focus:ring-sky-500"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-5">
            <x-input-label for="password_confirmation" class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-600" :value="__('Confirm password')" />

            <x-text-input id="password_confirmation" class="mt-2 block w-full border-slate-300 bg-slate-50 px-4 py-3 text-sm focus:border-sky-500 focus:ring-sky-500"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mt-8">
            <x-primary-button class="w-full justify-center rounded-3xl bg-slate-900 px-5 py-3 text-xs tracking-[0.16em] hover:bg-slate-800 focus:bg-slate-800 focus:ring-sky-500">
                {{ __('Create account') }}
            </x-primary-button>
        </div>
    </form>

    <p class="mt-8 border-t border-slate-200 pt-6 text-center text-sm text-slate-500">
        Already have an account?
        <a class="font-semibold text-sky-600 hover:text-slate-900" href="{{ route('login') }}">Sign in</a>
    </p>
</x-guest-layout>
