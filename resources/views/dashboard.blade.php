<x-app-layout>
    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-2">

            <form method="GET" action="{{ route('dashboard') }}" class="relative mb-8">

                <input type="hidden" name="categoria" value="{{ request('categoria') }}">
            
                <input
                    type="text" name="busca" value="{{ request('busca') }}"
                    placeholder="Buscar" 
                    class="w-full border-2 border-[#111fb4] rounded-xl px-4 py-2 pr-12"
                >

                <div x-data="{ open: false }" class="absolute right-3 top-1/2 -translate-y-1/2">
                    <button type="button" @click="open = !open" class="text-[#000000]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4.5h18M6 9h12M9 13.5h6M11 18h2" />
                        </svg>
                    </button>

                    <div x-show="open" x-cloak @click.outside="open = false"
                         class="absolute right-0 mt-3 w-48 bg-white border border-[#111fb4] rounded-lg shadow-lg z-10">
                        <a href="{{ route('dashboard', array_filter(['busca' => request('busca')])) }}"
                           class="block px-4 py-2 text-sm hover:bg-indigo-100">
                            Todas as categorias
                        </a>
                        @foreach ($categorias as $categoria)
                            <a href="{{ route('dashboard', array_filter(['busca' => request('busca'), 'categoria' => $categoria->id])) }}"
                               class="block px-4 py-2 text-sm hover:bg-indigo-100 {{ request('categoria') == $categoria->id ? 'font-bold bg-indigo-50' : '' }}">
                                {{ $categoria->nome }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </form>    

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse ($produtos as $produto)
                    <x-produto-card :produto="$produto">
                        @unless (usuario_e_admin())
                            <form method="POST" action="{{ route('carrinho.adicionar', $produto) }}">
                                @csrf
                                <button type="submit" class="bg-[#43A441] text-white text-sm rounded-full px-4 py-1">
                                    Comprar
                                </button>
                            </form>
                        @endunless
                        <a href="{{ route('produtos.show', $produto) }}" 
                        class="bg-[#111fb4] text-white text-sm rounded-full px-4 py-1">
                            Visualizar
                        </a>
                    </x-produto-card>
                @empty
                    <p class="col-span-full text-center text-gray-500">
                        Nenhum produto encontrado.
                    </p>
                @endforelse
            </div>

            <div class="mt-10">
                {{ $produtos->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
