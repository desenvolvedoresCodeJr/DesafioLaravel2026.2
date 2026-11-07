<?php

namespace App\Http\Controllers;

use App\Models\CompraItem;
use App\Models\Produto;

class GraficoController extends Controller
{
    public function produtos()
    {
        abort_unless(usuario_e_admin(), 403);

        $dados = $this->ultimosMeses(Produto::query(), 'created_at');

        return view('graficos.produtos', $dados);
    }

    public function vendas()
    {
        abort_if(usuario_e_admin(), 403);

        $query = CompraItem::where('vendedor_id', auth()->id());

        return view('graficos.vendas', $this->ultimosMeses($query, 'created_at'));
    }

    private function ultimosMeses($query, string $coluna): array
    {
        $nomes = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
        $inicio = now()->startOfMonth()->subMonths(11);

        $contagem = $query->where($coluna, '>=', $inicio)->get()
            ->groupBy(fn ($r) => $r->$coluna->format('Y-m'))
            ->map->count();

        $labels = [];
        $valores = [];

        for ($i = 0; $i < 12; $i++) {
            $mes = $inicio->copy()->addMonths($i);
            $labels[] = $nomes[$mes->month - 1] . '/' . $mes->format('y');
            $valores[] = $contagem[$mes->format('Y-m')] ?? 0;
        }

        return compact('labels', 'valores');
    }
}