<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h1 { color: #111fb4; font-size: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border: 1px solid #111fb4; padding: 6px; text-align: left; }
        th { background: #cacbea; }
        .total { margin-top: 12px; text-align: right; font-weight: bold; }
    </style>
</head>
<body>
    <h1>{{ $titulo }}</h1>
    <p>Emitido em {{ now()->format('d/m/Y H:i') }}@if ($periodo) · Período: {{ $periodo }}@endif</p>

    <table>
        <thead>
            <tr>
                <th>Data</th>
                <th>Produto</th>
                <th>Categoria</th>
                <th>Valor</th>
                <th>Comprador</th>
                <th>Vendedor</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($itens as $i)
                <tr>
                    <td>{{ $i->created_at->format('d/m/Y') }}</td>
                    <td>{{ $i->produto->nome }} (x{{ $i->quantidade }})</td>
                    <td>{{ $i->produto->categoria->nome }}</td>
                    <td>R$ {{ number_format($i->valor, 2, ',', '.') }}</td>
                    <td>{{ $i->compra->comprador->name }}</td>
                    <td>{{ $i->vendedor->name }}</td>
                </tr>
            @empty
                <tr><td colspan="6">Nenhuma transação no período.</td></tr>
            @endforelse
        </tbody>
    </table>

    <p class="total">Total: R$ {{ number_format($itens->sum('valor'), 2, ',', '.') }}</p>
</body>
</html>