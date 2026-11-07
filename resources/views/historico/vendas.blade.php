<x-app-layout>
    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex flex-wrap items-center gap-4 mb-6">
                <h1 class="text-3xl font-bold">Histórico de Vendas</h1>

                @unless (auth()->user()->is_admin)
                    <a href="{{ route('grafico.vendas') }}" class="ml-auto bg-[#111fb4] text-white text-lg font-semibold rounded-lg px-8 py-3">Gráfico</a>
                @endunless
            </div>

            <form method="GET" action="{{ route('vendas.index') }}" class="flex flex-wrap items-end gap-4 mb-6">
                <div>
                    <label class="block text-sm">De</label>
                    <input type="date" name="de" value="{{ request('de') }}" class="border border-[#111fb4] rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm">Até</label>
                    <input type="date" name="ate" value="{{ request('ate') }}" class="border border-[#111fb4] rounded-lg px-3 py-2">
                </div>
                <button type="submit" class="bg-[#111fb4] text-white rounded-lg px-8 py-3 text-lg">Filtrar</button>

                <div class="ml-auto flex gap-3">
                    <a href="{{ route('vendas.pdf', request()->only('de', 'ate')) }}" target="_blank"
                       class="bg-green-600 hover:bg-green-700 text-white rounded-lg px-8 py-3 text-lg">Relatório (PDF)</a>
                    @if (auth()->user()->is_admin)
                        <a href="{{ route('vendas.xlsx', request()->only('de', 'ate')) }}"
                           class="bg-green-600 hover:bg-green-700 text-white rounded-lg px-8 py-3 text-lg">Relatório (XLSX)</a>
                    @endif
                </div>
            </form>

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
                                    <a href="{{ route('vendas.item.pdf', $item) }}" target="_blank" class="font-bold text-lg underline">PDF</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="py-16 text-gray-500">Nenhuma venda encontrada.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-8">{{ $itens->links() }}</div>
        </div>
    </div>
</x-app-layout>