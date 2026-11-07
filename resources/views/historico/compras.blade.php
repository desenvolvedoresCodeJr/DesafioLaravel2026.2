<x-app-layout>
    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            <h1 class="text-3xl font-bold mb-6">Histórico de Compras</h1>

            <form method="GET" action="{{ route('compras.index') }}" class="flex flex-wrap items-end gap-4 mb-6">
                <div>
                    <label class="block text-sm">De</label>
                    <input type="date" name="de" value="{{ request('de') }}" class="border border-[#111fb4] rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm">Até</label>
                    <input type="date" name="ate" value="{{ request('ate') }}" class="border border-[#111fb4] rounded-lg px-3 py-2">
                </div>
                <button type="submit" class="bg-[#111fb4] text-white rounded-lg px-8 py-3 text-lg">Filtrar</button>
                <a href="{{ route('compras.pdf', request()->only('de', 'ate')) }}" target="_blank"
                   class="ml-auto bg-green-600 hover:bg-green-700 text-white rounded-lg px-8 py-3 text-lg">
                   Relatório do período (PDF)
                </a>
            </form>

            @if (session('status'))
                <div class="mb-4 rounded-lg bg-green-100 text-green-800 px-4 py-3">{{ session('status') }}</div>
            @endif

            <div class="border border-[#111fb4] rounded-2xl overflow-hidden">
                <table class="w-full text-center">
                    <thead>
                        <tr class="border-b border-[#111fb4] text-lg">
                            <th class="py-5">Foto</th>
                            <th class="py-5">Produto</th>
                            <th class="py-5">Data</th>
                            <th class="py-5">Valor</th>
                            <th class="py-5">Relatório</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($itens as $item)
                            <tr>
                                <td class="py-4">
                                    @if ($item->produto->foto_url)
                                        <img src="{{ $item->produto->foto_url }}" class="w-32 h-24 object-cover mx-auto">
                                    @else
                                        <div class="w-32 h-24 bg-gray-200 mx-auto flex items-center justify-center text-xs text-gray-500">Sem foto</div>
                                    @endif
                                </td>
                                <td class="py-4 text-lg">{{ $item->produto->nome }}</td>
                                <td class="py-4 text-lg">{{ formatar_data($item->created_at) }}</td>
                                <td class="py-4 text-lg">{{ formatar_dinheiro($item->valor) }}</td>
                                <td class="py-4">
                                    <a href="{{ route('compras.item.pdf', $item) }}" target="_blank" class="font-bold text-lg">PDF</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="py-16 text-gray-500">Nenhuma compra encontrada.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-8">{{ $itens->links() }}</div>
        </div>
    </div>
</x-app-layout>