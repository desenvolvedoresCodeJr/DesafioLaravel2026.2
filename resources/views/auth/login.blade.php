<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <h2 class="text-5xl font-bold mb-6 text-gray-900">{{ __('Login') }}</h2>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <!-- <x-input-label for="email" :value="__('Email')" /> -->
            <x-text-input id="email" class="block mt-1 w-full bg-blue-100 border-none h-12 rounded-sm" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="Email"/>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <!-- <x-input-label for="password" :value="__('Password')" /> -->

            <x-text-input id="password" class="block mt-1 w-full bg-blue-100 border-none h-12 rounded-sm"
                            type="password"
                            name="password"
                            required autocomplete="current-password" placeholder="Senha"/>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <!-- <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div> -->

        <div class="mt-4">
            <x-primary-button class="w-full justify-center py-3 h-14 bg-blue-800 rounded-xl text-base font-bold">
                {{ __('Entrar') }}
            </x-primary-button>
        </div>

        <div class="flex items-center justify-between mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Esqueceu sua senha?') }}
                </a>
            @endif

            @if (Route::has('register'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('register') }}">
                    {{ __('Cadastre-se!') }}
                </a>
            @endif
        </div>
    </form>
</x-guest-layout>
