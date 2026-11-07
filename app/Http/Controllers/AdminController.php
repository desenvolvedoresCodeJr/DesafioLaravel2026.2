<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function index()
    {
        $admins = User::where('is_admin', true)->orderBy('name')->paginate(6);

        return view('admins.index', compact('admins'));
    }

    public function store(Request $request)
    {
        $dados = $this->validar($request);

        if ($request->hasFile('foto')) {
            $dados['foto'] = $request->file('foto')->store('usuarios', 'public');
        }

        $dados['is_admin'] = true;
        $dados['criado_por'] = auth()->id();
        $dados['saldo'] = 0;

        User::create($dados);

        return redirect()->route('admins.index')->with('status', 'Administrador criado com sucesso!');
    }

    public function update(Request $request, User $admin)
    {
        abort_unless($admin->podeSerGerenciadoPor(auth()->user()), 403);

        $dados = $this->validar($request, $admin);

        if (empty($dados['password'])) {
            unset($dados['password']);
        }

        if ($request->hasFile('foto')) {
            $this->apagarFoto($admin);
            $dados['foto'] = $request->file('foto')->store('usuarios', 'public');
        }

        $admin->update($dados);

        return redirect()->route('admins.index')->with('status', 'Administrador atualizado!');
    }

    public function destroy(User $admin)
    {
        abort_unless($admin->podeSerGerenciadoPor(auth()->user()), 403);

        if ($admin->id === auth()->id()) {
            return redirect()->route('admins.index')->with('erro', 'Você não pode excluir o seu próprio perfil.');
        }

        $this->apagarFoto($admin);
        $admin->delete();

        return redirect()->route('admins.index')->with('status', 'Administrador deletado!');
    }

    private function validar(Request $request, ?User $admin = null): array
    {
        return $request->validate([
            'name'            => ['required', 'string', 'max:255'],
            'email'           => ['required', 'email', 'max:255', 'unique:users,email,' . $admin?->id],
            'password'        => [$admin ? 'nullable' : 'required', 'string', 'min:8'],
            'cep'             => ['required', 'string', 'max:9'],
            'logradouro'      => ['required', 'string', 'max:255'],
            'numero'          => ['required', 'string', 'max:20'],
            'bairro'          => ['required', 'string', 'max:255'],
            'cidade'          => ['required', 'string', 'max:255'],
            'estado'          => ['required', 'string', 'max:255'],
            'complemento'     => ['nullable', 'string', 'max:255'],
            'telefone'        => ['required', 'string', 'max:20'],
            'data_nascimento' => ['required', 'date', 'before:today'],
            'cpf'             => ['required', 'string', 'max:14', 'unique:users,cpf,' . $admin?->id],
            'foto'            => ['nullable', 'image', 'max:2048'],
        ]);
    }

    private function apagarFoto(User $admin): void
    {
        if ($admin->foto) {
            Storage::disk('public')->delete($admin->foto);
        }
    }
}
