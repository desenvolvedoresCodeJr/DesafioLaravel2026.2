<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CarrinhoItem;
use App\Models\Produto;

class CarrinhoController extends Controller
{
    public function index()
    {
        abort_if(auth()->user()->is_admin, 403);

        $itens = auth()->user()->carrinho()->with('produto')->get();

        $subtotal = $itens->sum(fn ($i) => $i->produto->preco * $i->quantidade);
        $frete = $itens->isEmpty() ? 0 : 29.00; // valor fixo do protótipo
        $total = $subtotal + $frete;

        return view('carrinho.index', compact('itens', 'subtotal', 'frete', 'total'));
    }

    public function adicionar(Request $request, Produto $produto)
    {
        abort_if(auth()->user()->is_admin, 403);
        abort_if($produto->user_id === auth()->id(), 403); // não compra o próprio produto

        $quantidade = max(1, (int) $request->input('quantidade', 1));

        $item = CarrinhoItem::firstOrNew([
            'user_id' => auth()->id(),
            'produto_id' => $produto->id,
        ]);

        $item->quantidade = min(($item->quantidade ?? 0) + $quantidade, $produto->quantidade);
        $item->save();

        return redirect()->route('carrinho.index');
    }

    public function atualizar(Request $request, CarrinhoItem $item)
    {
        abort_unless($item->user_id === auth()->id(), 403);

        $dados = $request->validate(['quantidade' => ['required', 'integer', 'min:1']]);
        $item->update(['quantidade' => min($dados['quantidade'], $item->produto->quantidade)]);

        return redirect()->route('carrinho.index');
    }

    public function remover(CarrinhoItem $item)
    {
        abort_unless($item->user_id === auth()->id(), 403);
        $item->delete();

        return redirect()->route('carrinho.index');
    }

    public function esvaziar()
    {
        auth()->user()->carrinho()->delete();

        return redirect()->route('carrinho.index');
    }
}