<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Produto;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    public function index(Request $request)
    {
        $query = Produto::with(['categoria', 'usuario'])
            ->where('user_id', '!=', auth()->id());

        if ($request->filled('busca')) {
            $query->where('nome', 'like', '%' . $request->busca . '%');
        }

        if ($request->filled('categoria')) {
            $query->where('categoria_id', $request->categoria);
        }

        $produtos = $query->latest()->paginate(6)->withQueryString();

        $categorias = Categoria::orderBy('nome')->get();

        return view('dashboard', compact('produtos', 'categorias'));
    }
}