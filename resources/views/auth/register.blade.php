<x-guest-layout>
    <h2 class="text-5xl font-bold mb-6 text-gray-900">{{ __('Cadastre-se') }}</h2>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <!-- <x-input-label for="name" :value="__('Name')" /> -->
            <x-text-input id="name" class="block mt-1 w-full bg-blue-100 border-none h-12 rounded-sm" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Nome"/>
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <!-- <x-input-label for="email" :value="__('Email')" /> -->
            <x-text-input id="email" class="block mt-1 w-full bg-blue-100 border-none h-12 rounded-sm" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="Email"/>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <!-- <x-input-label for="password" :value="__('Password')" /> -->

            <x-text-input id="password" class="block mt-1 w-full bg-blue-100 border-none h-12 rounded-sm"
                            type="password"
                            name="password"
                            required autocomplete="new-password" placeholder="Senha"/>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <!-- <x-input-label for="password_confirmation" :value="__('Confirm Password')" /> -->

            <x-text-input id="password_confirmation" class="block mt-1 w-full bg-blue-100 border-none h-12 rounded-sm"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" placeholder="Confirmar Senha"/>

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-primary-button class="w-full justify-center py-3 h-14 bg-blue-800 rounded-xl text-base font-bold">
                {{ __('Criar Conta') }}
            </x-primary-button>
        </div>

        <div class="flex items-center justify-start mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Já tem conta?') }}
            </a>
        </div>
    </form>
</x-guest-layout>
