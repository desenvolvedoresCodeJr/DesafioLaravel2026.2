<x-app-layout>
    <div class="py-12">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                <h1 class="text-3xl font-bold">Gerenciamento de Produtos</h1>

                @if (auth()->user()->is_admin)
                <a href="{{ route('grafico.produtos') }}" class="bg-[#111fb4] text-white text-lg font-semibold rounded-lg px-8 py-3">Gráfico</a>
                @endif

                @unless (auth()->user()->is_admin)
                <button type="button"
                    x-data=""
                    x-on:click.prevent="$dispatch('open-modal', 'criar-produto')"
                    class="ml-auto bg-green-600 hover:bg-green-700 text-white text-lg font-semibold rounded-lg px-8 py-3">
                    + Adicionar Produto
                </button>
                @endunless
            </div>

            @if (session('status'))
            <div class="mb-4 rounded-lg bg-green-100 text-green-800 px-4 py-3">{{ session('status') }}</div>
            @endif
            @if (session('erro'))
            <div class="mb-4 rounded-lg bg-red-100 text-red-800 px-4 py-3">{{ session('erro') }}</div>
            @endif
            @if ($errors->any())
            <div class="mb-4 rounded-lg bg-red-100 text-red-800 px-4 py-3">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $erro)
                    <li>{{ $erro }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="border border-[#111fb4] rounded-2xl overflow-hidden">
                <table class="w-full text-center">
                    <thead>
                        <tr class="border-b border-[#111fb4] text-lg">
                            <th class="py-5">Foto</th>
                            <th class="py-5">Produto</th>
                            <th class="py-5">Criador</th>
                            <th class="py-5">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($produtos as $produto)
                        <tr>
                            <td class="py-4">
                                @if ($produto->foto_url)
                                <img src="{{ $produto->foto_url }}" alt="{{ $produto->nome }}" class="w-32 h-24 object-cover mx-auto">
                                @else
                                <div class="w-32 h-24 bg-gray-200 mx-auto flex items-center justify-center text-xs text-gray-500">Sem foto</div>
                                @endif
                            </td>
                            <td class="py-4 text-lg">{{ $produto->nome }}</td>
                            <td class="py-4 text-lg">{{ $produto->usuario->name }}</td>
                            <td class="py-4">
                                <div class="flex justify-center gap-2">
                                    <button type="button" title="Ver"
                                        x-data=""
                                        x-on:click="
                                            $dispatch('ver-produto', @js([
                                                'nome'       => $produto->nome,
                                                'descricao'  => $produto->descricao,
                                                'categoria'  => $produto->categoria->nome,
                                                'preco'      => 'R$ ' . number_format($produto->preco, 2, ',', '.'),
                                                'quantidade' => $produto->quantidade,
                                                'foto'       => $produto->foto_url,
                                            ]));
                                            $dispatch('open-modal', 'ver-produto');
                                        "
                                        class="w-7 h-7 border border-[#111fb4] text-[#111fb4] rounded flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12z" />
                                            <circle cx="12" cy="12" r="3" />
                                        </svg>
                                    </button>

                                    <button type="button" title="Editar"
                                        @disabled(auth()->user()->is_admin)
                                        x-data=""
                                        x-on:click="
                                        $dispatch('editar-produto', @js([
                                        'action' => route('produtos.update', $produto),
                                        'nome' => $produto->nome,
                                        'descricao' => $produto->descricao,
                                        'preco' => $produto->preco,
                                        'quantidade' => $produto->quantidade,
                                        'categoria_id' => $produto->categoria_id,
                                        'foto' => $produto->foto_url,
                                        ]));
                                        $dispatch('open-modal', 'editar-produto');
                                        "
                                        class="w-7 h-7 border border-[#111fb4] text-[#111fb4] rounded flex items-center justify-center disabled:opacity-30 disabled:cursor-not-allowed">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M16 3l5 5-9 9H7v-5l9-9z" />
                                        </svg>
                                    </button>

                                    <button type="button" title="Excluir"
                                        x-data=""
                                        x-on:click="
                                            $dispatch('deletar-produto', @js([
                                                'action' => route('produtos.destroy', $produto),
                                            ]));
                                            $dispatch('open-modal', 'deletar-produto');
                                        "
                                        class="w-7 h-7 border border-red-400 bg-red-200 text-red-700 rounded flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 7h12M9 7V4h6v3m-8 0l1 13h8l1-13" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-16 text-gray-500">Nenhum produto encontrado.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginação --}}
            <div class="mt-8">
                {{ $produtos->links() }}
            </div>

        </div>
    </div>

    {{-- Modal Anunciar --}}
    @unless (auth()->user()->is_admin)
    <x-modal name="criar-produto" maxWidth="lg" :show="old('_form') === 'criar'">
        <div class="border-2 border-[#111fb4] rounded-lg overflow-hidden">

            <div class="bg-[#b0b4e2] py-3 text-center">
                <h2 class="text-xl font-bold text-[#111fb4]">Anunciar Produto</h2>
            </div>

            <form method="POST" action="{{ route('produtos.store') }}" enctype="multipart/form-data"
                x-data="{ preview: null }" class="p-6">
                @csrf
                <input type="hidden" name="_form" value="criar">

                <label class="block cursor-pointer border-2 border-dashed border-[#111fb4] rounded-2xl h-40 flex items-center justify-center overflow-hidden">
                    <template x-if="!preview">
                        <span class="text-gray-400 text-lg">Adicione a foto do produto</span>
                    </template>
                    <template x-if="preview">
                        <img :src="preview" class="w-full h-full object-cover">
                    </template>
                    <input type="file" name="foto" accept="image/*" class="hidden"
                        @change="preview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null">
                </label>
                <x-input-error :messages="$errors->get('foto')" class="mt-1" />

                <label for="criar-nome" class="block mt-5 text-sm">Nome</label>
                <input id="criar-nome" type="text" name="nome" value="{{ old('nome') }}" required
                    class="w-full border border-[#111fb4] rounded-full px-4 py-2">
                <x-input-error :messages="$errors->get('nome')" class="mt-1" />

                <label for="criar-descricao" class="block mt-4 text-sm">Descrição</label>
                <textarea id="criar-descricao" name="descricao" rows="4"
                    class="w-full border border-[#111fb4] rounded-2xl px-4 py-2">{{ old('descricao') }}</textarea>
                <x-input-error :messages="$errors->get('descricao')" class="mt-1" />

                <div class="grid grid-cols-2 gap-4 mt-4">
                    <div>
                        <label for="criar-preco" class="block text-sm">Preço</label>
                        <input id="criar-preco" type="number" step="0.01" min="0" name="preco" value="{{ old('preco') }}" required
                            class="w-full border border-[#111fb4] rounded-full px-4 py-2">
                        <x-input-error :messages="$errors->get('preco')" class="mt-1" />
                    </div>
                    <div>
                        <label for="criar-quantidade" class="block text-sm">Quantidade</label>
                        <input id="criar-quantidade" type="number" min="1" name="quantidade" value="{{ old('quantidade', 1) }}" required
                            class="w-full border border-[#111fb4] rounded-full px-4 py-2">
                        <x-input-error :messages="$errors->get('quantidade')" class="mt-1" />
                    </div>
                </div>

                <label for="criar-categoria" class="block mt-4 text-sm">Categoria</label>
                <select id="criar-categoria" name="categoria_id" required
                    class="w-full border border-[#111fb4] rounded-full px-4 py-2">
                    <option value="">Selecione</option>
                    @foreach ($categorias as $categoria)
                    <option value="{{ $categoria->id }}" @selected(old('categoria_id')==$categoria->id)>
                        {{ $categoria->nome }}
                    </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('categoria_id')" class="mt-1" />

                <div class="mt-6 flex justify-center gap-6">
                    <button type="button" x-on:click="$dispatch('close')"
                        class="w-40 bg-gray-300 hover:bg-gray-400 rounded-lg py-2 text-lg">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="w-40 bg-green-600 hover:bg-green-700 text-white rounded-lg py-2 text-lg">
                        Anunciar
                    </button>
                </div>
            </form>
        </div>
    </x-modal>
    @endunless

    {{-- Modal Visualizar --}}
    <x-modal name="ver-produto" maxWidth="lg">
        <div x-data="{ p: {} }"
            @ver-produto.window="p = $event.detail"
            class="border-2 border-[#111fb4] rounded-lg overflow-hidden">

            <div class="bg-[#b0b4e2] py-3 text-center">
                <h2 class="text-xl font-bold text-[#111fb4]">Visualizar Produto</h2>
            </div>

            <div class="p-6">
                <div class="mx-auto w-64 h-44 border-2 border-dashed border-[#111fb4] rounded-2xl overflow-hidden flex items-center justify-center">
                    <template x-if="p.foto">
                        <img :src="p.foto" class="w-full h-full object-cover">
                    </template>
                    <template x-if="!p.foto">
                        <span class="text-gray-400">Sem foto</span>
                    </template>
                </div>

                <label class="block mt-5 text-sm">Nome</label>
                <input type="text" readonly :value="p.nome"
                    class="w-full border border-gray-300 rounded-full px-4 py-2 bg-white">

                <label class="block mt-4 text-sm">Descrição</label>
                <textarea readonly rows="4" x-text="p.descricao"
                    class="w-full border border-gray-300 rounded-2xl px-4 py-2 bg-white"></textarea>

                <div class="grid grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block text-sm">Preço</label>
                        <input type="text" readonly :value="p.preco"
                            class="w-full border border-gray-300 rounded-full px-4 py-2 bg-white">
                    </div>
                    <div>
                        <label class="block text-sm">Quantidade</label>
                        <input type="text" readonly :value="p.quantidade"
                            class="w-full border border-gray-300 rounded-full px-4 py-2 bg-white">
                    </div>
                </div>

                <label class="block mt-4 text-sm">Categoria</label>
                <input type="text" readonly :value="p.categoria"
                    class="w-full border border-gray-300 rounded-full px-4 py-2 bg-white">

                <div class="mt-6 flex justify-center">
                    <button type="button" x-on:click="$dispatch('close')"
                        class="w-48 bg-gray-300 hover:bg-gray-400 rounded-lg py-2 text-lg">
                        Fechar
                    </button>
                </div>
            </div>
        </div>
    </x-modal>

    {{-- Modal Editar --}}
    @unless (auth()->user()->is_admin)
    <x-modal name="editar-produto" maxWidth="lg">
        <div x-data="{ p: {}, preview: null }"
            @editar-produto.window="p = $event.detail; preview = null"
            class="border-2 border-[#111fb4] rounded-lg overflow-hidden">

            <div class="bg-[#b0b4e2] py-3 text-center">
                <h2 class="text-xl font-bold text-[#111fb4]">Editar Produto</h2>
            </div>

            <form method="POST" :action="p.action" enctype="multipart/form-data" class="p-6">
                @csrf
                @method('PUT')

                <label class="block mx-auto w-64 h-44 cursor-pointer border-2 border-dashed border-[#111fb4] rounded-2xl overflow-hidden flex items-center justify-center">
                    <template x-if="preview || p.foto">
                        <img :src="preview || p.foto" class="w-full h-full object-cover">
                    </template>
                    <template x-if="!preview && !p.foto">
                        <span class="text-gray-400">Adicione a foto do produto</span>
                    </template>
                    <input type="file" name="foto" accept="image/*" class="hidden"
                        @change="preview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null">
                </label>

                <label class="block mt-5 text-sm">Nome</label>
                <input type="text" name="nome" :value="p.nome" required
                    class="w-full border border-[#111fb4] rounded-full px-4 py-2">

                <label class="block mt-4 text-sm">Descrição</label>
                <textarea name="descricao" rows="4" x-text="p.descricao"
                    class="w-full border border-[#111fb4] rounded-2xl px-4 py-2"></textarea>

                <div class="grid grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block text-sm">Preço</label>
                        <input type="number" step="0.01" min="0" name="preco" :value="p.preco" required
                            class="w-full border border-[#111fb4] rounded-full px-4 py-2">
                    </div>
                    <div>
                        <label class="block text-sm">Quantidade</label>
                        <input type="number" min="1" name="quantidade" :value="p.quantidade" required
                            class="w-full border border-[#111fb4] rounded-full px-4 py-2">
                    </div>
                </div>

                <label class="block mt-4 text-sm">Categoria</label>
                <select name="categoria_id" required
                    class="w-full border border-[#111fb4] rounded-full px-4 py-2">
                    @foreach ($categorias as $categoria)
                    <option value="{{ $categoria->id }}" :selected="p.categoria_id == {{ $categoria->id }}">
                        {{ $categoria->nome }}
                    </option>
                    @endforeach
                </select>

                <div class="mt-6 flex justify-center gap-6">
                    <button type="button" x-on:click="$dispatch('close')"
                        class="w-40 bg-gray-300 hover:bg-gray-400 rounded-lg py-2 text-lg">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="w-40 bg-green-600 hover:bg-green-700 text-white rounded-lg py-2 text-lg">
                        Salvar
                    </button>
                </div>
            </form>
        </div>
    </x-modal>
    @endunless

    {{-- Modal Deletar --}}
    <x-modal name="deletar-produto" maxWidth="md">
        <div x-data="{ p: {} }"
            @deletar-produto.window="p = $event.detail"
            class="border-2 border-[#111fb4] rounded-lg p-8 text-center">

            <h2 class="text-2xl font-bold">Deletar Produto</h2>

            <svg class="w-16 h-16 mx-auto mt-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
            </svg>

            <p class="mt-6 text-sm font-semibold">Você tem certeza que deseja deletar este produto?</p>
            <p class="text-sm font-semibold">Essa ação não poderá ser desfeita.</p>

            <form method="POST" :action="p.action" class="mt-10 flex justify-center gap-6">
                @csrf
                @method('DELETE')

                <button type="button" x-on:click="$dispatch('close')"
                    class="w-44 bg-gray-300 hover:bg-gray-400 rounded-lg py-2 text-lg">
                    Não, mantê-lo.
                </button>
                <button type="submit"
                    class="w-44 bg-red-500 hover:bg-red-600 text-white rounded-lg py-2 text-lg">
                    Sim, deletar!
                </button>
            </form>
        </div>
    </x-modal>
</x-app-layout>