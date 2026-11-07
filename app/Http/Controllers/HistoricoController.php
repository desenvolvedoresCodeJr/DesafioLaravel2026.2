<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CompraItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;


class HistoricoController extends Controller
{
    public function compras(Request $request)
    {
        abort_if(auth()->user()->is_admin, 403);

        $itens = $this->queryCompras($request)->paginate(6)->withQueryString();

        return view('historico.compras', compact('itens'));
    }

    public function comprasPdf(Request $request)
    {
        abort_if(auth()->user()->is_admin, 403);

        $itens = $this->queryCompras($request)->get();

        return Pdf::loadView('historico.pdf', [
            'titulo' => 'Relatório de Compras',
            'itens'  => $itens,
            'periodo' => $this->periodo($request),
        ])->stream('relatorio-compras.pdf');
    }

    public function compraPdf(CompraItem $item)
    {
        abort_unless($item->compra->user_id === auth()->id(), 403);

        return Pdf::loadView('historico.pdf', [
            'titulo' => 'Comprovante de Compra',
            'itens'  => collect([$item->load(['produto.categoria', 'compra.comprador', 'vendedor'])]),
            'periodo' => null,
        ])->stream('comprovante-compra-' . $item->id . '.pdf');
    }

    public function vendas(Request $request)
    {
        $itens = $this->queryVendas($request)->paginate(6)->withQueryString();

        return view('historico.vendas', compact('itens'));
    }

    public function vendasPdf(Request $request)
    {
        $itens = $this->queryVendas($request)->get();

        return Pdf::loadView('historico.pdf', [
            'titulo' => 'Relatório de Vendas',
            'itens'  => $itens,
            'periodo' => $this->periodo($request),
        ])->stream('relatorio-vendas.pdf');
    }

    public function vendaPdf(CompraItem $item)
    {
        abort_unless(auth()->user()->is_admin || $item->vendedor_id === auth()->id(), 403);

        return Pdf::loadView('historico.pdf', [
            'titulo' => 'Comprovante de Venda',
            'itens'  => collect([$item->load(['produto.categoria', 'compra.comprador', 'vendedor'])]),
            'periodo' => null,
        ])->stream('comprovante-venda-' . $item->id . '.pdf');
    }

    public function vendasXlsx(Request $request)
    {
        abort_unless(auth()->user()->is_admin, 403);

        $linhas = $this->queryVendas($request)->get()->map(fn ($i) => [
            $i->created_at->format('d/m/Y'),
            $i->produto->nome,
            $i->produto->categoria->nome,
            $i->quantidade,
            number_format($i->valor, 2, ',', '.'),
            $i->compra->comprador->name,
            $i->vendedor->name,
        ]);

        $export = new class($linhas) implements FromCollection, WithHeadings {
            public function __construct(private $linhas) {}
            public function collection() { return $this->linhas; }
            public function headings(): array {
                return ['Data', 'Produto', 'Categoria', 'Quantidade', 'Valor (R$)', 'Comprador', 'Vendedor'];
            }
        };

        return Excel::download($export, 'relatorio-vendas.xlsx');
    }

    private function queryCompras(Request $request)
    {
        $query = CompraItem::with(['produto.categoria', 'compra.comprador', 'vendedor'])
            ->whereHas('compra', fn ($q) => $q->where('user_id', auth()->id()))
            ->latest();

        return $this->filtrarPeriodo($query, $request);
    }

    private function queryVendas(Request $request)
    {
        $query = CompraItem::with(['produto.categoria', 'compra.comprador', 'vendedor'])->latest();

        if (!auth()->user()->is_admin) {
            $query->where('vendedor_id', auth()->id());
        }

        return $this->filtrarPeriodo($query, $request);
    }

    private function filtrarPeriodo($query, Request $request)
    {
        if ($request->filled('de')) {
            $query->whereDate('created_at', '>=', $request->de);
        }
        if ($request->filled('ate')) {
            $query->whereDate('created_at', '<=', $request->ate);
        }

        return $query;
    }

    private function periodo(Request $request): ?string
    {
        if (!$request->filled('de') && !$request->filled('ate')) {
            return null;
        }

        return ($request->de ? date('d/m/Y', strtotime($request->de)) : 'início')
            . ' a '
            . ($request->ate ? date('d/m/Y', strtotime($request->ate)) : 'hoje');
    }
}