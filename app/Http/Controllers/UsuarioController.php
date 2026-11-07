<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = User::where('is_admin', false)->latest()->paginate(6);

        return view('usuarios.index', compact('usuarios'));
    }

    public function store(Request $request)
    {
        $dados = $this->validar($request);

        if ($request->hasFile('foto')) {
            $dados['foto'] = $request->file('foto')->store('usuarios', 'public');
        }

        $dados['saldo'] = $dados['saldo'] ?? 0;
        $dados['is_admin'] = false;

        User::create($dados);

        return redirect()->route('usuarios.index')->with('status', 'Usuário criado com sucesso!');
    }

    public function update(Request $request, User $usuario)
    {
        abort_if($usuario->is_admin, 403);

        $dados = $this->validar($request, $usuario);

        if (empty($dados['password'])) {
            unset($dados['password']);
        }

        if ($request->hasFile('foto')) {
            $this->apagarFoto($usuario);
            $dados['foto'] = $request->file('foto')->store('usuarios', 'public');
        }

        $dados['saldo'] = $dados['saldo'] ?? $usuario->saldo;

        $usuario->update($dados);

        return redirect()->route('usuarios.index')->with('status', 'Usuário atualizado!');
    }

    public function destroy(User $usuario)
    {
        abort_if($usuario->is_admin, 403);

        if ($usuario->produtos()->exists() || $usuario->compras()->exists() || $usuario->vendas()->exists()) {
            return redirect()->route('usuarios.index')
                ->with('erro', 'Este usuário possui produtos ou transações e não pode ser deletado.');
        }

        $this->apagarFoto($usuario);
        $usuario->delete();

        return redirect()->route('usuarios.index')->with('status', 'Usuário deletado!');
    }

    private function validar(Request $request, ?User $usuario = null): array
    {
        return $request->validate([
            'name'            => ['required', 'string', 'max:255'],
            'email'           => ['required', 'email', 'max:255', 'unique:users,email,' . $usuario?->id],
            'password'        => [$usuario ? 'nullable' : 'required', 'string', 'min:8'],
            'cep'             => ['required', 'string', 'max:9'],
            'numero'          => ['required', 'string', 'max:20'],
            'logradouro'      => ['required', 'string', 'max:255'],
            'bairro'          => ['required', 'string', 'max:255'],
            'cidade'          => ['required', 'string', 'max:255'],
            'estado'          => ['required', 'string', 'max:255'],
            'complemento'     => ['nullable', 'string', 'max:255'],
            'telefone'        => ['required', 'string', 'max:20'],
            'data_nascimento' => ['required', 'date', 'before:today'],
            'cpf'             => ['required', 'string', 'max:14', 'unique:users,cpf,' . $usuario?->id],
            'saldo'           => ['nullable', 'numeric', 'min:0'],
            'foto'            => ['nullable', 'image', 'max:2048'],
        ]);
    }

    private function apagarFoto(User $usuario): void
    {
        if ($usuario->foto) {
            Storage::disk('public')->delete($usuario->foto);
        }
    }
}