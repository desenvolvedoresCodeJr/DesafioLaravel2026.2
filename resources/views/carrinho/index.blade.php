<x-app-layout>
    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex flex-wrap items-center gap-6 mb-6">
                <h1 class="text-3xl font-bold">Carrinho de Compras</h1>

                <a href="{{ route('dashboard') }}" class="text-[#111fb4] underline ml-auto text-lg">Continuar comprando</a>

                @if ($itens->isNotEmpty())
                    <form method="POST" action="{{ route('carrinho.esvaziar') }}" class="ml-auto">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-900 font-semibold">Esvaziar carrinho</button>
                    </form>
                @endif
            </div>

            @if (session('erro'))
                <div class="mb-4 rounded-lg bg-red-100 text-red-800 px-4 py-3">{{ session('erro') }}</div>
            @endif
            @if (session('status'))
                <div class="mb-4 rounded-lg bg-green-100 text-green-800 px-4 py-3">{{ session('status') }}</div>
            @endif

            <div class="space-y-4">
                @forelse ($itens as $item)
                    <div class="border border-[#111fb4] p-4 flex items-center gap-6">
                        @if ($item->produto->foto_url)
                            <img src="{{ $item->produto->foto_url }}" class="w-36 h-28 object-cover">
                        @else
                            <div class="w-36 h-28 bg-gray-200 flex items-center justify-center text-xs text-gray-500">Sem foto</div>
                        @endif

                        <div class="flex-1 text-center">
                            <p class="text-lg">{{ $item->produto->nome }}</p>
                            <p class="mt-3 text-lg">{{ formatar_dinheiro($item->produto->preco) }}</p>
                        </div>

                        <form method="POST" action="{{ route('carrinho.atualizar', $item) }}" class="flex items-center gap-2">
                            @csrf
                            @method('PATCH')
                            <input type="number" name="quantidade" min="1" max="{{ $item->produto->quantidade }}"
                                   value="{{ $item->quantidade }}" onchange="this.form.submit()"
                                   class="w-20 border border-[#111fb4] text-center">
                        </form>

                        <form method="POST" action="{{ route('carrinho.remover', $item) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 text-sm underline">Remover</button>
                        </form>
                    </div>
                @empty
                    <p class="py-16 text-center text-gray-500">Seu carrinho está vazio.</p>
                @endforelse
            </div>

            <div class="mt-10 border border-[#111fb4] rounded-2xl p-6 text-lg">
                <div class="flex justify-between mt-4"><span>Total</span><span>{{ formatar_dinheiro($total) }}</span></div>
            </div>

            @if ($itens->isNotEmpty())
                <form method="POST" action="{{ route('checkout.iniciar') }}" class="mt-8">
                    @csrf
                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white text-2xl rounded-xl py-3">
                        Fechar Carrinho
                    </button>
                </form>
            @endif
        </div>
    </div>
</x-app-layout>