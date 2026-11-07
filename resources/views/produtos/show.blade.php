<x-app-layout>
    <div class="py-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <div>
                    @if ($produto->foto_url)
                        <img src="{{ $produto->foto_url }}" alt="{{ $produto->nome }}" class="w-full h-96 object-cover border-2 border-[#111fb4]">
                    @else
                        <div class="w-full h-96 border-2 border-[#111fb4] bg-gray-200 flex items-center justify-center text-gray-500">
                            Sem foto
                        </div>
                    @endif
                </div>

                <div>
                    <h1 class="text-2xl">{{ $produto->nome }}</h1>

                    <p class="mt-8 text-xl"><strong>Categoria:</strong> {{ $produto->categoria->nome }}</p>

                    <p class="mt-8 text-2xl font-bold">{{ formatar_dinheiro($produto->preco) }}</p>

                    @unless (usuario_e_admin())
                        <form method="POST" action="{{ route('carrinho.adicionar', $produto) }}">
                            @csrf
                            <div class="mt-8 flex items-center gap-3">
                                <label for="quantidade" class="text-xl">Quantidade:</label>
                                <input type="number" id="quantidade" name="quantidade" min="1"
                                    max="{{ $produto->quantidade }}" value="1"
                                    class="w-28 border border-gray-500 px-2 py-1"
                                >
                            </div>

                            <button type="submit" class="mt-6 block w-full max-w-xs text-center bg-green-600 hover:bg-green-700 text-white text-xl rounded-lg py-3">
                                Comprar
                            </button>
                        </form>
                    @endunless
                </div>
            </div>

            <div class="mt-16">
                <h2 class="text-xl font-bold">Descrição</h2>
                <p class="mt-4 text-lg whitespace-pre-line">{{ $produto->descricao }}</p>
            </div>

            <div class="mt-12">
                <h2 class="text-xl font-bold">Informações do anunciante</h2>
                <p class="mt-4 text-lg">{{ $produto->usuario->name }}</p>
                <p class="mt-4 text-lg">{{ $produto->usuario->telefone }}</p>
            </div>

        </div>
    </div>
</x-app-layout>