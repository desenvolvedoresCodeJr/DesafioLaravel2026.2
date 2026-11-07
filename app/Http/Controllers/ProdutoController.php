<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Produto;
use App\Models\CompraItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

    public function show(Produto $produto)
    {
        $produto->load(['categoria', 'usuario']);

        return view('produtos.show', compact('produto'));
    }

    public function gerenciar()
    {
        $query = Produto::with(['categoria', 'usuario'])->latest();

        if (!auth()->user()->is_admin) {
            $query->where('user_id', auth()->id());
        }

        $produtos = $query->paginate(6);
        $categorias = Categoria::orderBy('nome')->get();

        return view('produtos.gerenciar', compact('produtos', 'categorias'));
    }

    public function store(Request $request)
    {
        abort_if(auth()->user()->is_admin, 403);

        $dados = $this->validarProduto($request);

        if ($request->hasFile('foto')) {
            $dados['foto'] = $request->file('foto')->store('produtos', 'public');
        }

        $dados['user_id'] = auth()->id();
        Produto::create($dados);

        return redirect()->route('produtos.gerenciar')->with('status', 'Produto anunciado com sucesso!');
    }

    public function update(Request $request, Produto $produto)
    {
        abort_unless($produto->user_id === auth()->id() && !auth()->user()->is_admin, 403);

        $dados = $this->validarProduto($request);

        if ($request->hasFile('foto')) {
            $this->apagarFoto($produto);
            $dados['foto'] = $request->file('foto')->store('produtos', 'public');
        }

        $produto->update($dados);

        return redirect()->route('produtos.gerenciar')->with('status', 'Produto atualizado!');
    }

    public function destroy(Produto $produto)
    {
        abort_unless($produto->user_id === auth()->id() || auth()->user()->is_admin, 403);

        if (CompraItem::where('produto_id', $produto->id)->exists()) {
            return redirect()->route('produtos.gerenciar')
                ->with('erro', 'Este produto já possui vendas e não pode ser deletado.');
        }

        $this->apagarFoto($produto);
        $produto->delete();

        return redirect()->route('produtos.gerenciar')->with('status', 'Produto deletado!');
    }

    private function validarProduto(Request $request): array
    {
        return $request->validate([
            'nome'         => ['required', 'string', 'max:255'],
            'descricao'    => ['nullable', 'string'],
            'preco'        => ['required', 'numeric', 'min:0'],
            'quantidade'   => ['required', 'integer', 'min:1'],
            'categoria_id' => ['required', 'exists:categorias,id'],
            'foto'         => ['nullable', 'image', 'max:2048'],
        ]);
    }

    private function apagarFoto(Produto $produto): void
    {
        if ($produto->foto && !str_starts_with($produto->foto, 'http')) {
            Storage::disk('public')->delete($produto->foto);
        }
    }
}
