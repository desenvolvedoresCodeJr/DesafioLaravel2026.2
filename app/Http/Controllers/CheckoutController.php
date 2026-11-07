<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Compra;
use App\Models\CompraItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class CheckoutController extends Controller
{
    public function iniciar()
    {
        abort_if(auth()->user()->is_admin, 403);

        $itens = auth()->user()->carrinho()->with('produto')->get();

        if ($itens->isEmpty()) {
            return redirect()->route('carrinho.index')->with('erro', 'Seu carrinho está vazio.');
        }

        $items = $itens->map(fn ($i) => [
            'title'       => $i->produto->nome,
            'quantity'    => (int) $i->quantidade,
            'unit_price'  => (float) $i->produto->preco,
            'currency_id' => 'BRL',
        ])->values()->all();

        $resposta = Http::withToken(config('services.mercadopago.token'))
            ->post('https://api.mercadopago.com/checkout/preferences', [
                'items' => $items,
                'external_reference' => (string) auth()->id(),
                'back_urls' => [
                    'success' => route('checkout.retorno'),
                    'failure' => route('checkout.retorno'),
                    'pending' => route('checkout.retorno'),
                ],
            ]);

        if ($resposta->failed()) {
            return redirect()->route('carrinho.index')
                ->with('erro', 'Não foi possível iniciar o pagamento. Tente novamente.');
        }

        return redirect()->away($resposta->json('sandbox_init_point') ?? $resposta->json('init_point'));
    }

    public function retorno(Request $request)
    {
        $paymentId = $request->query('payment_id');

        if (!$paymentId) {
            return redirect()->route('carrinho.index')->with('erro', 'Pagamento não concluído.');
        }

        $pagamento = Http::withToken(config('services.mercadopago.token'))
            ->get("https://api.mercadopago.com/v1/payments/{$paymentId}");

        if ($pagamento->failed()
            || $pagamento->json('status') !== 'approved'
            || $pagamento->json('external_reference') !== (string) auth()->id()) {
            return redirect()->route('carrinho.index')->with('erro', 'Pagamento não aprovado.');
        }

        $itens = auth()->user()->carrinho()->with('produto')->get();

        if ($itens->isEmpty()) {
            return redirect()->route('compras.index')->with('status', 'Compra já registrada.');
        }

        DB::transaction(function () use ($itens) {
            $compra = Compra::create([
                'user_id' => auth()->id(),
                'valor_total' => $itens->sum(fn ($i) => $i->produto->preco * $i->quantidade),
            ]);

            foreach ($itens as $item) {
                CompraItem::create([
                    'compra_id'   => $compra->id,
                    'produto_id'  => $item->produto_id,
                    'vendedor_id' => $item->produto->user_id,
                    'quantidade'  => $item->quantidade,
                ]);

                $item->produto->usuario->increment('saldo', $item->produto->preco * $item->quantidade);
            }

            auth()->user()->carrinho()->delete();
        });

        return redirect()->route('dashboard')->with('status', 'Compra realizada com sucesso!');
    }
}