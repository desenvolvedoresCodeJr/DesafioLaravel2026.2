@props(['produto'])

<div class="border-4 border-[#111fb4] bg-white p-4">
    @if ($produto->foto_url)
        <img src="{{ $produto->foto_url }}" alt="{{ $produto->nome }}" 
        class="w-full h-60 object-cover">
    @endif

    <p class="mt-4 text-sm">{{ $produto->nome }}</p>
    <p class="mt-2 text-sm font-bold">{{ formatar_dinheiro($produto->preco) }}</p>

    <div class="mt-3 flex justify-center gap-2">
        {{ $slot }}
    </div>
</div>