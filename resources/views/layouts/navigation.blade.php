<nav x-data="{ open: false }" class="bg-[#111fb4]">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-32">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <img src="{{ asset('images/logoBranca.png') }}" alt="Logo Micro Point" class="h-14 w-auto rounded-lg">
                    </a>
                </div>
            </div>

            <!-- Acesso -->
            <div class="flex items-center gap-4">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="bg-white rounded-lg px-4 py-1 text-left focus:outline-none h-14">
                            <p class="font-bold text-sm leading-tight text-gray-900">{{ Auth::user()->name }}</p>
                            <p class="text-xs leading-tight text-gray-500">
                                {{ usuario_e_admin() ? 'administrador' : 'usuário' }}
                            </p>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Perfil') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>

                @if (usuario_e_admin())
                    <a href="{{ route('usuarios.index') }}" class="text-white text-sm font-semibold">
                        Usuários
                    </a>
                    <a href="{{ route('admins.index') }}" class="text-white text-sm font-semibold">
                        Administradores
                    </a>
                @endif

                <a href="{{ route('produtos.gerenciar') }}" class="text-white text-sm font-semibold">
                    {{ Auth::user()->is_admin ? 'Produtos' : 'Meus produtos' }}
                </a>

                @unless (usuario_e_admin())
                    <a href="{{ route('compras.index') }}" class="text-white text-sm font-semibold">Compras</a>
                @endunless

                <a href="{{ route('vendas.index') }}" class="text-white text-sm font-semibold">Vendas</a>

                <!-- Carrinho -->
                @unless (usuario_e_admin())
                    <a href="{{ route('carrinho.index') }}" class="text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                        </svg>
                    </a>
                @endunless

                <!-- Home -->
                <a href="{{ route('dashboard') }}" class="text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>